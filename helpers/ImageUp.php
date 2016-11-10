<?php

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Intervention\Image\Constraint;
use Aws\S3\S3Client;

/**
 * Upload Image to S3
 * @author Jaster
 **/
class ImageUp
{
    private static $size_limit = 3145728; //3*1024*1024 limit size to 3M
    private $s3config;
    private $upload_set;
    private $name; // upload image name
    private $error = null;

    public function __construct()
    {
        $this->s3config = config('s3');

        $this->s3 = S3Client::factory([
            'key'    => $this->s3config['key'],
            'secret' => $this->s3config['secret'],
            'region' => $this->s3config['region']
        ]);

        $this->upload_set = [
            'Bucket' => $this->s3config['bucket'],
            'ACL'    => $this->s3config['acl']
        ];
    }

    public static function instance()
    {
        return new ImageUp;
    }

    /**
     * Upload image, save both origin and thumb image
     * @vendor aws/aws-sdk-php-laravel
     *
     * @param  UploadedFile $image
     * @return string|bool new_image_name or false
     **/
    public function uploadImage(UploadedFile $image)
    {
        if (!$this->valid($image)) {
            return false;
        }

        $this->name = $this->getRandName($image->guessExtension());

        if (!$this->uploadS3($image->getRealPath(), 'origin')) {
            return false;
        }

        $thumb_image = $this->buildThumbImage($image);

        if (!$this->uploadS3($thumb_image, 'thumb')) {
            return false;
        }

        return $this->name;
    }

    /**
     * Upload image, save both origin and thumb image
     * @vendor aws/aws-sdk-php-laravel
     * @param  UploadedFile $image
     * @return string|bool new_image_name or false
     **/
    public function uploadUserImage(UploadedFile $image)
    {
        if (!$this->valid($image)) {
            return false;
        }

        $this->name = $this->getRandName($image->guessExtension());

        if (!$this->uploadS3($image->getRealPath(), 'origin')) {
            return false;
        }

        $thumb_image = $this->buildUserThumbImage($image);

        if (!$this->uploadS3($thumb_image, 'thumb')) {
            return false;
        }

        return $this->name;
    }

    /**
     * Upload static image
     * @vendor aws/aws-sdk-php-laravel
     * @param UploadedFile $image
     * @return string|bool new_image_name or false
     */
    public function uploadStaticImage(UploadedFile $image)
    {
        if (!$this->valid($image)) {
            return false;
        }

        $this->name = $this->getRandName($image->guessExtension());

        if (!$this->uploadS3($image->getRealPath(), 'static')) {
            return false;
        }

        return $this->name;
    }

    private function valid($image)
    {
        $rules      = ['image' => "image|max:".self::$size_limit .'|mimes:jpeg,jpg,png,gif'];
        $validator  = Validator::make(['image' => $image], $rules);
        $this->error= $validator->messages()->first('image');
        return $validator->passes();
    }

    private function uploadS3($image_path, $type = 'origin')
    {
        $this->upload_set['SourceFile'] = $image_path;
        $this->upload_set['Key'] = $this->s3config["key_{$type}"] . $this->name;

        return $this->s3->putObject(array_merge([
            'Key'    =>  $this->s3config["key_{$type}"].$this->name,
            'SourceFile'   => $image_path,
        ], $this->upload_set));

    }

    /**
     * Build thumb image
     * @param UploadedFile $image
     * @param array $size
     *
     * @vendor intervention/image (need GD library)
     * @return string $path
     **/
    public function buildThumbImage(UploadedFile $image, $size = [])
    {
        if (!$size) {
            $size = [
                'width'  => config('image.thumb_p_width'),
                'height' => config('image.thumb_p_height'),
                'ratio'  => config('image.thumb_p_ratio'),
            ];
        }

        $thumb_image_path = $this->getTempStoragePath() . '/' . $this->getRandName($image->guessExtension());

        Image::make($image->getRealPath())
            ->resize($size['width'], $size['height'], function(Constraint $constraint) use ($size) {
                if($size['ratio']) {
                    $constraint->aspectRatio();
                }
            })->save($thumb_image_path);

        return $thumb_image_path;
    }

    public function buildUserThumbImage(UploadedFile $image)
    {
        return $this->buildThumbImage($image, [
            'width'  => config('image.thumb_u_width'),
            'height' => config('image.thumb_u_height'),
            'ratio'  => config('image.thumb_u_ratio'),
        ]);
    }

    private function getRandName($ext)
    {
        return str_replace('.', '', microtime(true)) . mt_rand(1, 9999) . "." . $ext;
    }

    /**
     * return storage_path( app/storage/images ), build if no exist
     * @return string $dir
     **/
    private function getTempStoragePath()
    {
        $temp_dir = storage_path('images');
        if (!is_dir($temp_dir)) {
            mkdir($temp_dir, 0777);
        }
        return $temp_dir;
    }

    public function getOriginImage($image = '')
    {
        $image = $image?: $this->name;
        return $this->s3config['origin'].$image;
    }

    public function getThumbImage($image = '')
    {
        $image = $image?: $this->name;
        return $this->s3config['thumb'].$image;
    }

    public function getStaticUrl($image_name)
    {
        return $this->s3config['static'].$image_name;
    }
}
