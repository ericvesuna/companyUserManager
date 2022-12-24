<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;

class UserController extends Controller
{
    protected $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    /**
     * User listing
     */
    public function index(Request $request){
        return view('users.index');
    }

    /**
     * User records in json format for datatable
     */

    public function usersRecords(Request $request){
        $columns = array( 
            0 =>'id', 
            1 =>'name',
            2 => 'email'
        );

        $totalData = $this->user->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        if(empty($request->input('search.value'))){   
            $users =  $this->user->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        }else{
            $records = $this->user->where('name','LIKE','%'.$search.'%');
            $totalFiltered = $records->count();
            $users =  $records->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        }

        $index = 1; 
        $data = [];
        foreach($users as $user){
            $nestedData['index'] = $index++;
            $nestedData['name'] = $user->name;
            $nestedData['email'] = $user->email;
            $nestedData['options'] =  '<a href ="'.route('usersEdit', encrypt($user->id)).'" class="btn btn-warning">Edit</a> <a href ="javascript:void(0);" data-id="'.encrypt($user->id).'" class="btn btn-danger deleteUser">Delete</a>';
            $data[] = $nestedData;
        }

        $jsonData = array(
            "draw" => intval($request->input('draw')),  
            "recordsTotal" => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data" => $data   
            );

        return json_encode($jsonData);
    }

    public function create(){
        return view('users.create');
    }

    /**
     * Storing user records
     */
    public function store(Request $request){
        $this->validate($request,[
            'name' => 'bail|required|regex:/^[a-zA-Z ]*$/',
            'email' => 'bail|required|email|unique:users,email',
        ],
        [   
            'name.required' => 'Please Enter Name',
            'name.regex' => 'Please Enter Proper Name',
            'email.required' => 'Please Enter Email',
            'email.unique' => 'User Already Registered With This Email',
        ]);
        
        $user = $this->user;
        $status = $this->recordCreationAndUpdation($user, $request->all());

        if($status == true){
            return redirect()->route('usersIndex')->with('success','User Saved Successfully');
        }
        return redirect()->back()->with('error','Failed To Save User');
    }

    /**
     * Get edit user records
     */
    public function edit($id){
        $user = $this->user->find(decrypt($id));
        if($user){
            return view('users.edit', compact('user'));
        }
        abort(404);
    }

    /**
     * Updating user records
     */
    public function update($id, Request $request){
        $id = decrypt($id);
        $this->validate($request,[
            'name' => 'bail|required|regex:/^[a-zA-Z ]*$/',
            'email' => 'bail|required|email|unique:users,email,'.$id,
        ],
        [   
            'name.required' => 'Please Enter Name',
            'name.regex' => 'Please Enter Proper Name',
            'email.required' => 'Please Enter Email',
            'email.unique' => 'User Already Registered With This Email',
        ]);
        
        $status = false;
        $user = $this->user->find($id);
        if($user){
            $status = $this->recordCreationAndUpdation($user, $request->all());
        }

        if($status == true){
            return redirect()->route('usersIndex')->with('success','User Updated Successfully');
        }
        return redirect()->back()->with('error','Failed To Update User');
    }

    /**
     * Common function for inserting and updating the records
     */
    public function recordCreationAndUpdation($user, $data){
        $status = false;
        try{
            DB::beginTransaction();

            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->save();
            
            DB::commit();
            $status = true;
        }catch(\Exception $e){
            DB::rollback();
        }
        return $status;
    }

    /**
     * Delete user
     */
    public function delete(Request $request){
        $status = false;
        try{
            $user = $this->user->find(decrypt($request->id));
            if($user){
                DB::beginTransaction();   
                $user->delete();
                
                DB::commit();
                $status = true;
            }
        }catch(\Exception $e){
            DB::rollback();
        }

        if($status == true){
            return response()->json(['status' => 'true', 'message' => 'User Deleted Successfully']);
        }
        return response()->json(['status' => 'false', 'message' => 'Failed To Delete User']);
    }
}
