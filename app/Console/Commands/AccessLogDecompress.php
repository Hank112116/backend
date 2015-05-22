<?php namespace Backend\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Carbon\Carbon;

class AccessLogDecompress extends Command
{

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
    protected $description = 'Fetch log bz2 from remote server and parse into logstash';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $compressed = $this->fetchRemoteCompressedLog();
        if (file_exists($compressed)) {
            return $this->decompressToLogstash($compressed);
        }

        $this->info("No such file: {$compressed}");
    }

    private function fetchRemoteCompressedLog()
    {
        $path = storage_path('logs');
        $date = Carbon::yesterday()->toDateString();
        $file = "nginx-access-{$date}.log.bz2";;

        $pem  = "~/.ssh/hwtrek-vpc-oregon-admin-panel.pem";
        $from = "centos@backend.hwtrek.com:/var/hwtrek/backend/storage/logs/{$file}";

        exec("scp -i {$pem} {$from} {$path}");
        return "{$path}/{$file}";
    }

    private function decompressToLogstash($compressed)
    {
        $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

        foreach ($this->yieldDecompressLogs($compressed) as $log) {
            $this->info($log);
            socket_sendto($sock, $log, strlen($log), 0, '127.0.0.1', 5544);
        }

        socket_close($sock);
    }

    private function yieldDecompressLogs($compressed)
    {
        $handle = fopen($compressed, 'rb');
        stream_filter_append($handle, 'bzip2.decompress');

        while (true !== feof($handle)) {
            $line = fgets($handle);
            preg_match('~\{(?:[^{}]|(?R))*\}~', $line, $matches); // parse json

            if (count($matches) > 0) {
                yield $matches[0];
            }
        }

        fclose($handle);
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
