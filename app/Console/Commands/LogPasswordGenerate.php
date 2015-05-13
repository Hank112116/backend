<?php namespace Backend\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Auth\Guard as Auth;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class LogPasswordGenerate extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'logpass:gen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate log server password for login user';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $user  = auth()->user();
        $email = $user->email;

        $password = bcrypt(time());
        session()->put('logpass', $password);

        $path       = storage_path('logpass.users');
        $parameters = file_exists($path) ? "-b" : "-cb";

        exec("htpasswd {$parameters} {$path} {$email} {$password}");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

}
