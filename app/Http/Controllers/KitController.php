<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use App\Models\Order;
use App\Models\Kit;
use App\Imports\KitsImport;

class KitController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ((Session::get('grandidsession')===null)){
            return  view('admin.login');
        }
        return view('admin.kits'/*, ['kits' => Kit::all()]*/);
        
    }
    
    
    /**
     * Get the collection of kits.
     *
     * @return string
     */
    public function getKits()
    {
        
        $kits =  Kit::with(['user', 'order', 'sample']);
        //return $kits->get();
        
        return DataTables::of($kits)
            ->addIndexColumn()
            ->addColumn('name', function($row){
                return $row->user->first_name." ".$row->user->last_name;
            })
            ->addColumn('action', function($row){
                
                $action_url = "";
                
                if($row->sample == null){
                    
                    //register sample
                    $action_url .= '<a href="'.url("/admin/kits/$row->id/registerSample").'" >'.
                        '<button class="btn btn-outline-primary" type="button" data-toggle="tooltip" title="Register Sample">
                        <i class="fas fa-flask"></i>
                        </button>
                        </a>';
                }
                
                //edit kit
                $action_url .= '<a href="'.url("/admin/kits/$row->id/edit/kits").'" >'.
                    '<button class="btn btn-outline-primary" type="button" data-toggle="tooltip" title="Edit Kit Information">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
      				<path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
      				<path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
    				</svg>
    				</button>
    				</a>';
                    
                //delete kit
                $action_url .= '<form action="'.action('KitController@destroy', ['id' => $row->id]). '" method="post" onsubmit="return confirm(\'Are you sure you want to delete the kit?\');">'.
                    //@csrf equivalent html for blade directive @csrf
                    '<input type="hidden" name="_token" value="'.csrf_token(). '" />'.
                    //@method("DELETE") equivalent html for blade directive @method
                    '<input type="hidden" name="_method" value="DELETE">
        				<button class="btn btn-outline-danger" type="submit" data-toggle="tooltip" title="Delete Kit">
        				<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        	<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                        	<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                        </svg>
    				    </button>
                    </form>';
                    
                
                return $action_url;
            })
            ->make(true);
        
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function create($id) {
        $order = Order::find($id);
        return view('admin.register_kit', compact('order'));
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        
        //dd($request);
        $request->validate([
            'sample_id'=>'required|unique:kits,sample_id',
            'barcode'=>'sometimes|nullable|unique:kits,barcode',
            'kit_dispatched_date'=>'sometimes|nullable|date' 
        ]);
        
        $order = Order::find($id);
        
        $kit = new Kit([
            'order_id' => $id,
            'user_id' => $order->user->id,
            'sample_id' => $request->get('sample_id'),
            'barcode' => $request->get('barcode'),
            'kit_dispatched_date' => $request->get('kit_dispatched_date')
        ]); 
        
        $kit->save();
        
        $order->update(['status' => config('constants.kits.KIT_REGISTERED')]);
        
        if($request->filled('kit_dispatched_date')){
            $order->update(['status' => config('constants.kits.KIT_DISPATCHED')]);
        }
        
        
        
        return redirect('admin/orders')->with("kit_registered", "The Kit is registered for the order!");
    }
    
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $type = null)
    {
        //
        if ((Session::get('grandidsession')===null)){
            return  view('admin.login');
        }
        
        $kit = Kit::find($id);
        
        if($type === "kits"){
            return view('admin.edit_kit', compact('kit'));
        }
        
        return view('admin.edit_register_kit', compact('kit'));
    }
    
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  string $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $type=null)
    {   //dd($request);
        $kit = Kit::find($id);
        
        $request->validate([
            'sample_id'=>'required|unique:kits,sample_id,'.$id,
            'barcode'=>'sometimes|nullable|unique:kits,barcode,'.$id,
            'kit_dispatched_date'=>'sometimes|nullable|date',
            'sample_received_date'=>'sometimes|nullable|date|after_or_equal:kit_dispatched_date'
        ]);
        
        /*** Conditional update of kit removed. Kit can be updated same way from Orders and Kits menu.***/
        /*
        if($type === "kits"){
            $request->validate([
                'sample_id'=>'required|unique:kits,sample_id,'.$id,
                'barcode'=>'sometimes|nullable|unique:kits,barcode,'.$id,
                'kit_dispatched_date'=>'sometimes|nullable|date',
                'sample_received_date'=>'sometimes|nullable|date|after_or_equal:kit_dispatched_date'
            ]);
        }
        else{
            $request->validate([
                'sample_id'=>'required|unique:kits,sample_id,'.$id,
                'barcode'=>'sometimes|nullable|unique:kits,barcode,'.$id,
                'kit_dispatched_date'=>'sometimes|nullable|date|before_or_equal:'.$kit->sample_received_date,
            ]);
        }
        */
       
        $kit->update($request->all());
        //checking if kit has a sample
        if($kit->sample()->count() && $kit->sample->reporting_date){
            $kit->order->update(['status' => config('constants.results.RESULT_RECEIVED')]);
        }
        elseif($kit->sample()->count() && $kit->sample->sample_registered_date){
            $kit->order->update(['status' => config('constants.samples.SAMPLE_REGISTERED')]);
        }
        elseif($request->filled('sample_received_date') || $kit->sample_received_date){
            $kit->order->update(['status' => config('constants.samples.SAMPLE_RECEIVED')]);
        }
        elseif($request->filled('kit_dispatched_date') || $kit->kit_dispatched_date){
            $kit->order->update(['status' => config('constants.kits.KIT_DISPATCHED')]);
        }
        else{
            $kit->order->update(['status' => config('constants.kits.KIT_REGISTERED')]);
        }
        
        
        
        if($type === "kits"){
            return redirect('admin/kits')->with("kit_updated", "The Kit is updated!");
        }
        
        return redirect('admin/orders')->with("kit_updated", "The Kit is updated for the order!");
        
    }
    
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try{
        
            $kit = Kit::find($id);
            $kit->delete();
            $kit->order->update(['status' => config('constants.orders.ORDER_CREATED')]);
            return back()->with('kit_deleted', "Kit Deleted!");
        }
        catch (\Illuminate\Database\QueryException $e){
            
            return back()->with('kit_not_deleted', "Kit cannot be deleted! Sample already registered for the kit. To delete
                                    the kit, first delete the associated sample.");
        }
    }
    
    
    /**
     * Show the kit import form.
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        //
        if ((Session::get('grandidsession')===null)){
            return  view('admin.login');
        }
        
        return view('admin.import_kits');
    }
    
    
    /**
     * Import collections in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function importKitSave(Request $request) {
        
        Validator::make($request->all(), [
                'kits_file' => 'required|mimes:xls,xlsx',
            ],
            [
                'required' => "Please provide the import file." ,
                'mimes' => "The import file must be an excel file (.xls/.xlsx). "
            ]
        )->validate();
        
        try {
            
            $import = new KitsImport();
            
            //In case trait Importable is used in Import Class.
            //$import->import($request->file('users_file'));
            
            //Otherwise use Facade.
            Excel::import($import, $request->file('kits_file'));
            
            //return back()->with('kits_import_success', $import->getRowCount().' Kits have been imported successfully!');
            return back()->with('kits_import_success', '<strong>'.$import->getRowCount().'</strong> Kits/Samples have been processed successfully! <br>
                            of which <strong>'.$import->getInsertedRowCount().'</strong> Kits/Samples have been inserted and <strong>
                            '.$import->getUpdatedRowCount(). '</strong> Kits/Samples have been updated.');
            
        }catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            dd($e);
        }
        catch (\Maatwebsite\Excel\Exceptions\NoTypeDetectedException $e) {
            //dd($e);
        }
    }
}
