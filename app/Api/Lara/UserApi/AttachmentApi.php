<?php
namespace Backend\Api\Lara\UserApi;

use Backend\Api\ApiInterfaces\UserApi\AttachmentApiInterface;
use Backend\Enums\URI\API\HWTrekApiEnum;
use Backend\Api\Lara\HWTrekApi;
use Backend\Model\Eloquent\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AttachmentApi extends HWTrekApi implements AttachmentApiInterface
{
    private $url;
    private $user;

    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
        $this->url  = 'https://' . $this->front_domain . HWTrekApiEnum::API . HWTrekApiEnum::BACKEND . HWTrekApiEnum::USER . '/' . $user->user_id . HWTrekApiEnum::ATTACHMENT;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttachment()
    {
        return $this->get($this->url);
    }

    /**
     * {@inheritDoc}
     */
    public function updateAttachment(array $attachments)
    {
        return $this->patch($this->url, ['attachments' => $attachments]);
    }

    /**
     * {@inheritDoc}
     */
    public function putAttachment(UploadedFile $file)
    {
        $upload_dir = '/tmp/';
        $file->move($upload_dir, $file->getClientOriginalName());
        $file_path = $upload_dir . $file->getClientOriginalName();
        $fp        = fopen($file_path, "r");
        $this->curl->setHeader('Content-Type', 'multipart/form-data');
        $this->curl->setOpt(CURLOPT_INFILE, $fp);
        $this->curl->setOpt(CURLOPT_INFILESIZE, filesize($file_path));
        $r = $this->put($this->url, ['file' => "@{$file_path}"]);
        fclose($fp);
        unlink($file_path);
        return $r;
    }
}
