<?php namespace Backend\Repo\Lara;

use Backend\Repo\RepoInterfaces\ReportInterface;
use Backend\Model\Eloquent\User;
use Carbon\Carbon;

class ReportRepo implements ReportInterface
{
    private $user;

    public function __construct(
        User $user
    ) {
        $this->user=$user;
    }
    /***
     * @param string $dstart    The day of start
     * @param string $dend      The day of end
     * @return array Array that the first element is the start of interval, and the second element is the end of interval.
     */
    private function getTimeIntervalArray($dstart, $dend)
    {
        $dstart = $dstart ? Carbon::parse($dstart) : Carbon::now()->startOfMonth();
        $dend   = $dend ? Carbon::parse($dend)->addDay() : Carbon::now();
        return array($dstart, $dend);
    }

    /**
     * @param array $condition  The condition of registers' report
     * @param int   $perpage    How many data per page
     * @return Collection User who is under the condition
     */
    public function getRegisters($condition, $perpage = 15)
    {
        $filter=$this->user;

        $filter = $filter->whereBetween('date_added', $this->getTimeIntervalArray($condition['dstart'], $condition['dend']));

        if ($condition['filter']=='expert') {
            $filter=$filter->expert();
        }
        if ($condition['filter']=='creator') {
            $filter=$filter->creator();
        }
        if ($condition['filter']=='pm') {
            $filter=$filter->PM();
        }
        $filter=$filter->paginate($perpage);

        return $filter;
    }
}
