<?php namespace Backend\Model;

use Backend\Model\Eloquent\Solution;
use Backend\Model\Eloquent\DuplicateSolution;
use ImageUp;
use Carbon;
use Backend\Model\ModelInterfaces\SolutionModifierInterface;

class SolutionModifier implements SolutionModifierInterface
{
    private static $update_columns = [
        'user_id',
        'solution_type', 'solution_detail',
        'solution_title', 'solution_summary',
        'description', 'solution_application_compatibility',

        'business_prospect_reference', 'business_prospect_reference_other',
        'project_category_co_work', 'project_category_co_work_other',

        'solution_obtained', 'solution_obtained_other',
        'customer_portfolio',

        'tags', 'is_deleted',
    ];

    private static $ongoing_update_columns = [
        //'user_id',

        'solution_type', 'solution_detail',
        'solution_title', 'solution_summary',
        'image', 'image_gallery',

        'description', 'solution_application_compatibility',

        'business_prospect_reference', 'business_prospect_reference_other',
        'project_category_co_work', 'project_category_co_work_other',

        'solution_obtained', 'solution_obtained_other',
        'customer_portfolio',

        'tags',
    ];

    private $image_uploader;

    public function __construct(
        Solution $solution,
        DuplicateSolution $duplicate,
        ImageUp $image_uploader
    ) {
        $this->solution       = $solution;
        $this->duplicate      = $duplicate;
        $this->image_uploader = $image_uploader;
    }

    public function update($solution_id, $data)
    {
        $solution = $this->solution->find($solution_id);

        $setter = array_only($data, self::$update_columns);
        $setter = array_merge(
            $setter,
            $this->updateImageGalleries($solution, $data)
        );

        $solution->fill($setter);
        $solution->update_time = Carbon::now();

        if ($solution->is_deleted) {
            $solution->deleted_date = $solution->update_time;
        }

        $solution->save();
    }

    public function managerApprove($solution_id)
    {
        $setter = [
            'is_manager_approved' => 1,
            'approve_time'        => Carbon::now(),
        ];

        $this->updateSolution($solution_id, $setter);
    }

    public function approve($solution_id)
    {
        $setter = array_merge(
            $this->solution->on_shelf_status, [
            'is_manager_approved' => 0,
            'approve_time'        => Carbon::now(),
            ]
        );

        $this->updateSolution($solution_id, $setter);
    }

    public function reject($solution_id)
    {
        $setter = array_merge(
            $this->solution->draft_status, [
            'is_manager_approved' => 0,
            'approve_time'        => Carbon::now(),
            ]
        );

        $this->updateSolution($solution_id, $setter);
    }

    public function onShelf($solution_id)
    {
        $this->updateSolution($solution_id, $this->solution->on_shelf_status);
    }

    public function offShelf($solution_id)
    {
        $this->updateSolution($solution_id, $this->solution->off_shelf_status);
    }

    public function ongoingUpdate($solution_id, $data)
    {
        $duplicate = $this->duplicate->find($solution_id);

        $setter = array_only($data, self::$ongoing_update_columns);
        $setter = array_merge(
            $setter,
            $this->updateImageGalleries($duplicate->image_gallery, $duplicate->image, $data)
        );

        $duplicate->fill($setter);
        $duplicate->update_time = Carbon::now();
        $duplicate->save();
    }

    public function ongoingManagerApprove($solution_id)
    {
        $this->duplicate->where('solution_id', $solution_id)->update(
            [
            'is_manager_approved' => 1,
            ]
        );
    }

    public function ongoingApprove($solution_id)
    {
        $duplicate = $this->duplicate->find($solution_id);
        $duplicate->delete(); // Delete duplicate project after copy

        $data = array_only($duplicate->toArray(), self::$ongoing_update_columns);
        $data = array_merge($data, $this->solution->on_shelf_status);
        $this->updateSolution($solution_id, $data);
    }

    public function ongoingReject($solution_id)
    {
        $this->duplicate->where('solution_id', $solution_id)->delete();
    }

    private function updateSolution($solution_id, $data)
    {
        $solution = $this->solution->find($solution_id);
        $solution->fill($data);
        $solution->update_time = Carbon::now();
        $solution->save();
    }

    private function updateImageGalleries($solution, $data)
    {
        $gallery = $solution->image_gallery ? json_decode($solution->image_gallery, true) : [];

        $cover = '';
        for ($i = 0; $i < 5; $i ++) {
            $key         = "thumb_{$i}";
            $delete_key  = "thumb_delete_{$i}";
            $content_key = "thumb_desc_{$i}";

            if ($data[$delete_key] == 1 or !$this->hasGalleryImage($gallery, $i, $data, $key)) {
                unset($gallery[$i]);
                continue;
            }

            $image_name = array_key_exists($key, $data) ?
                $this->image_uploader->uploadImage($data[$key]) :
                $gallery[$i][Solution::GALLERY_FILENAME];

            $gallery[$i] = [
                Solution::GALLERY_URL         => $this->image_uploader->getThumbImage($image_name),
                Solution::GALLERY_FILENAME    => $image_name,
                Solution::GALLERY_DESCRIPTION => $data[$content_key],
            ];

            if (array_key_exists('cover', $data) and $data['cover'] == $key) {
                $cover = $image_name;
            }
        }

        $gallery = array_values($gallery);

        if (!$cover and count($gallery) > 0) {
            $cover = $gallery[0][Solution::GALLERY_FILENAME];
        }

        return [
            'image'         => $cover ?: $solution->image,
            'image_gallery' => json_encode($gallery)
        ];
    }

    private function hasGalleryImage($gallery, $index, $data, $key)
    {
        if (array_key_exists($key, $data)) {
            return true;
        }

        return array_key_exists($index, $gallery) and $gallery[$index][Solution::GALLERY_FILENAME];
    }
}
