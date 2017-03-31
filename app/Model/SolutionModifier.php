<?php namespace Backend\Model;

use Backend\Api\ApiInterfaces\SolutionApi\SolutionApiInterface;
use Backend\Assistant\ApiResponse\SolutionApi\UploadPictureResponseAssistant;
use Backend\Model\Eloquent\Solution;
use Backend\Model\ModelInterfaces\SolutionModifierInterface;
use ImageUp;

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

            /* @var SolutionApiInterface $solution_api */
            $solution_api = app()->make(SolutionApiInterface::class);

            if (array_key_exists($key, $data)) {
                $response   = $solution_api->uploadPicture($solution_id, $data[$key]);
                $assistant  = UploadPictureResponseAssistant::create($response);

                $image_name = $assistant->getFileName();
            } else {
                $image_name = $gallery[$i][Solution::GALLERY_FILENAME];
            }

            $gallery[$i] = [
                Solution::GALLERY_URL         => $this->image_uploader->getThumbImage($image_name),
                Solution::GALLERY_FILENAME    => $image_name,
                Solution::GALLERY_DESCRIPTION => array_key_exists($content_key, $data) ? $data[$content_key] : '',
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
