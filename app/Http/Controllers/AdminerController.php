<?php

namespace Backend\Http\Controllers;

use Backend\Repo\RepoInterfaces\AdminerInterface;
use Backend\Repo\RepoInterfaces\RoleInterface;
use Backend\Facades\Log;
use Noty;

class AdminerController extends BaseController
{
    protected $cert = 'adminer';

    private $adminer_repo;
    private $role_repo;
    private $request;

    public function __construct(
        AdminerInterface $adminer,
        RoleInterface $role
    ) {
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
        if ($this->request->get('csv')) {
            $output = $this->adminer_repo->toOutputArray($this->adminer_repo->all());

            $log_action = 'CSV of backend team';
            Log::info($log_action);

            return $this->outputArrayToCsv($output, 'adminers');
        }

        return view('adminer.list')
            ->with('adminers', $this->adminer_repo->all())
            ->with('deleted_adminers', $this->adminer_repo->allDeleted())
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
        $data = $this->request->all();

        if ($this->adminer_repo->validCreate($data)) {
            Noty::success('Create successful');
            $adminer = $this->adminer_repo->create($data);

            $log_action = 'New member of backend team';
            $log_data   = [
                'backend_member' => $adminer->id,
                'name'           => $data['name'],
                'email'          => $data['email'],
                'role'           => $data['role_id'],
                'hwtrek_member'  => $data['user_id']
            ];
            Log::info($log_action, $log_data);

            return redirect()->action('AdminerController@showList');
        } else {
            Noty::warn('Something goes wrong');

            return redirect()->action('AdminerController@showCreate')
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
            ->with('certs', config('cert.all'));
    }

    /**
     * show update role page
     * Route::get adminer/role/update/{role_id}
     **/
    public function showRoleUpdate($id)
    {
        return view('adminer.role-update')
            ->with('role', $this->role_repo->find($id))
            ->with('certs', config('cert.all'));
    }

    /**
     * update adminer
     * @route: post adminer/update/{id}
     **/
    public function update($id)
    {
        if ($this->adminer_repo->validUpdate($id, $this->request->all())) {
            $this->adminer_repo->update($id, $this->request->all());

            $data = $this->request->all();
            $log_action = 'Edit member of backend team';
            $log_data   = [
                'backend_member' => $id,
                'name'           => $data['name'],
                'email'          => $data['email'],
                'role'           => $data['role_id']
            ];
            Log::info($log_action, $log_data);

            Noty::success('Update successful');

            return redirect()->action('AdminerController@showList');
        } else {
            Noty::warn('Something goes wrong');

            return redirect()->action('AdminerController@showUpdate', [$id])
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
        $data = $this->request->all();

        $role = $this->role_repo->create($data);

        $log_action = 'New Role of backend team';
        $log_data   = [
            'role'      => $role->id,
            'name'      => $data['name'],
            'privilege' => $data['cert']
        ];

        Log::info($log_action, $log_data);

        Noty::success('Create successful');

        return redirect()->action('AdminerController@showList');
    }

    /**
     * update role
     * @route: post adminer/role/update/{id}
     **/
    public function roleUpdate($id)
    {
        $data = $this->request->all();
        $this->role_repo->update($id, $data);
        Noty::success('Update successful');

        $log_action = 'Edit Role of backend team';
        $log_data   = [
            'role'      => $id,
            'name'      => $data['name'],
            'privilege' => $data['cert']
        ];

        Log::info($log_action, $log_data);

        return redirect()->action('AdminerController@showList');
    }

    public function delete($id)
    {
        $adminer = $this->adminer_repo->find($id);
        $name    = $adminer->name;
        Noty::success("Delete [{$name}] successful");

        $log_action = 'Delete member of backend team';
        $log_data   = [
            'backend_member' => $id
        ];
        Log::info($log_action, $log_data);

        $this->adminer_repo->deleteAdminer($adminer);

        return redirect()->action('AdminerController@showList');
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

        return redirect()->action('AdminerController@showList');
    }
}
