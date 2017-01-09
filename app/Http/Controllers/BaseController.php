<?php

namespace Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use League\Csv\Writer;

abstract class BaseController extends Controller
{

    use DispatchesJobs, ValidatesRequests;

    protected $page = null;
    protected $per_page = null;
    protected $is_restricted_adminer;
    protected $is_limitied_editor;

    /* @var Request $request */
    protected $request;

    public function __construct()
    {
        $request = app()->make('Illuminate\Http\Request');
        $this->page     = $this->page ?: $request->get('page', 1);
        $this->per_page = $this->per_page ?: $request->get('pp', 200);

        $this->request  = $request;

        if (isset($this->cert)) {
            $this->setCert();
        }
    }

    protected function setCert()
    {
        if (!auth()->check()) {
            $this->is_restricted_adminer = true;
            return;
        }

        $this->is_restricted_adminer = auth()->user()->isRestricted($this->cert);
        $this->is_limitied_editor    = auth()->user()->isLimitedEditor($this->cert);
    }

   /**
    * Setup the layout used by the controller.
    *
    * @return void
    */
   // protected function setupLayout()
   // {
   //     if (!is_null($this->layout)) {
   //         $this->layout = view($this->layout);
   //     }
   // }

    /**
     * @param $output
     * @param string $name
     * @return int
     */
    protected function outputArrayToCsv($output, $name = 'output')
    {
        $result = $this->getOutputArray($output);
        $writer = Writer::createFromFileObject(new \SplTempFileObject);

        return $writer->insertAll($result)->output($name . '.csv');
    }

    private function getOutputArray($output)
    {
        $header = [];
        foreach (($output instanceof Collection)? $output->first() : $output[0] as $head => $value) {
            $header[$head] = $head;
        }

        $result = ($output instanceof Collection)? $output->toArray() : $output;
        array_unshift($result, $header);
        return $result;
    }
}
