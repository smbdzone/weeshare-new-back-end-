<?php

namespace App\Http\Controllers\API;

use DB;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;

// use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Validator;

class RolesController  extends BaseController 
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        // $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        // $this->middleware('permission:role-create', ['only' => ['create','store']]);
        // $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $roles = Role::orderBy('id','DESC')
        ->with('role_has_permissions')
        ->get();

        // $roles = Role::join("role_has_permissions", 
        // "role_has_permissions.role_id","=","roles.id")
        // // ->where("role_has_permissions.role_id",$id)
        // ->get();

        return $this->sendResponse($roles, 'All Roles'); 

        // return view('roles.index',compact('roles'))
        //     ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::get();
        return $this->sendResponse($permissions, 'All permissions to create role'); 

        // return view('roles.create', compact('permissions'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $input = $request->all();

        // return $this->sendResponse($input, 'Role created successfully'); 

        $validator = Validator::make($input, [ 
            'name' => 'required|unique:roles,name'  
        ]);
 
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        } else {
            $role = Role::create(['name' => $request->get('name')]);
            $role->syncPermissions($request->get('permission')); 
        }  
    
        return $this->sendResponse($role, 'Role created successfully'); 
 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join(  "role_has_permissions", 
            "role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();
        $data = [
            'role' => $role,
            'rolePermissions' => $rolePermissions
        ];

        return $this->sendResponse($data, 'Role and Role Permissions'); 
    }


     
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 

        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")
        ->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
    
        $data = [
            'role' => $role,
            'permission' => $permission,
            'rolePermissions' => $rolePermissions
        ];
        
        return $this->sendResponse($data, 'role, permission and role Permissions'); 
 
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */  

    public function update( $id, Request $request)
    {
        
        $input = $request->all(); 

        $validator = Validator::make($input, [ 
            'name' => 'required|unique:roles,name,'.$id,
            'permission' => 'required',
        ]); 

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        } else { 

            $role = Role::find($id);
            $role->name = $request->input('name');
            $role->save();
        
            $role->syncPermissions($request->input('permission'));
        
            return $this->sendResponse($role, 'Role updated successfully'); 

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
        // $role->delete();

        DB::table("roles")->where('id',$id)->delete();
        DB::table("role_has_permissions")->where('role_id',$id)->delete();

        return $this->sendResponse($id, 'Role deleted successfully'); 

        // return redirect()->route('roles.index')
        //                 ->with('success','Role deleted successfully');
    }


  

}