<?php

namespace Backend\Http\Controllers;

use Auth;
use Input;
use League\Csv\Writer;
use League\Csv\Plugin\SkipNullValuesFormatter;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Foundation\Validation\ValidatesRequests;

class BaseController extends Controller
{

    use DispatchesCommands, ValidatesRequests;

    protected $page = null;
    protected $per_page = null;
    protected $is_restricted_adminer;

    public function __construct()
    {
        $this->page     = $this->page ?: Input::get('page', 1);
        $this->per_page = $this->per_page ?: Input::get('pp', 50);

        if (isset($this->cert)) {
            $this->setCert();
        }
    }

    protected function setCert()
    {
        if (!Auth::check()) {
            $this->is_restricted_adminer = true;

            return;
        }

        $this->is_restricted_adminer = Auth::user()->isRestricted($this->cert);
    }
//
//    /**
//     * Setup the layout used by the controller.
//     *
//     * @return void
//     */
//    protected function setupLayout()
//    {
//        if (!is_null($this->layout)) {
//            $this->layout = view($this->layout);
//        }
//    }
//
    protected function outputArrayToCsv($output, $name = 'output') {
        $writer = Writer::createFromFileObject(new \SplTempFileObject);
        $writer->addFormatter(new SkipNullValuesFormatter());
        return $writer->insertAll($output)->output($name . '.csv');
    }
}
