<?php namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\Adminer;
use Validator;
use Hash;
use Backend\Repo\RepoInterfaces\AdminerInterface;
use Backend\Repo\RepoInterfaces\RoleInterface;

class AdminerRepo implements AdminerInterface
{
    private $error;
    private $rules = [
        'name'     => 'required',
        'password' => 'required|min:4|confirmed',
        'email'    => 'required|email|unique:adminers',
    ];

    public function __construct(Adminer $adminer, RoleInterface $role)
    {
        $this->adminer = $adminer;
        $this->role    = $role;
    }

    public function all()
    {
        return $this->adminer->with(['role', 'user'])->get();
    }

    public function find($id)
    {
        return $this->adminer->with(['role', 'user'])->find($id);
    }

    public function validCreate($input)
    {
        $validator = Validator::make($input, $this->rules);

        if ($validator->passes()) {
            return true;
        }

        $this->error = $validator;

        return false;
    }

    public function error()
    {
        return $this->error;
    }

    public function create($input)
    {
        $adminer           = new Adminer();
        $adminer->name     = $input[ 'name' ];
        $adminer->email    = $input[ 'email' ];
        $adminer->password = Hash::make($input[ 'password' ]);

        $role = $this->role->find($input[ 'role_id' ]);
        $adminer->role()->associate($role);

        return $adminer->save();
    }

    public function validUpdate($id, $input)
    {
        $adminer = $this->adminer->find($id);

        if ($input[ 'email' ] == $adminer->email) {
            unset($this->rules[ 'email' ]);
        }

        if (!array_get($input, 'password')) {
            unset($this->rules[ 'password' ]);
        }

        $validator = Validator::make($input, $this->rules);

        if ($validator->passes()) {
            return true;
        }

        $this->error = $validator;

        return false;
    }

    public function update($id, $input)
    {
        $adminer        = $this->adminer->find($id);
        $adminer->name  = $input[ 'name' ];
        $adminer->email = $input[ 'email' ];

        $role = $this->role->find($input[ 'role_id' ]);
        $adminer->role()->associate($role);

        if (array_get($input, 'user_id')) {
            $adminer->user_id = $input[ 'user_id' ];
        }

        if (array_get($input, 'password')) {
            $adminer->password = Hash::make($input[ 'password' ]);
        }

        return $adminer->save();
    }

    public function deleteAdminer($adminer)
    {
        $adminer->delete();
    }

    /*
     * @param Paginator|Collection
     * return array
     */
    public function toOutputArray($adminers)
    {
        $output = [];
        foreach ($adminers as $adminer) {
            $output[ ] = $this->adminerOutput($adminer);
        }

        return $output;
    }

    private function adminerOutput(Adminer $adminer)
    {
        return [
            '#'     => $adminer->id,
            'Name'  => $adminer->name,
            'Email' => $adminer->email,
            'Role'  => $adminer->role->name
        ];
    }
}
