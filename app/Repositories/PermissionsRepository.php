<?php
namespace App\Repositories;

use App\Permission;

use Gate;

class PermissionsRepository extends Repository {
    protected $rolesRer;

    public function __construct(Permission $permission, RolesRepository $rolesRep)
    {
        $this->model = $permission;
        $this->rolesRer = $rolesRep;
    }

    public function changePermissions($request){
        if (Gate::denies('change', $this->model)){
            abort(403);
        }

        $data = $request->except('_token');
        $roles = $this->rolesRer->get();
        foreach ($roles as $value) {
            if (isset($data[$value->id])){
                $value->savePermissions($data[$value->id]);
            }
            else{
                $value->savePermissions([]);
            }
        }
        return ['status' => 'Права обновлены'];
    }

}