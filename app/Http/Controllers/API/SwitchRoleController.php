<?php
namespace App\Http\Controllers\API;
use Spatie\Permission\Models\Role;
 
use App\Http\Controllers\API\BaseController as BaseController; 
 
class SwitchRoleController extends BaseController
{
    public function __invoke(Role $role)
    {
        abort_unless(auth()->user()->hasRole($role), 404);
 
        auth()->user()->update(['current_role_id' => $role->id]);
        
        return $this->sendResponse($role->id, 'current_role_id');  

        // return  $role->id;
        // return to_route('dashboard'); // Replace this with your own home route
    }
}