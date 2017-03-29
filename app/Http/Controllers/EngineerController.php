<?php

namespace Backend\Http\Controllers;

use Carbon\Carbon;
use DB;
use Noty;
use Backend\Model\Eloquent\Project;
use ProjectSubmitTimeUpdater;
use ProjectTagMigrater;
use ProjectTagSeeder;
use QueryLog;
use QuestionnaireProjectTagMigrater;
use SSH;
use Backend\Model\Eloquent\User;

class EngineerController extends BaseController
{

    public function logServer()
    {
        return view('engineer.log-server');
    }
    public function bug()
    {
        return view('engineer.bug');
    }

    public function bugDecode()
    {
        $reporter = app()->make('Backend\ErrorReport\ReporterInterface');

        return response()->json([
            'report' => $reporter->decrypt($this->request->get('bug'))
        ]);
    }

    public function migrate()
    {
        return view('engineer.migrate');
    }

    public function migrateProjectTag()
    {
        $seeder = new ProjectTagSeeder();
        $seeder->run();
        Noty::success('Done');

        return redirect()->to('/');
    }

    public function migrateTagsColumn()
    {
        $seeder = new ProjectTagMigrater();
        $seeder->run();
        Noty::success('Done');

        return redirect()->to('/');
    }

    public function migrateQuestionnaireColumn()
    {
        $seeder = new QuestionnaireProjectTagMigrater();
        $seeder->run();
        Noty::success('Done');

        return redirect()->to('/');
    }

    public function migrateProjectSubmitDate()
    {
        $seeder = new ProjectSubmitTimeUpdater();
        $seeder->run();
        Noty::success('Done');

        return redirect()->to('/');
    }

    public function index()
    {
        $logs = QueryLog::instance()->getLogs();

        return view('engineer.index')->with('logs', $logs);
    }

    public function deleteLog()
    {
        QueryLog::instance()->deleteLog();

        return redirect()->action('EngineerController@index');
    }

    public function updateHwtrekDevDatabase()
    {
        SSH::into('hwtrek')->run([
            'cd /apps/hwp/hwp_database',
            './scripts/restore.sh'
        ]);

        Noty::success('Update HWtrek Dev Database Success');

        return redirect()->to('/');
    }

    public function getUsersByMonth()
    {
        $nodes = User::select(DB::raw(
            "count(user_id) as user_amount, year(date_added) as join_y, month(date_added) as join_m"
        ))
            ->groupBy('join_y')
            ->groupBy('join_m')
            ->get();

        $users = [];
        $sum   = 0;

        foreach ($nodes as $n) {
            $users[]  = $n->user_amount + $sum;
            $months[] = "{$n->join_y}-{$n->join_m}";
            $sum += $n->user_amount;
        }

        return response()->json(['users' => $users, 'months' => $months]);
    }

    public function getProjectsStatus()
    {
        $projects = Project::all();
        $status   = ['draft'   => ['value' => 0, 'color' => '#F38630'],
                     'ongoing' => ['value' => 0, 'color' => '#E0E4CC'],
                     'success' => ['value' => 0, 'color' => '#69D2E7'],
                     'fail'    => ['value' => 0, 'color' => '#FDFDFD'],
        ];

        foreach ($projects as $p) {
            switch (true) {
                case $p->isFundEnd():
                    $st = ($p->amount_get < $p->amount) ? 'fail' : 'success';
                    $status[$st]['value'] ++;
                    break;

                case $p->isOnGoing():
                    $status['ongoing']['value'] ++;
                    break;

                default:
                    $status['draft']['value'] ++;
                    break;
            }
        }

        return response()->json(['project_status' => array_values($status)]);
    }

    public function showProductsInfograph()
    {
        $products = Project::select(
            DB::raw('project.project_title, project.amount, project.end_date,
                project.amount_get, count(transaction_id) as backers')
        )
            ->leftJoin('transaction', 'project.project_id', '=', 'transaction.project_id')
            ->where('project.active', '=', '1')
            ->where('project.amount', '>', '0')
            ->where('project.end_date', '>', Carbon::now()->toDateTimeString())
            ->groupBy('project.project_id')
            ->get();

        return view('engineer.products')
            ->with('products', $products);
    }

    public function cropTest()
    {
        $project   = Project::find('896');
        $image_url = $project->getImagePath();

        $type   = pathinfo($image_url, PATHINFO_EXTENSION);
        $data   = file_get_contents($image_url);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        return view('engineer.crop')
            ->with([
                'image_url' => $image_url,
                'base64'    => $base64,
                'type'      => $type,
            ]);
    }

    public function viewInject()
    {
        return view('engineer.inject');
    }
}
