<?php

namespace Backend\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use File;
use Storage;

class CalculateAssetsChecksum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-assets-checksum';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate checksum of js and css file';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('Start calculate checksum assets files');

        $mapping_array = [];

        $js_files    = Collection::make(File::files(config('assets.js_path')));
        $react_files = Collection::make(File::files(config('assets.react_path')));
        $css_files   = Collection::make(File::files(config('assets.css_path')));


        foreach ($js_files as $js_file) {
            $file_name = pathinfo($js_file, PATHINFO_BASENAME);

            $mapping_array['js/' . $file_name] = md5_file($js_file);
        }

        foreach ($react_files as $react_file) {
            $file_name = pathinfo($react_file, PATHINFO_BASENAME);

            $mapping_array['react/' . $file_name] = md5_file($react_file);
        }

        foreach ($css_files as $css_file) {
            $file_name = pathinfo($css_file, PATHINFO_BASENAME);

            $mapping_array['css/' . $file_name] = md5_file($css_file);
        }

        Storage::put('AssetsMapping.php', '<?php return ' . var_export($mapping_array, true) . ";\n");
    }
}
