<?php

namespace Backend\Http\Controllers;

use Backend\Repo\RepoInterfaces\ApplyExpertMessageInterface;
use Backend\Facades\Log;

class ApplyExpertMessageController extends BaseController
{
    private $apply_expert_message;

    public function __construct(ApplyExpertMessageInterface $apply_expert_message)
    {
        parent::__construct();
        $this->apply_expert_message = $apply_expert_message;
    }

    public function showMessages()
    {
        $user_id  = $this->request->get('user_id');
        $messages = $this->apply_expert_message->byUserId($user_id);

        if ($messages) {
            $log_action      = 'View apply expert message';
            $data['user_id'] = $user_id;
            Log::info($log_action, $data);
            return view('apply_expert_message.messages')->with('messages', $messages);
        }
    }
}
