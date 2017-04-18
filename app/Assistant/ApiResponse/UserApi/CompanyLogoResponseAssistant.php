<?php
namespace Backend\Assistant\ApiResponse\UserApi;

use Backend\Assistant\ApiResponse\BaseResponseAssistant;
use Symfony\Component\HttpFoundation\Response;

class CompanyLogoResponseAssistant extends BaseResponseAssistant
{
    const KEY_PICTURE_URL = 'pictureUrl';

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @param Response $response
     * @return CompanyLogoResponseAssistant
     */
    public static function create(Response $response)
    {
        return new CompanyLogoResponseAssistant($response);
    }

    /**
     * @return string|null
     */
    public function url()
    {
        $response = $this->decode();

        if (!array_key_exists(self::KEY_PICTURE_URL, $response)) {
            return null;
        }

        return $response[self::KEY_PICTURE_URL];
    }

    /**
     * @return string|null
     */
    public function getFileName()
    {
        $response = $this->decode();

        if (!array_key_exists(self::KEY_PICTURE_URL, $response)) {
            return null;
        }

        $url_path = parse_url($response[self::KEY_PICTURE_URL], PHP_URL_PATH);

        $file_name = str_ireplace('/upload/thumb/', '', $url_path);

        return $file_name;
    }
}
