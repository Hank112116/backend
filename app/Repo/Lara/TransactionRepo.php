<?php namespace Backend\Repo\Lara;

use Carbon;
use Backend\Model\Eloquent\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoInterfaces\TransactionInterface;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Repo\RepoTrait\PaginateTrait;

/**
 * TODO Unused, remove
 *
 * Class TransactionRepo
 *
 * @package Backend\Repo\Lara
 */
class TransactionRepo implements TransactionInterface
{
    use PaginateTrait;
    protected $with_relations = ['user', 'perk', 'project'];

    public function __construct(
        Transaction $t,
        UserInterface $user,
        ProjectInterface $project
    ) {
        $this->transaction_repo = $t;
        $this->user_repo = $user;
        $this->project_repo = $project;
    }

    public function all()
    {
        return $this->transaction_repo
            ->with($this->with_relations)
            ->orderBy('perk_id', 'asc')
            ->orderBy('transaction_id', 'desc')
            ->get();
    }

    public function byPage($page = 1, $limit = 20)
    {
        $collection =  $this->modelBuilder($this->transaction_repo, $page, $limit)
            ->with($this->with_relations)
            ->get();

        return $this->getPaginateContainer($this->transaction_repo, $page, $limit, $collection);
    }

    public function byUserName($name)
    {
        $users = $this->user_repo->byName($name);

        if ($users->count() == 0) {
            return new Collection();
        }

        return $this->transaction_repo
            ->with($this->with_relations)
            ->whereIn('user_id', $users->lists('user_id'))
            ->orderBy('transaction_id', 'desc')
            ->get();
    }

    public function byProjectId($project_id)
    {
        return $this->transaction_repo
            ->with($this->with_relations)
            ->where('project_id', $project_id)
            ->orderBy('perk_id', 'asc')
            ->orderBy('transaction_id', 'desc')
            ->get();
    }

    public function byProjectTitle($title)
    {
        $projects = $this->project_repo->byTitle($title);

        if ($projects->count() == 0) {
            return new Collection();
        }

        return $this->transaction_repo
            ->with($this->with_relations)
            ->whereIn('project_id', $projects->lists('project_id'))
            ->orderBy('transaction_id', 'desc')
            ->get();
    }

    public function byDateRange($from, $to)
    {
        if (!$from and !$to) {
            return new Collection();
        }

        $from = $from ? Carbon::parse($from)->startOfDay() : Carbon::now()->startOfMonth()->startOfDay();
        $to   = $to ?   Carbon::parse($to)->endOfDay() :  Carbon::now()->endOfDay();

        return $this->transaction_repo
            ->whereBetween('transaction_date_time', [$from, $to])
            ->orderBy('transaction_id', 'desc')
            ->get();
    }

    /*
      * @param Paginator|Collection
      * return array
     */
    public function toOutputArray($transactions)
    {
        $output = [];
        foreach ($transactions as $transaction) {
            $output[ ] = $this->transactionOutput($transaction);
        }

        return $output;
    }

    private function transactionOutput(Transaction $transaction)
    {
        return [
            '#'              => $transaction->transaction_id,
            'Project'        => $transaction->textProjectTitle(),
            'Member'         => $transaction->textUserName(),
            'Total'          => $transaction->preapproval_total_amount,
            'Transaction id' => $transaction->preapproval_key,
            'Paypal email'   => $transaction->email,
            'Host ip'        => $transaction->host_ip,
            'Time'           => $transaction->transaction_date_time,
        ];
    }
}
