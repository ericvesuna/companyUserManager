<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use App\Models\CompanyUser;
use DB;

class CompanyController extends Controller
{
    protected $company;
    protected $user;
    protected $companyUser;

    public function __construct(Company $company, User $user, CompanyUser $companyUser){
        $this->company = $company;
        $this->user = $user;
        $this->companyUser = $companyUser;
    }

    /**
     * companies listing
     */
    public function index(Request $request){
        return view('companies.index');
    }

    /**
     * User records in json format for datatable
     */

    public function companiesRecords(Request $request){
        $columns = array( 
            0 =>'id', 
            1 =>'name',
            2 => 'email'
        );

        $totalData = $this->company->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        if(empty($request->input('search.value'))){   
            $companies =  $this->company->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        }else{
            $records = $this->company->where('name','LIKE','%'.$search.'%');
            $totalFiltered = $records->count();
            $companies =  $records->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        }

        $index = 1; 
        $data = [];
        foreach($companies as $comapny){
            $nestedData['index'] = $index++;
            $nestedData['name'] = $comapny->name;
            $nestedData['options'] =  '<a href ="'.route('companiesAssignUsers', encrypt($comapny->id)).'" class="btn btn-primary">Add User</a> <a href ="'.route('companiesEdit', encrypt($comapny->id)).'" class="btn btn-warning">Edit</a> <a href ="javascript:void(0);" data-id="'.encrypt($comapny->id).'" class="btn btn-danger deleteCompany">Delete</a>';
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
        return view('companies.create');
    }

    /**
     * Storing company records
     */
    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required',
        ],
        [   
            'name.required' => 'Please Enter Name',
        ]);
        
        $company = $this->company;
        $status = $this->recordCreationAndUpdation($company, $request->all());

        if($status == true){
            return redirect()->route('companiesIndex')->with('success','Company Saved Successfully');
        }
        return redirect()->back()->with('error','Failed To Save Company');
    }

    /**
     * Get edit company records
     */
    public function edit($id){
        $company = $this->company->find(decrypt($id));
        if($company){
            return view('companies.edit', compact('company'));
        }
        abort(404);
    }

    /**
     * Updating company records
     */
    public function update($id, Request $request){
        $id = decrypt($id);
        $this->validate($request,[
            'name' => 'required',
        ],
        [   
            'name.required' => 'Please Enter Name',
        ]);
        
        $status = false;
        $comapny = $this->company->find($id);
        if($comapny){
            $status = $this->recordCreationAndUpdation($comapny, $request->all());
        }

        if($status == true){
            return redirect()->route('companiesIndex')->with('success','Company Updated Successfully');
        }
        return redirect()->back()->with('error','Failed To Update Company');
    }

    /**
     * Common function for inserting and updating the records
     */
    public function recordCreationAndUpdation($company, $data){
        $status = false;
        try{
            DB::beginTransaction();

            $company->name = $data['name'];
            $company->save();
            
            DB::commit();
            $status = true;
        }catch(\Exception $e){
            DB::rollback();
        }
        return $status;
    }

    /**
     * Delete company
     */
    public function delete(Request $request){
        $status = false;
        try{
            $company = $this->company->find(decrypt($request->id));
            if($company){
                DB::beginTransaction();   
                $company->delete();
                
                DB::commit();
                $status = true;
            }
        }catch(\Exception $e){
            DB::rollback();
        }

        if($status == true){
            return response()->json(['status' => 'true', 'message' => 'Company Deleted Successfully']);
        }
        return response()->json(['status' => 'false', 'message' => 'Failed To Delete Company']);
    }

    public function assignUsers($id){
        $users = $this->user->select('name','id')->get();
        $company = $this->company->select('name','id')->find(decrypt($id));
        $companyUsers = $this->companyUser->where('company_id',decrypt($id))->pluck('user_id')->toArray();

        if(count($users) > 0){
            return view('companies.assignUsers', compact('users','company','companyUsers'));
        }
        return redirect()->back()->with('error','No Users Found');
    }

    public function storeAssignUsers($id, Request $request){
        if(isset($request->users) == false){
            return redirect()->back()->with('error','Please Select Atleast One User');
        }
        try{
            $status = false;   
            DB::beginTransaction();
            $this->companyUser->where('company_id', decrypt($id))->delete();
            foreach($request->users as $user){
                $companyUser = new $this->companyUser;
                $companyUser->company_id = decrypt($id);
                $companyUser->user_id = $user;
                $companyUser->save();
                
            }
            DB::commit();
            $status = true;   
        }catch(\Exception $e){
            DB::rollback();
        }
        if($status == true){
            return redirect()->route('companiesIndex')->with('success','User Assigned Successfully');
        }
        return redirect()->route('companiesIndex')->with('error','Failed To Assign User');
    }
}
