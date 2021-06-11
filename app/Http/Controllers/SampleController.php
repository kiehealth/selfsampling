<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Kit;
use App\Models\Sample;
use App\Models\User;

use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

use App\Imports\SamplesImport;

class SampleController extends Controller
{
    //
    
    /**
     * Show the form for registering the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function registerSample($id)
    {
        //
        if ((Session::get('grandidsession')===null)){
            return  view('admin.login');
        }
        
        $kit = Kit::find($id);
        return view('admin.register_sample', compact('kit'));
        
        
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request, $id)
    {
        
        $request->validate([
            'sample_id'=>'required|unique:samples,sample_id',
            'sample_registered_date'=>'required|date'
        ]);
        
        $kit = Kit::find($id);
        $kit->update(['sample_id' => $request->sample_id]);
        
        $sample = new Sample([
            'kit_id' => $id,
            'sample_id' => $request->sample_id,
            'sample_registered_date' => $request->sample_registered_date
        ]);
        
        $sample->save();
        
        if(!$kit->sample_received_date){
            //$kit->update(['sample_received_date' => Carbon::now()->toDateString()]);
            $kit->update(['sample_received_date' => $sample->sample_registered_date]);
        }
        
        $order = $kit->order;
        $order->update(['status' => config('constants.samples.SAMPLE_REGISTERED')]);
        
        return redirect('admin/kits')->with("sample_registered", "The sample with sample_id <strong>".$kit->sample_id."</strong> is registered successfully!");
        
    }
    
    
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
        
        return view('admin.samples'/*, ['samples' => Sample::all()]*/);
        
    }
    
    
    /**
     * Get the collection of samples.
     *
     * @return string
     */
    public function getSamples()
    {
        
        $samples =  Sample::with(['kit', 'kit.user', 'kit.order']);
        //return $samples->get();
        
        return DataTables::of($samples)
            ->addIndexColumn()
            ->addColumn('name', function($row){
                return $row->kit->user->first_name." ".$row->kit->user->last_name;
            })
            ->addColumn('action', function($row){
                
                $action_url = "";
                
                //edit sample
                $action_url .= '<a href="'.url("/admin/samples/$row->id/edit").'" >'.
                    '<button class="btn btn-outline-primary" type="button" data-toggle="tooltip" title="Edit Sample Information">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                    </svg>
                    </button>
        			</a>';
                
                
                //delete sample
                $action_url .= '<form action="'.action('SampleController@destroy', ['id' => $row->id]). '" method="post" onsubmit="return confirm(\'Are you sure you want to delete the sample?\');">'.
                    //@csrf equivalent html for blade directive @csrf
                    '<input type="hidden" name="_token" value="'.csrf_token(). '" />'.
                    //@method("DELETE") equivalent html for blade directive @method
                    '<input type="hidden" name="_method" value="DELETE">
            			<button class="btn btn-outline-danger" type="submit" data-toggle="tooltip" title="Delete Sample">
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        if ((Session::get('grandidsession')===null)){
            return  view('admin.login');
        }
        
        $sample = Sample::find($id);
        return view('admin.edit_sample', compact('sample'));
        
        
    }
    
    
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {//dd($request);
        $sample = Sample::find($id);
        $kit_id = $sample->kit->id;
        
        $validator = Validator::make($request->all(),[
            'sample_id'=>'required|unique:kits,sample_id,'.$kit_id.'|unique:samples,sample_id,'.$id,
            'lab_id'=>'sometimes|nullable|unique:samples,lab_id,'.$id,
            'sample_registered_date'=>'required|date',
            'cobas_analysis_date'=>'sometimes|nullable|date|after_or_equal:sample_registered_date',
            'luminex_analysis_date'=>'sometimes|nullable|date|after_or_equal:sample_registered_date',
            'rtpcr_analysis_date'=>'sometimes|nullable|date|after_or_equal:sample_registered_date',
            'final_reporting_result'=>'sometimes|nullable|required_with:reporting_date',
            'reporting_date'=>'sometimes|nullable|date|after_or_equal:sample_registered_date|required_with:final_reporting_result',
        ]/* Remove custom message for required_without_all sincee it is not used.
        ,[
            'required_without_all' => "The final reporting result is required when the reporting date is present."
        ]*/);
        
        /*
         * Complex conditional validation rule removed for lab-workflow.
        $validator->sometimes('result', 'required_without_all:cobas_result,final_reporting_result,luminex_result,rtpcr_result', function ($input) {
            return !empty($input->reporting_date);
        });
        */
     
        //dd($validator->errors());
        //dd($validator);
        
        $validator->validate();
        
        
        $sample->kit->update(['sample_id' => $request->sample_id]);
        $sample->update($request->all());
        
        if($request->filled('reporting_date')){
            $sample->kit->order->update(['status' => config('constants.results.RESULT_RECEIVED')]);
        }
        else{
            $sample->kit->order->update(['status' => config('constants.samples.SAMPLE_REGISTERED')]);
        }
        
        return redirect('admin/samples')->with("sample_updated", "The Sample is updated!");
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
        $sample = Sample::find($id);
        $sample->delete();
        
        if($sample->kit->sample_received_date){
            $sample->kit->order->update(['status' => config('constants.samples.SAMPLE_RECEIVED')]);
        }
        elseif($sample->kit->kit_dispatched_date){
            $sample->kit->order->update(['status' => config('constants.kits.KIT_DISPATCHED')]);
        }
        else{
            $sample->kit->order->update(['status' => config('constants.kits.KIT_REGISTERED')]);
        }
        
        
        return back()->with('sample_deleted', "Sample Deleted!");
        
    }
    
    
    /**
     * Show the sample import form.
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        //
        if ((Session::get('grandidsession')===null)){
            return  view('admin.login');
        }
        
        return view('admin.import_samples');
    }
    
    
    /**
     * Import collections in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function importSampleSave(Request $request) {
        
        Validator::make($request->all(), [
            'samples_file' => 'required|mimes:xls,xlsx',
        ],
            [
                'required' => "Please provide the import file." ,
                'mimes' => "The import file must be an excel file (.xls/.xlsx). "
            ]
            )->validate();
            
            try {
                
                $import = new SamplesImport() ;
                
                //In case trait Importable is used in Import Class.
                //$import->import($request->file('users_file'));
                
                //Otherwise use Facade.
                Excel::import($import, $request->file('samples_file'));
                
                return back()->with('samples_import_success', '<strong>'.$import->getRowCount().'</strong> Samples have been processed successfully! <br>
                            of which <strong>'.$import->getInsertedRowCount().'</strong> Samples have been inserted and <strong>
                            '.$import->getUpdatedRowCount(). '</strong> Samples have been updated.');
                
                
            }catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                dd($e);
            }
            catch (\Maatwebsite\Excel\Exceptions\NoTypeDetectedException $e) {
                //dd($e);
            }
    }
    
    
    
    public function myresults(){
        return SampleController::getAllResultsforUser(session('user_id'));
    }
    
    
    /**
     * Get all results for this user.
     *
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    
    public function getAllResultsforUser($id){
        
        $user = User::find($id);
        $myresults = $user->samples->whereNotNull('final_reporting_result');
        return view('my_results', compact('myresults'));
        
    }
}
