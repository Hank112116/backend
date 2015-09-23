<?php

namespace Backend\Http\Controllers;

use Backend\Repo\RepoInterfaces\MailTemplateInterface;
use Input;
use Noty;
use Redirect;
use Log;

class MailTemplateController extends BaseController
{

    protected $cert = 'email_template';

    public function __construct(MailTemplateInterface $mt)
    {
        parent::__construct();
        $this->mail_repo = $mt;
    }

    /**
     * show email templates
     * @route get mail/all
     **/
    public function showList()
    {
        return view('email_template.list')
            ->with('is_show_active', true)
            ->with('mails', $this->mail_repo->byActive(true));
    }

    public function showDisactiveList()
    {
        return view('email_template.list')
            ->with('is_show_active', false)
            ->with('mails', $this->mail_repo->byActive(false));
    }

    public function showDetail($id)
    {
        return view('email_template.detail')
            ->with('email', $this->mail_repo->find($id))
            ->with('template', view('email_template.hwtrek-inline')->render());
    }

    /**
     * show create email template
     * @route get mail/create
     **/
    public function showCreate()
    {
        return view('email_template.create')
            ->with('tags', $this->mail_repo->getTags());
    }

    /**
     * create email template
     * @route post mail/create
     **/
    public function create()
    {
        $data = Input::all();

        $render = $this->mail_repo->create($data);
        Noty::success('Create a new template successful');

        $log_action = 'New template';
        $log_data   = [
            'email'   => $render['email_id'],
            'task'    => $data['task'],
            'from'    => $data['from_address'],
            'reply'   => $data['reply_address'],
            'subject' => $data['subject']
        ];
        Log::info($log_action, $log_data);

        return Redirect::action('MailTemplateController@showList');
    }

    /**
     * show update email template
     * @route get mail/update/{id}
     **/
    public function showUpdate($id)
    {
        return view('email_template.update')
            ->with('email', $this->mail_repo->find($id))
            ->with('tags', $this->mail_repo->getTags())
            ->with('template', view('email_template.hwtrek-inline')->render());
    }

    /**
     * update email template
     * @route post mail/update/{id}
     **/
    public function update($id)
    {
        $log_action = 'Edit email template';
        $log_data   = [
            'email' => $id
        ];
        Log::info($log_action, $log_data);

        $this->mail_repo->update($id, Input::all());
        Noty::success('Update successful');

        return Redirect::action('MailTemplateController@showList');
    }

    /**
     * update email template
     * @param integer $id
     * @route post mail/trigger-active/{id}
     * @return redirect
     **/
    public function triggerActive($id)
    {
        $this->mail_repo->switchActive($id);
        $email = $this->mail_repo->find($id);

        $msg = ($email->active ? "Active" : "Deactive") . " #{$email->email_template_id} successful";
        Noty::success($msg);

        $status     = $email->active ? "Active" : "Deactive";
        $log_action = $status.' email template';
        $log_data   = [
            'email'  => $id,
            'status' => $status
        ];
        Log::info($log_action, $log_data);

        return Redirect::action('MailTemplateController@showList');
    }

    public function fetchHtmlTemplate()
    {
        return view('email_template.hwtrek-inline');
    }
}
