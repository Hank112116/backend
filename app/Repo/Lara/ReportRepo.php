<?php namespace Backend\Repo\Lara;

use Backend\Repo\RepoInterfaces\ReportInterface;
use Backend\Model\Eloquent\User;

class ReportRepo implements ReportInterface
{
    /***
     * @param $days How many days in interval from now
     * @return array Array that the first element is the start of interval, and the second element is the end of interval.
     */
    private function getTimeIntervalArray($days)
    {
        $start = new \DateTime('now');
        $start->modify('-'.$days.' days');
        $end = new \DateTime('now');
        return array($start,$end);
    }
    /***
     * To get users' user_id, name, company, user_type, date_added who registered in last 7 days
     * @return users who registered in last 7 days
     ***/
    public function getRegisters()
    {
        $columns=array(
            'user_id',
            'user_name',
            'last_name',
            'company',
            'user_type',
            'date_added'
        );

        $users=User::whereBetween('date_added', $this->getTimeIntervalArray(7))
                    ->get($columns);
        return $users;
    }
}
