<?php

namespace Backend\Http\Controllers;

use Illuminate\Support\Collection;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Repo\RepoInterfaces\ProductInterface;
use Backend\Repo\RepoInterfaces\DuplicateProductInterface;
use Backend\Repo\RepoInterfaces\PerkInterface;
use Backend\Repo\RepoInterfaces\TransactionInterface;
use Backend\Repo\RepoInterfaces\ExpertiseInterface;
use Input;
use Lang;
use Noty;
use Redirect;

class ProductController extends BaseController
{

    protected $cert = 'user';

    public function __construct(
        ProjectInterface $project,
        ProductInterface $product,
        PerkInterface $perk,
        TransactionInterface $transaction,
        ExpertiseInterface $expertise
    ) {
        parent::__construct();

        $this->project_repo     = $project;
        $this->product_repo     = $product;
        $this->perk_repo        = $perk;
        $this->transaction_repo = $transaction;
        $this->expertise_repo   = $expertise;
    }

    /**
     * @route get project/all
     **/
    public function showList()
    {
        $projects = $this->product_repo->byPage($this->page, $this->per_page);

        return $this->showProducts($projects);
    }

    public function showWaitApproves()
    {
        $projects = $this->product_repo->waitApproves();

        return $this->showProducts($projects, $paginate = false);
    }

    public function showSearch($search_by)
    {
        switch ($search_by) {
            case 'name':
                $projects = $this->product_repo->byUserName(Input::get('name'));
                break;

            case 'project_id':
                $projects = $this->product_repo->byProjectId(Input::get('project_id'));
                break;

            case 'title':
                $projects = $this->product_repo->byTitle(Input::get('title'));
                break;

            case 'date':
                $projects = $this->product_repo->byDateRange(Input::get('dstart'), Input::get('dend'));
                break;

            default:
                $projects = new Collection();
        }

        if ($projects->count() == 0) {
            Noty::warnLang('product.no-product');

            return Redirect::action('ProductController@showList');
        } else {
            return $this->showProducts($projects, $paginate = false);
        }
    }

    public function showProducts($projects, $paginate = true)
    {
        if (Input::has('csv')) {
            return $this->renderCsv($projects);
        }

        $template = view('product.list')->with([
            'projects'                 => $projects,
            'has_wait_approve_product' => $this->product_repo->hasWaitApproveProject()
        ]);


        return $paginate ? $template->with('per_page', $this->per_page) : $template;
    }

    private function renderCsv($projects)
    {
        if (Input::get('csv') == 'all') {
            $output = $this->product_repo->toOutputArray($this->product_repo->all());
        } else {
            $output = $this->product_repo->toOutputArray($projects);
        }

        return $this->outputArrayToCsv($output, 'products');
    }

    public function showDetail($id)
    {
        $project = $this->product_repo->find($id);

        if (!$project) {
            Noty::warnLang('product.no-product');

            return Redirect::action('ProductController@showList');
        }

        return view('product.detail')->with([
            'project' => $project,
            'bakers'  => $this->transaction_repo->byProjectId($id)
        ]);
    }

    public function showProjectDetail($id)
    {
        $project = $this->product_repo->find($id);

        if (!$project) {
            Noty::warnLang('product.no-product');

            return Redirect::action('ProductController@showList');
        }

        return view('product.detail-project')
            ->with([
                'project_tag_tree' => $this->project_repo->projectTagTree(),
                'project'          => $project
            ]);
    }


    public function showUpdate($id)
    {
        $is_wait_approve_ongoing = $this->product_repo->isWaitApproveOngoing($id);

        $project = $is_wait_approve_ongoing ?
            $this->product_repo->findDuplicate($id) : $this->product_repo->find($id);

        return view($is_wait_approve_ongoing ? 'product.update-ongoing' : 'product.update')
            ->with([
                'category_options' => $this->project_repo->categoryOptions($is_selected = $project->category ? true : false),
                'project'          => $project
            ]);
    }

    public function showProjectUpdate($project_id)
    {
        $project = $this->product_repo->find($project_id);

        if (!$project) {
            Noty::warnLang('product.no-product');

            return Redirect::action('ProductController@showList');
        }

        return view('product.update-project')
            ->with([
                'project_tag_tree'      => $this->project_repo->projectTagTree(),

                'current_stage_options' => $this->project_repo->currentStageOptions($is_selected = $project->category ? true : false),
                'resource_options'      => $this->project_repo->resourceOptions(),
                'quantity_options'      => $this->project_repo->quantityOptions(),

                'project'               => $project
            ]);
    }

    public function approve($project_id)
    {
        $this->product_repo->approve($project_id);
        Noty::successLang('product.approve');

        return Redirect::action('ProductController@showDetail', $project_id);
    }

    public function reject($project_id)
    {
        $this->product_repo->reject($project_id);
        Noty::successLang('product.reject');

        return Redirect::action('ProductController@showDetail', $project_id);
    }

    public function update($project_id)
    {
        return $this->updateByRepo($this->product_repo, $project_id);
    }

    /**
     * Show ongoing duplicated project
     *
     * @route get project/update-ongoing/{id}
     * @param int $project_id
     * @return View
     **/
    public function updateOngoing($project_id)
    {
        return $this->updateByRepo($this->product_repo->duplicateRepo(), $project_id);
    }

    /**
     * @param ProductInterface|DuplicateProductInterface $repo
     * @param int $project_id
     * @return View
     **/
    private function updateByRepo($repo, $project_id)
    {
        if (Input::has('approve')) {
            $approve = Input::get('approve');
            $repo->approve($project_id, $approve);
            $message = $approve ?
                Lang::get('product.approve') : Lang::get('product.reject');
        } else {
            $repo->update($project_id, Input::all());
            $message = Lang::get('product.update');
        }

        Noty::success($message);

        return Redirect::action('ProductController@showDetail', $project_id);
    }

    public function updateProject($project_id)
    {
        $this->project_repo->update($project_id, Input::all());
        Noty::successLang('common.update-success');

        return Redirect::action('ProductController@showProjectDetail', $project_id);
    }

    /**
     * return a new perk template
     * ajax from pages/project/update.js
     *
     * @route  post project/new-perk/{is_pro}
     * @param  int $is_pro 0:custom, 1:expert-only
     * @return View
     **/
    public function renderNewPerk($is_pro = 1)
    {
        return view('product.update-perk')
            ->with('perk', $this->perk_repo->newEntity($is_pro))
            ->with('is_new', true);
    }

    public function postpone($id)
    {
        $this->product_repo->postpone($id);
        Noty::success(Lang::get('product.postpone'));

        return Redirect::action('ProductController@showDetail', $id);
    }

    public function recoverPostpone($id)
    {
        $this->product_repo->recoverPostpone($id);
        Noty::success(Lang::get('product.recover-postpone'));

        return Redirect::action('ProductController@showDetail', $id);
    }
}
