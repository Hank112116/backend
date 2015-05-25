<?php namespace Backend\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Carbon\Carbon;

class AccessLogCompress extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'access-log:compress';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Compress nginx access log to bzip';

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
        \Log::info('access-log:compress run!!');

        $date = Carbon::yesterday()->toDateString();
        $path = storage_path('logs');

        $log = "{$path}/nginx-access.log";
        $to  = "{$path}/nginx-access-{$date}.log";

        if(!file_exists($log)) {
            $this->info("no such file: {$log}");
            return;
        }

        exec("bzip2 {$log} && mv {$log}.bz2 {$to}.bz2");
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
