<?php
namespace Backend\Assistant\ApiResponse\MarketingApi;

use Backend\Assistant\ApiResponse\BaseResponseAssistant;
use Backend\Model\LowPriorityObject\LowPriorityUser;
use Symfony\Component\HttpFoundation\Response;

class LowPriorityUsersResponseAssistant extends BaseResponseAssistant
{
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public static function create(Response $response)
    {
        return new LowPriorityUsersResponseAssistant($response);
    }

    /**
     * @return array
     */
    public function users()
    {
        $users = $this->decode();

        if (empty($users)) {
            return [];
        }

        $result = [];

        foreach ($users as $user) {
            $result[] = LowPriorityUser::denormalize($user);
        }

        return $result;
    }
}
