<?php
/**
 * Real-time get query log
 *
 * @refer www.laravel-tricks.com/tricks/real-time-log-of-eloquent-sql-queries
 * @usage php artisan tail --path="app/storage/logs/query.log
 **/

class QueryLog
{
    private static $instance;
    private $path;

    private function __construct()
    {
        $this->path = storage_path().'/logs/query.log';
    }

    public static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new QueryLog;
        }
            
        return self::$instance;
    }

    public function appendRoute($request)
    {
        $start = PHP_EOL.'=| '.$request->method().' '.$request->path().' |='.PHP_EOL;
        File::append($this->path, $start);
    }

    public function appendQuery($sql, $bindings, $time)
    {
        // Uncomment this if you want to include bindings to queries
        //$sql = str_replace(array('%', '?'), array('%%', '%s'), $sql);
        //$sql = vsprintf($sql, $bindings);
        $time_now = Carbon::now('Asia/Taipei')->format('m/d h:i');
        $sql = str_replace(PHP_EOL, '', $sql);
        $sql = str_replace("/[\s\t]+/", ' ', $sql);
        File::append($this->path, "{$time_now} | {$time}ms | $sql".PHP_EOL);
    }

    public function getLogs()
    {
        $blocks = explode(LogRecord::$tpair[0], File::get($this->path));

        $logs = [];
        foreach ($blocks as $block) {
            $record = new LogRecord($block);
            if (!$record->method or count($record->queries) == 0) {
                continue;
            }

            $logs[] = $record;
        }
        
        return array_reverse($logs);
    }

    public function deleteLog()
    {
        File::delete($this->path);
    }
}

class LogRecord
{
    public $method;
    public $route;
    public $queries = [];
    public $total_cost = 0.0;
    public static $tpair = ["=| ", " |="];
     
    /**
     * $block format :
     * 		GET engineer |=
     * 		05/02 12:35 | 1.97ms | select * from `adminers` where `id` = ? limit 1
     * 		05/02 12:35 | 1.61ms | select * from `admin_roles` where `admin_roles`.`id` = ? limit 1
     **/
    public function __construct($block)
    {
        foreach (explode(PHP_EOL, $block) as $line) {
            if (!trim($line) or $line == PHP_EOL) {
                continue;
            }

            if (ends_with($line, self::$tpair[1])) {
                $this->setMethodRoute($line);
            } else {
                $query = new Query($line);
                $this->total_cost += $query->cost;
                if (!$query->is_admin_query) {
                    $this->queries[] = $query;
                }
            }
        }
    }

    private function setMethodRoute($line)
    {
        preg_match('/(GET|POST)/', $line, $match_method);
        $this->method = $match_method[0];

        preg_match('/\s([\w-_\/]+)\s\|=/', $line, $match_route);
        if (!$match_route) {
            return;
        }

        $this->route = str_replace(self::$tpair[1], '', $match_route[0]);
    }
}

class Query
{
    public $datetime;
    public $cost;
    public $statement;

    private static $delimiter = " | ";

    // $line format : "05/02 12:26 | 1.61ms | select * from `adminers` where `id` = ? limit 1"
    public function __construct($line)
    {
        list($this->datetime, $this->cost, $this->statement) = explode(self::$delimiter, $line);

        $this->is_admin_query = str_contains($this->statement, "admin");
    }
}
