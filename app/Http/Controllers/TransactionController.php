<?php

namespace Backend\Http\Controllers;

use Illuminate\Support\Collection;
use Backend\Repo\RepoInterfaces\TransactionInterface;
use Input;
use Noty;
use Redirect;

/**
 * TODO Unused, remove
 *
 * Class TransactionController
 *
 * @package Backend\Http\Controllers
 */
class TransactionController extends BaseController
{
    protected $cert = 'project';

    private $transaction_repo;

    public function __construct(TransactionInterface $t)
    {
        parent::__construct();
        $this->transaction_repo = $t;
    }

    public function showList()
    {
        $transactions = $this->transaction_repo->byPage($this->page, $this->per_page);

        return $this->showTransactions($transactions);
    }

    public function showSearch($search_by)
    {
        switch ($search_by) {
            case 'name':
                $transactions = $this->transaction_repo->byUserName(Input::get('name'));
                break;

            case 'title':
                $transactions = $this->transaction_repo->byProjectTitle(Input::get('title'));
                break;

            case 'date':
                $transactions = $this->transaction_repo->byDateRange(Input::get('dstart'), Input::get('dend'));
                break;

            default:
                $transactions = new Collection();
        }

        if ($transactions->count() == 0) {
            Noty::warnLang('common.no-search-result');

            return Redirect::action('TransactionController@showList');
        }

        return $this->showTransactions($transactions, $paginate = false);
    }

    public function showTransactions($transactions, $paginate = true)
    {
        if (Input::has('csv')) {
            return $this->renderCsv($transactions);
        }

        $template = view('product.transactions')
            ->with('transactions', $transactions);

        return $paginate ? $template->with('per_page', $this->per_page) : $template;
    }

    private function renderCsv($transactions)
    {
        if (Input::get('csv') == 'all') {
            $output = $this->transaction_repo->toOutputArray($this->transaction_repo->all());
        } else {
            $output = $this->transaction_repo->toOutputArray($transactions);
        }

        return $this->outputArrayToCsv($output, 'transactions');
    }
}
