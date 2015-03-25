<?php

namespace Backend\Http\Controllers;

use Backend\Repo\RepoInterfaces\AdminerInterface;
use Backend\Repo\RepoInterfaces\RoleInterface;

use Config;
use CSV;
use Input;
use Noty;
use Redirect;

class AdminerController extends BaseController
{

    protected $cert = 'adminer';

    public function __construct(
        AdminerInterface $adminer,
        RoleInterface $role
    )
    {
        parent::__construct();

        $this->adminer_repo = $adminer;
        $this->role_repo    = $role;
    }

    /**
     * show all adminers page
     * Route::get adminers
     **/
    public function showList()
    {
        if (Input::get('csv')) {
            $output = $this->adminer_repo->toOutputArray($this->adminer_repo->all());

            return CSV::fromArray($output)->render('adminers.csv');
        }

        return view('adminer.list')
            ->with('adminers', $this->adminer_repo->all())
            ->with('roles', $this->role_repo->all());
    }

    public function showCreate()
    {
        return view('adminer.create')
            ->with('roles_options', $this->role_repo->allNames());
    }

    /**
     * create adminer
     * @route: post adminer/create
     **/
    public function create()
    {
        $data = Input::all();

        if ($this->adminer_repo->validCreate($data)) {
            Noty::success('Create successful');
            $this->adminer_repo->create($data);

            return Redirect::action('AdminerController@showList');
        } else {
            Noty::warn('Something goes wrong');

            return Redirect::action('AdminerController@showCreate')
                ->withInput()
                ->withErrors($this->adminer_repo->error());
        }
    }

    /**
     * show update adminer page
     * Route::get adminer/update/{adminer_id}
     **/
    public function showUpdate($id)
    {
        return view('adminer.update')
            ->with('adminer', $this->adminer_repo->find($id))
            ->with('role_options', $this->role_repo->allNames());
    }

    /**
     * create role
     * @route: post adminer/role/create
     **/
    public function showRoleCreate()
    {
        return view('adminer.role-create')
            ->with('certs', Config::get('cert.all'));
    }

    /**
     * show update role page
     * Route::get adminer/role/update/{role_id}
     **/
    public function showRoleUpdate($id)
    {
        return view('adminer.role-update')
            ->with('role', $this->role_repo->find($id))
            ->with('certs', Config::get('cert.all'));
    }

    /**
     * update adminer
     * @route: post adminer/update/{id}
     **/
    public function update($id)
    {
        if ($this->adminer_repo->validUpdate($id, Input::all())) {
            $this->adminer_repo->update($id, Input::all());

            Noty::success('Update successful');

            return Redirect::action('AdminerController@showList');
        } else {
            Noty::warn('Something goes wrong');

            return Redirect::action('AdminerController@showUpdate', [$id])
                ->withInput()
                ->withErrors($this->adminer_repo->error());
        }
    }

    /**
     * update role
     * @route: post adminer/role/update/{id}
     **/
    public function roleCreate()
    {
        $this->role_repo->create(Input::all());
        Noty::success('Create successful');

        return Redirect::action('AdminerController@showList');
    }

    /**
     * update role
     * @route: post adminer/role/update/{id}
     **/
    public function roleUpdate($id)
    {
        $this->role_repo->update($id, Input::all());
        Noty::success('Update successful');

        return Redirect::action('AdminerController@showList');
    }

    public function delete($id)
    {
        $adminer = $this->adminer_repo->find($id);
        $name    = $adminer->name;
        Noty::success("Delete [{$name}] successful");

        $this->adminer_repo->deleteAdminer($adminer);

        return Redirect::action('AdminerController@showList');
    }

    public function roleDelete($id)
    {
        $role = $this->role_repo->find($id);
        $name = $role->name;

        if ($this->role_repo->isRoleHasAdminer($role)) {
            Noty::warn("Change backend member whose role is [{$name}]");
        } else {
            $this->role_repo->deleteRole($role);
            Noty::success("Delete {$name} successful");
        }

        return Redirect::action('AdminerController@showList');
    }
}
