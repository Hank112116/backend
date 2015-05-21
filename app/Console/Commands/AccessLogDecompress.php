<?php namespace Backend\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AccessLogDecompress extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'access-log:decompress';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

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
        $path = storage_path('logs');
        $file = $this->argument('file');

        if(!file_exists("{$path}/{$file}")) {
            $this->info("no such file: {$file}");
            return;
        }

        $handle = fopen("{$path}/{$file}", 'rb');
        stream_filter_append($handle, 'bzip2.decompress');

        $outputs = [];
        while(true !== feof($handle)) {
            $outputs[] = fgets($handle);
        }

        fclose($handle);
        dd($outputs);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['file', InputArgument::REQUIRED, 'decompressing file'],
		];
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
