<?php namespace Backend\Repo\Lara;

use Backend\Model\Solution\Entity\BasicSolution;
use ImageUp;
use Backend\Model\Eloquent\Solution;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoInterfaces\SolutionInterface;
use Backend\Model\ModelInterfaces\SolutionModifierInterface;
use Backend\Model\ModelInterfaces\ProjectTagBuilderInterface;
use Backend\Model\ModelInterfaces\FeatureModifierInterface;
use Backend\Model\Plain\SolutionCategory;
use Backend\Model\Plain\SolutionCertification;
use Illuminate\Support\Collection;
use Backend\Repo\RepoTrait\PaginateTrait;

class SolutionRepo implements SolutionInterface
{
    use PaginateTrait;

    private $solution;
    private $project_tag_builder;
    private $solution_modifier;
    private $category;
    private $certification;
    private $image_uploader;
    private $feature;
    private $user_repo;

    public function __construct(
        Solution $solution,
        SolutionModifierInterface $solution_modifier,
        ProjectTagBuilderInterface $project_tag_builder,
        FeatureModifierInterface $feature_modify,
        SolutionCategory $category,
        SolutionCertification $certification,
        ImageUp $uploader,
        UserInterface $user_repo
    ) {
        $this->solution            = $solution;
        $this->project_tag_builder = $project_tag_builder;
        $this->solution_modifier   = $solution_modifier;
        $this->category            = $category;
        $this->certification       = $certification;
        $this->image_uploader      = $uploader;
        $this->feature             = $feature_modify;
        $this->user_repo           = $user_repo;
    }

    public function find($id)
    {
        return $this->solution->find($id);
    }

    public function findSolution($id)
    {
        return $this->solution
                    ->where('solution_id', $id)
                    ->querySolution()
                    ->first();
    }

    public function findProgram($id)
    {
        return $this->solution
                    ->where('solution_id', $id)
                    ->queryProgram()
                    ->first();
    }

    public function all()
    {
        return $this->solution->all();
    }

    public function byUserName($name)
    {
        $users = $this->user_repo->byName($name);
        if ($users->count() == 0) {
            return new Collection();
        }

        $solutions = $this->solution->with('user')
            ->whereIn('user_id', $users->lists('user_id'))
            ->orderBy('solution_id', 'desc')
            ->get();

        return $this->configApprove($solutions);
    }

    public function byTitle($title = '')
    {
        if (!$title) {
            return new Collection();
        }

        $trimmed = preg_replace('/\s+/', ' ', $title); //replace multiple space to one
        $keys    = explode(' ', $trimmed);

        $solutions = $this->solution->with('user')
            ->orderBy('solution_id', 'desc');

        foreach ($keys as $k) {
            $solutions->orWhere('solution_title', 'LIKE', "%{$k}%");
        }

        return $this->configApprove($solutions->get());
    }

    public function configApprove($solutions)
    {
        foreach ($solutions as $solution) {
            $solution->is_wait_approve_draft   = $solution->isWaitApprove();
        }

        return $solutions;
    }

    public function categoryOptions()
    {
        return $this->category->options();
    }

    public function certificationOptions()
    {
        return $this->certification->options();
    }

    public function onShelf($solution_id)
    {
        $this->solution_modifier->onShelf($solution_id);
    }

    public function offShelf($solution_id)
    {
        $this->solution_modifier->offShelf($solution_id);
    }

    public function updateImageGalleries($solution_id, $data)
    {
        return $this->solution_modifier->updateImageGalleries($solution_id, $data);
    }

    /*
     * @param Paginator|Collection
     * return array
     */
    public function toOutputArray($solutions)
    {
        $output = [];
        foreach ($solutions as $solution) {
            $output[ ] = $this->solutionOutput($solution);
        }

        return $output;
    }

    private function solutionOutput(BasicSolution $solution)
    {
        return [
            '#'            => $solution->getId(),
            'Title'        => $solution->getTitle(),
            'Category'     => $solution->getTextCategory().', '.$solution->getTextSubCategory(),
            'Member'       => $solution->getOwnerFullName(),
            'Country'      => $solution->getOwnerLocation(),
            'Tags'         => $solution->getTextTags(),
            'Brief'        => $solution->getSummary(),
            'Status'       => $solution->getTextStatus(),
            'Approve Date' => $solution->getTextApprovedDate(),
        ];
    }
}
