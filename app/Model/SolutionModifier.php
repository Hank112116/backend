<?php namespace Backend\Model;

use Backend\Model\Eloquent\Solution;
use ImageUp;
use Carbon;
use Backend\Model\ModelInterfaces\SolutionModifierInterface;

class SolutionModifier implements SolutionModifierInterface
{
    private $image_uploader;
    private $solution;

    public function __construct(
        Solution $solution,
        ImageUp $image_uploader
    ) {
        $this->solution       = $solution;
        $this->image_uploader = $image_uploader;
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
            $this->solution->on_shelf_status,
            [
            'is_manager_approved' => 0,
            'approve_time'        => Carbon::now(),
            ]
        );

        $this->updateSolution($solution_id, $setter);
    }

    public function managerToProgram($solution_id)
    {
        $setter = $this->solution->is_pending_program_status;

        $this->updateSolution($solution_id, $setter);
    }

    public function toProgram($solution_id)
    {
        $setter = $this->solution->is_program_status;

        $this->updateSolution($solution_id, $setter);
    }

    public function managerToSolution($solution_id)
    {
        $setter = $this->solution->is_pending_solution_status;

        $this->updateSolution($solution_id, $setter);
    }

    public function toSolution($solution_id)
    {
        $setter = $this->solution->is_solution_status;

        $this->updateSolution($solution_id, $setter);
    }
    public function reject($solution_id)
    {
        $setter = array_merge(
            $this->solution->draft_status,
            [
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

    private function updateSolution($solution_id, $data)
    {
        $solution = $this->solution->find($solution_id);
        if ($solution) {
            $solution->fill($data);
            $solution->update_time = Carbon::now();
            $solution->save();
        }
    }

    public function updateImageGalleries($solution_id, $data)
    {
        $solution = $this->solution->find($solution_id);

        $gallery = $solution->image_gallery ? json_decode($solution->image_gallery, true) : [];

        $cover = '';
        for ($i = 0; $i < 5; $i ++) {
            $key         = "thumb_{$i}";
            $delete_key  = "thumb_delete_{$i}";
            $content_key = "thumb_desc_{$i}";
            if (array_key_exists($delete_key, $data) and array_key_exists($content_key, $data)) {
                if ($data[$delete_key] == 1 or !$this->hasGalleryImage($gallery, $i, $data, $key)) {
                    unset($gallery[$i]);
                    continue;
                }
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
            'image_gallery' => $gallery
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
