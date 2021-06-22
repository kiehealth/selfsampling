<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Personnummer\Personnummer;
use Personnummer\PersonnummerException;
use Yajra\DataTables\DataTables;
use App\Models\Order;
use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Imports\OrdersImport;

class OrderController extends Controller
{
    
    /**
     * The user repository instance.
     *
     * @var UserRepository
     */
    protected $userRepo;
    
    /**
     * Create a new controller instance.
     *
     * @param  UserRepository $userRepo
     * @return  void
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //session(['name' => 'suyesh']);
        //print session('name');
        //print_r(session()->all());
        if ((Session::get('grandidsession')===null)){
            return  view('admin.login');
        }
        return view('admin.orders'/*, ['orders' => Order::all()]*/);
       
    }
    
    /**
     * Get the collection of orders.
     *
     * @return string
     */
    public function getOrders()
    {
        /*
        adding ->select('orders.*') in order to avoid ambuiguity, the order.id was being overidden by
        user.id
        */
        $orders =  Order::with(['user', 'kit', 'kit.sample'])->select('orders.*');
        /*
         * 
         $orders =  Order::with(['user' => function ($query) {
            $query->select('first_name', 'last_name');
        }])->with(['kit', 'kit.sample']);
        */
        //return User::select(['id','first_name','created_at','updated_at'])->get();
        
        return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('name', function($row){
                    return $row->user->first_name." ".$row->user->last_name;
                })
                /*->filterColumn('user.name', function ($query, $keyword) {
                    //$query->with(array('user' => function($query) use ($keyword){
                        //dd($keyword);
                        return $query->whereRaw("CONCAT(first_name, ' ',  last_name) like ?", ["%{$keyword}%"]);
                    //}));
                })
                */
                ->addColumn('order_status', function($row){
                    if ($row->status===config('constants.kits.KIT_REGISTERED'))
                        return $row->status.' '.(($row->kit !== null)?$row->kit->created_at:"");
                    elseif ($row->status===config('constants.kits.KIT_DISPATCHED'))
                        return $row->status.' '.(($row->kit !== null)?$row->kit->kit_dispatched_date:"");
                    elseif ($row->status===config('constants.samples.SAMPLE_RECEIVED'))
                        return $row->status.' '.(($row->kit !== null)?$row->kit->sample_received_date:"");
                    elseif ($row->status===config('constants.samples.SAMPLE_REGISTERED'))
                        return $row->status.' '.(($row->kit !== null && $row->kit->sample !== null)?$row->kit->sample->sample_registered_date:"");
                    elseif ($row->status===config('constants.results.RESULT_RECEIVED'))
                        return $row->status.' '.(($row->kit !== null && $row->kit->sample !== null)?$row->kit->sample->reporting_date:"");
                    else return $row->status;
                })
                ->addColumn('action', function($row){
                    
                    $action_url = "";
                    
                    if($row->kit !== null){
                        $kit_id = $row->kit->id;
                        //edit kit
                        $action_url .= '<a href="'.url("/admin/kits/$kit_id/edit/viaord").'" >'.
                            '<button class="btn btn-outline-primary" type="button" data-toggle="tooltip" title="Edit Kit Information">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              				<path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
              				<path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
            				</svg>
            				</button>
            				</a>';
                        //delete kit
                        $action_url .= '<form action="'.action('KitController@destroy', ['id' => $row->kit->id]). '" method="post" onsubmit="return confirm(\'Are you sure you want to delete the kit for this order?\');">'.
            				//@csrf equivalent html for blade directive @csrf
                            '<input type="hidden" name="_token" value="'.csrf_token(). '" />'.
                            //@method("DELETE") equivalent html for blade directive @method
                            '<input type="hidden" name="_method" value="DELETE">
            				<button class="btn btn-outline-danger" type="submit" data-toggle="tooltip" title="Delete Kit for this order">
                            	<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-minus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              		<path fill-rule="evenodd" d="M4 0h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H4z"/>
                              		<path fill-rule="evenodd" d="M5.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5z"/>
                            	</svg>
                            </button>
                            </form>';
                    }
                    else{
                        //register kit
                        $action_url .= '<a href="'.url("/admin/orders/$row->id/registerKit").'" >'.
                            '<button class="btn btn-outline-primary" type="button" data-toggle="tooltip" title="Register Kit">
                            	<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            		<path fill-rule="evenodd" d="M4 0h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H4z"/>
                              		<path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5z"/>
                            	</svg>
            				</button>
            				</a>';
                    }
                    
                    //delete order
                    $action_url .= '<form action="'.action('OrderController@destroy', ['id' => $row->id]). '" method="post" onsubmit="return confirm(\'Are you sure you want to delete the order?\');">'.
        				//@csrf
        				//@method("DELETE")
                        '<input type="hidden" name="_token" value="'.csrf_token(). '" />'.
                        '<input type="hidden" name="_method" value="DELETE">
        				<button class="btn btn-outline-danger" type="submit" data-toggle="tooltip" title="Delete Order">
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
    public function create()
    {
        if ((Session::get('grandidsession')===null)){
            return  view('admin.login');
        }
        
        return view('admin.create_order');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        //
        $request->validate([
            'pnr'=>'required|size:12'
        ],
        [
            'pnr.size' => ($request->has('type') && $request->type === "admin")?"Please enter the 12 digit personnummer without hyphen (-)":"Vänligen änge 12 siffrigt personnummer utan bindestreck."
        ]);
      
        
        try {
            Personnummer::valid($request->pnr);
               try {
                   $user = $this->userRepo->getUserbyPNR((new Personnummer($request->pnr))->format(true));
                   if ($user->exists) {
                       $order = new Order([
                           'user_id' => $user->id,
                           'order_created_by' => is_null(Session::get('userattributes'))?null:Str::title(Session::get('userattributes')['givenName'])." ".Str::title(Session::get('userattributes')['surname'])
                       ]);
                       /*
                        *
                        * Alternatively
                        *
                        * $user->orders()->save($order);
                        *
                        */
                       
                       $order->save();
                       $user->update(['consent' => 1]);
                       
                       $order_success_msg = "Din beställning har tagits emot och den kommer att skickas
                            till din folkbokföringsadress om några dagar.
                            Om du vill att den ska skickas till en annan adress eller se
                            status kan du göra det genom att logga in på <a href=".url('/myprofile').">mina sidor</a>
                            eller kontakta oss på hpvcenter@ki.se.";
                       if($request->has('type') && $request->type === "admin"){
                           return redirect('admin/orders')->with('order_created', "Order created succussfully for ".$request->pnr."!");
                       }
                       return back()->with('order_created', $order_success_msg);
                       //return view('home', ['order_created'=>"Order Received!"]);
                   }
               } catch (ModelNotFoundException $e) {
                   if($request->has('type') && $request->type === "admin"){
                       return back()->withError('The user with personnummer ' . $request->input('pnr').' does not exist. Please register the user before you can place an order.')->withInput();
                   }
                   return back()->withError("Något gick fel! Beställning är endast möjlig för kvinnor som har fått en inbjudan att beställa.");
                   //return view('home',['order_not_allowed' => "You cannot order."]);
               }
                
            
        } catch (PersonnummerException $e) {
            if($request->has('type') && $request->type === "admin"){
                return back()->withError('Personnummer Invalid ' . $request->input('pnr'))->withInput();
            }
            return back()->withError('Ogiltigt Personnummer ' . $request->input('pnr'))->withInput();
        }
        
        //$user = $this->userRepo->getUserbyPNR($request->get('pnr')); 
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return Order::find($id);
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
        return Order::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'user_id'=>'required',
            'status'=>'required'
        ]);
                
        $order = Order::findOrFail($id);
        $order->update($request->all());
        
        return $order;
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
            Order::find($id)->delete();
            return back()->with('order_deleted', "Order Deleted!");
        }
        catch(\Illuminate\Database\QueryException $e){
            return back()->with('order_not_deleted', "Order cannot be deleted! Kit already registered for the order. To delete
                                    the order, first delete the associated kit.");
        }
        
    }
    
    
    /**
     * Get all orders for this user. 
     *
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    
    public function getAllOrdersforUser($id){
        
        $myorders =  User::find($id)->orders;
        return view('my_orders', compact('myorders'));
        
    }
    
    public function myorders(){
        return OrderController::getAllOrdersforUser(session('user_id'));
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function orderKit(Request $request){
        $order = new Order([
            'user_id' => $request->user_id,
            'order_created_by' => is_null(Session::get('userattributes'))?null:Str::title(Session::get('userattributes')['givenName'])." ".Str::title(Session::get('userattributes')['surname'])
        ]);
        $order->save();
        User::find($order->user->id)->update(['consent' => 1]);
        
        $order_success_msg = "Din beställning har tagits emot och den kommer att skickas 
                            till din folkbokföringsadress om några dagar. 
                            Om du vill att den ska skickas till en annan adress eller se 
                            status kan du göra det genom att logga in på <a href=".url('/myprofile').">mina sidor</a>
                            eller kontakta oss på hpvcenter@ki.se.";
        return back()->with('order_created', $order_success_msg);
    }
    
    
    /**
     * Show the orderr import form.
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        //
        if ((Session::get('grandidsession')===null)){
            return view('admin.login');
        }
        
        return view('admin.import_orders');
    }
    
    
    /**
     * Import collections in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function importOrderSave(Request $request) {
        
        Validator::make($request->all(), [
            'orders_file' => 'required|mimes:xls,xlsx',
        ],
        [
            'required' => "Please provide the import file." ,
            'mimes' => "The import file must be an excel file (.xls/.xlsx). "
        ]
        )->validate();
            
            
            try {
                
                $import = new OrdersImport(new UserRepository);
                
                //In case trait Importable is used in Import Class.
                //$import->import($request->file('users_file'));
                
                //Otherwise use Facade.
                Excel::import($import, $request->file('orders_file'));
                
                if(empty($import->getErrors())){
                    return back()->with('orders_import_success', $import->getRowCount().' Orders have been imported successfully!');
                }
                
                return back()->with(['errors_msg' => $import->getErrors() ]);
            }catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                dd($e);
            }
    }
}
