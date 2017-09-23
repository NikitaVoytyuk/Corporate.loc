<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\PermissionsRepository;
use App\Repositories\RolesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Gate;
class PermissionsController extends AdminController
{
    protected $permissionsRep;
    protected $rolesRep;

    public function __construct(PermissionsRepository $permissionsRep, RolesRepository $rolesRep)
    {

        if (Gate::denies('EDIT_USERS')){
            abort(403);
        }
        parent::__construct();
        $this->permissionsRep = $permissionsRep;
        $this->rolesRep = $rolesRep;

        $this->template = env('THEME').'.admin.permissions';

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->title = 'Менеджер привелегий';

        $roles = $this->getRoles();
        $permissions = $this->getPermissions();
        $this->content = view(env('THEME').'.admin.permissionsContent')->with(['roles' => $roles, 'priv' => $permissions])->render();
        return $this->renderOutput();


    }

    public function getRoles()
    {
        $roles = $this->rolesRep->get();
        return $roles;
    }

    public function getPermissions()
    {
        $permissions = $this->permissionsRep->get();
        return $permissions;
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $res = $this->permissionsRep->changePermissions($request);

        if (is_array($res) && !empty($res['error'])){
            return back()->with($res);
        }
        return back()->with($res);
    }


}
