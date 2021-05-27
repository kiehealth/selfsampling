<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Personnummer\Personnummer;
use Personnummer\PersonnummerException;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Models\Order;
use App\Imports\UsersImport;
use function GuzzleHttp\Promise\all;

class UserController extends Controller
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
    
    
    public function getUserbyPNR($pnr){
        
        return $this->userRepo->getUserbyPNR($pnr);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //return User::all();
        //dd(User::all('first_name', 'last_name')->toJson());
        //return view('admin.users', ['users' => User::all()]);
        return view('admin.users');
    }
    
    /**
     * Get the collection of users as JSON.
     *
     * @return string
     */
    public function getUsers()
    {
        return User::withCount('orders')->get();
        //return User::all('first_name', 'last_name', 'pnr', 'phonenumber', 'roles', 
             //           'street', 'zipcode', 'city', 'country', '')->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if ((Session::get('grandidsession')===null)){
            return  view('admin.login');
        }
           
        return view('admin.create_user');
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
        
        try {
            //$pnr = (new Personnummer($request->get('pnr')))->format(true);
            
            $request->validate([
                'pnr' =>'required|size:12|unique:users,pnr',
            ]);
            
            //Personnummer::valid($request->pnr);
            $user = new User([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'pnr' => (new Personnummer($request->get('pnr')))->format(true),
                'phonenumber' => $request->get('phonenumber'),
                'street' => $request->get('street'),
                'zipcode' => $request->get('zipcode'),
                'city' => $request->get('city'),
                'country' => $request->get('country'),
                'consent' => $request->get('consent')
            ]);
            
            $roles = NULL;
            $roles_sep = FALSE;
            if($request->has("user_role")){
                $roles = $request->get('user_role');
                $roles_sep = TRUE;
            }
                
            if($request->has("admin_role")){
                $roles .= ($roles_sep===TRUE)?",".$request->get("admin_role"):"".$request->get("admin_role");
            }
            
            if(!is_null($roles))
                $user->roles = $roles;
            
            
            $user->save();
            
            return redirect('admin/users')->with("user_created", "The user is created!");
        } catch (PersonnummerException $e) {
            //dd($e);
            return back()->withError('PNR Invalid ' . $request->input('pnr'))->withInput();
            
        }
        //return redirect('/users')->with('success', 'User saved!');
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
        return User::find($id);
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
        
        $user = User::find($id);
        return view('admin.edit_user', compact('user'));
        
        
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
            'pnr'=>'required|size:12|unique:users,pnr,'.$id,
            'user_role'=>'required_without:admin_role',
            ],
            [
                'user_role.required_without' => "One of the USER_ROLE or ADMIN_ROLE should be selected.",
            ]);
        
        try{
            $user = User::find($id);
            $user->first_name = $request->get('first_name');
            $user->last_name = $request->get('last_name');
            $user->pnr = (new Personnummer($request->get('pnr')))->format(true);
            $user->phonenumber = $request->get('phonenumber');
            $user->street = $request->get('street');
            $user->zipcode = $request->get('zipcode');
            $user->city = $request->get('city');
            $user->country = $request->get('country');
            $user->consent = $request->get('consent');
            
            $roles = NULL;
            $roles_sep = FALSE;
            if($request->has("user_role")){
                $roles = $request->get('user_role');
                $roles_sep = TRUE;
            }
            
            if($request->has("admin_role")){
                $roles .= ($roles_sep===TRUE)?",".$request->get("admin_role"):"".$request->get("admin_role");
            }
            
            if(!is_null($roles))
                $user->roles = $roles;

            $user->save();
            
            return redirect('admin/users')->with("user_updated", "The user is updated!");
            
        }
        catch (PersonnummerException $e) {
            return back()->withError('PNR Invalid ' . $request->input('pnr'))->withInput();
        }
        
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
            User::find($id)->delete();
            return back()->with('user_deleted', "User Deleted!");
        }
        catch (\Illuminate\Database\QueryException $e){
            return back()->with('user_not_deleted', "User cannot be deleted! Order already registered for the user. To delete
                                    the user, first delete the associated order.");
            
        }
    }
    /**
     * Get user for this order.
     *
     *
     * @param  int $id
     * @return \App\Models\User
     * 
     */
    
    
    public function getUserforOrder(Order $order){
        
        //return Order::find($id)->user;
        return $order->user()->firstOrFail();
    }
    
    /**
     * Show the user import form.
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        //
        if ((Session::get('grandidsession')===null)){
            return view('admin.login');
        }
        
        return view('admin.import_users');
    }
    
    
    /**
     * Import collections in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function importUserSave(Request $request) {
        
        Validator::make($request->all(), [
                'users_file' => 'required|mimes:xls,xlsx',
            ],
            [
                'required' => "Please provide the import file." ,
                'mimes' => "The import file must be an excel file (.xls/.xlsx). "
            ]
        )->validate();
        
        
        try {
            
            $import = new UsersImport(new UserRepository);
            
            //In case trait Importable is used in Import Class.
            //$import->import($request->file('users_file'));
            
            //Otherwise use Facade.
            Excel::import($import, $request->file('users_file'));
            
            if(empty($import->getErrors())){
                return back()->with('users_import_success', $import->getRowCount().' Users have been imported successfully!');
            }
            
            return back()->with(['errors_msg' => $import->getErrors() ]);
        }catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            dd($e);
        }
    }
    
    
    
    /**
     * Show the user profile page.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile() {
        //dd(session()->all());
        if (UserController::userNotLoggedin()){
            //return redirect()->to('/');
            return view('user_login');
        }
        return redirect()->action([UserController::class, 'myprofile']);
    }
    
    
    
    /**
     * Show the user profile page.
     *
     * @return \Illuminate\Http\Response
     */
    public function myprofile(){
        if (UserController::userNotLoggedin()){
                //return redirect()->to('/');
                return view('user_login');
        }
        $user = User::find(session('user_id'));
        $latest_order = $user->orders()->latest()->first();
        $latest_result = $user->samples->whereNotNull('final_reporting_result')->sortByDesc('id')->first();
        
        return view('profile', compact('user', 'latest_order', 'latest_result'));
    }
    
    
    /**
     * Check if the user is not logged in.
     *
     * @return boolean
     */
    private function userNotLoggedin(){
        
        if (session()->get('grandidsession')===null || !session()->has('user_id')
            || (session()->get('role')!==config('constants.roles.USER_ROLE'))){
            return true;
        }
        return false;
    }
    
    
    
    /**
     * Update the user profile in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateprofile(Request $request, $id)
    {
        $user = User::find($id);
        $user->phonenumber = $request->get('phonenumber');
        $user->street = $request->get('street');
        $user->zipcode = $request->get('zipcode');
        $user->city = $request->get('city');
        $user->country = $request->get('country');
        
        $user->save();
        
        return redirect('myprofile')->with("user_profile_updated", "Adress Uppdaterad!");
    }
    
    
    /**
     * Unsubscribe the user from the study.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe(Request $request, $type=null) {
        $unsubscribed_msg = "Din deltagande i studien har avslutats och kommer vi inte kontakta dig längre. Däremot om du ångrar dig, behöver du bara
		samtycker igen på <a href=".url('/').">hemsidan</a> och besätlla självprovtagningskit.";
        
        if($type ==='pnr'){
            $request->validate([
                'pnr'=>'required|size:12'
            ],
            [
                'pnr.size' => "Vänligen änge 12 siffrigt personnummer utan bindestreck."
            ]);
            
            try {
                Personnummer::valid($request->pnr);
                try {
                    $user = $this->userRepo->getUserbyPNR((new Personnummer($request->pnr))->format(true));
                    if ($user->exists) {
                        $user->update(['consent' => 0]);
                        return back()->with('unsubscribed', $unsubscribed_msg);
                    }
                } catch (ModelNotFoundException $e) {
                    return back()->withError("Något gick fel!");
                }
            } catch (PersonnummerException $e) {
                return back()->withError('Ogiltigt Personnummer ' . $request->input('pnr'))->withInput();
            }
            
        }
        
        User::find($request->user_id)->update(['consent' => 0]);
        return back()->with('unsubscribed', $unsubscribed_msg);
    }
}
