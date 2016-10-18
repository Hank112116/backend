<?php
namespace Backend\Api\Lara\UserApi;

use Backend\Api\ApiInterfaces\UserApi\AttachmentApiInterface;
use Backend\Enums\URI\API\HWTrek\UserApiEnum;
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
        $uri        = str_replace('(:num)', $user->user_id, UserApiEnum::ATTACHMENT);
        $this->url  = $this->hwtrek_url . $uri;
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
        $r = $this->post($this->url, ['file' => "@{$file_path}"]);
        fclose($fp);
        unlink($file_path);
        return $r;
    }
}
