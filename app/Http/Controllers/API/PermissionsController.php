<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Spatie\Permission\Models\Permission;

use Validator;

class PermissionsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $permissions = Permission::all();

        return $this->sendResponse($permissions, 'All permissions'); 

        // return view('permissions.index', [
        //     'permissions' => $permissions
        // ]);
    }

    /**
     * Show form for creating permissions
     * 
     * @return \Illuminate\Http\Response
     */

    public function create() 
    {   
        return $this->sendResponse('', 'Create Permission Page'); 
        
        // return view('permissions.create');
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

        $validator = Validator::make($input, [ 
            'name' => 'required|unique:permissions,name'  
        ]);
 
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        } else {
            $permission = Permission::create($request->only('name')); 
            return $this->sendResponse($permission, 'Permission created successfully.');
        }
        
         
        
        // return redirect()->route('permissions.index')
        //     ->withSuccess(__('Permission created successfully.'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Permission  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


        $permission = Permission::find($id);
        // $permission = $permission->update(['name' => $request->name]); 
        return $this->sendResponse($permission, 'Permission Details'); 

        // return view('permissions.edit', [
        //     'permission' => $permission
        // ]);
    }


    public function show($id)
    {


        $permission = Permission::find($id);
        // $permission = $permission->update(['name' => $request->name]); 
        return $this->sendResponse($permission, 'Permission Details'); 

        // return view('permissions.edit', [
        //     'permission' => $permission
        // ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        $input = $request->all();

        $validator = Validator::make($input, [ 
            'name' => 'required|unique:permissions,name,'.$id
        ]);
 
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        } else {
            $permission = Permission::find($id);
            $permission = $permission->update(['name' => $request->name]); 
            return $this->sendResponse($permission, 'Permission updated successfully.');
        }



        // $request->validate([
        //     'name' => 'required|unique:permissions,name,'.$id
        // ]);

        // $permission->update($request->only('name'));

        // return $this->sendResponse($permission, 'Permission updated successfully.'); 

        // return redirect()->route('permissions.index')
        //     ->withSuccess(__('Permission updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);
        $permission->delete();

        return $this->sendResponse($id, 'Permission deleted successfully.'); 

        // return redirect()->route('permissions.index')
        //     ->withSuccess(__('Permission deleted successfully.'));
    }
}