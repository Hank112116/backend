<?php namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\User;
use Backend\Repo\RepoInterfaces\ReportInterface;
use Carbon;
use Illuminate\Support\Facades\DB;

class ReportRepo implements ReportInterface
{
    public function getCommentReport($dstart, $dend)
    {
        $subHubComments = DB::table('pms_temp_comments')
            ->select('user_id')
            ->whereBetween('date_added', [ $dstart, $dend ]);
        $comments = DB::table('comments')
            ->select('user_id')
            ->whereBetween('date_added', [ $dstart, $dend ])
            ->unionAll($subHubComments);
        $commentCount = DB::table(DB::raw("({$comments->toSql()}) as comments"))
            ->mergeBindings($comments)
            ->selectRaw('`user_id`, COUNT(*) AS comments')
            ->groupBy('user_id');
        $users = DB::table('user')
            ->select('user_id', DB::raw('CONCAT(`user_name`,`last_name`) AS `user_name`'));
        $result = DB::table(DB::raw("({$users->toSql()}) as user"))
            ->select('user.user_id', 'user.user_name', 'report_comment.comments')
            ->mergeBindings($commentCount)
            ->leftJoin(DB::raw("({$commentCount->toSql()}) as report_comment"), 'user.user_id', '=', 'report_comment.user_id')
            ->orderBy('user_id', 'ASC');
        return $result;
    }
}
