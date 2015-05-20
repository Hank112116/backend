<?php namespace Backend\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Carbon\Carbon;

class AccessLogTarBall extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'access:tarball';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Tarball nginx access log';

	/**
	 * Create a new command instance.
	 *
	 * @return void
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
        \Log::info('access:tarball run!!');

        $date = Carbon::yesterday()->toDateString();
        $path = storage_path('logs');

        $log = "{$path}/nginx-access.log";
        $tarball   = "{$path}/nginx-access-{$date}.tar.bz2";

        if(!file_exists($log)) {
            $this->info("no such file: {$log}");
            return;
        }

        exec("tar -cjf {$tarball} -C {$path} nginx-access.log && rm -rf {$log}");
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
