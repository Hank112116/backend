<?php namespace Backend\Repo\Lara;

use ImageUp;
use Backend\Model\Eloquent\Manufacturer;
use Backend\Repo\RepoInterfaces\LandingManufacturerInterface;

class LandingManufacturerRepo implements LandingManufacturerInterface
{
    public function __construct(Manufacturer $m, ImageUp $image_uplodaer)
    {
        $this->manufacturer = $m;
        $this->image_uploader = $image_uplodaer;
    }

    public function all()
    {
        return $this->manufacturer->all();
    }

    public function dummy()
    {
        $entity = new Manufacturer();
        $entity->id =  time();
        $entity->img_url =  $this->manufacturer->getDefaultImage();

        return $entity;
    }

    public function reset($data)
    {
        Manufacturer::truncate();

        foreach (array_except($data, ['_token']) as $key => $m) {
            $f = new Manufacturer();
            $f->fill(array_except($m, ['logo']));

            if (null !== $m['logo']) {
                $f->img_url = $this->uploadImage($m['logo']);
            }

            $f->save();
        }
    }

    private function uploadImage($image)
    {
        $image_name = $this->image_uploader->uploadStaticImage($image);

        return $this->image_uploader->getStaticUrl($image_name);
    }
}
