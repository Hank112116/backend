<?php
namespace Backend\Assistant\ApiResponse\SolutionApi;

use Backend\Assistant\ApiResponse\BaseResponseAssistant;
use Symfony\Component\HttpFoundation\Response;

class UploadPictureResponseAssistant extends BaseResponseAssistant
{
    const PICTURE_URL = 'pictureUrl';

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public static function create(Response $response)
    {
        return new UploadPictureResponseAssistant($response);
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        $response = $this->decode();

        $picture_url = $response[self::PICTURE_URL];

        $url_path = parse_url($picture_url, PHP_URL_PATH);

        $file_name = str_ireplace('/upload/thumb/', '', $url_path);

        return $file_name;
    }
}
