<?php namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\MailTemplate;
use Backend\Repo\RepoInterfaces\MailTemplateInterface;

class MailTemplateRepo implements MailTemplateInterface
{
    public function __construct(MailTemplate $mt)
    {
        $this->mt = $mt;
    }

    public function find($id)
    {
        return $this->mt->find($id);
    }

    public function byActive($active = true)
    {
        return $this->mt
            ->where('active', $active ? 1 : 0)
            ->get();
    }

    public function getTags()
    {
        $emails = $this->mt
            ->where('active', 1)
            ->select('message')
            ->get();

        $pattern = '/{[\w_]*}/';
        $tags = [];
        foreach ($emails as $e) {
            preg_match_all($pattern, $e->message, $matches);
            $tags = array_merge($tags, $matches[0]);
        }

        $tags = array_unique($tags);
        sort($tags);

        return $tags;
    }

    public function create($data)
    {
        $email = new MailTemplate();
        $email->fill(array_except($data, ['_token']));
        $email->save();
        $render = [
            'success'  => true,
            'email_id' => $email->email_template_id,
        ];

        return $render;
    }

    public function update($id, $data)
    {
        $email = $this->find($id);
        $email->fill(array_except($data, ['_token']));
        $email->save();
    }

    public function switchActive($id)
    {
        $email = $this->find($id);
        $email->active = $email->active ? 0 : 1;
        $email->save();
    }
}
