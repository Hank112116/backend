<?php

namespace Backend\Console\Commands;

use Illuminate\Console\Command;
use League\Flysystem\Exception;
use Symfony\Component\Finder\SplFileInfo;
use File;

class ClearTmpFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:clear-tmp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all storage tmp file';

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
    public function handle()
    {
        $files = File::allFiles(config('app.tmp_folder'));

        if (empty($files)) {
            $this->info('No tmp file');
            return;
        }

        $this->info('Deleting all tmp files');

        /* @var SplFileInfo $file */
        foreach ($files as $file) {
            try {
                $this->info('Delete ' . $file->getRealPath());
                File::delete($file);

            } catch (Exception $e) {
                $msg = 'Delete error' . $file->getRealPath();
                $this->error($msg);
            }
        }
    }
}
