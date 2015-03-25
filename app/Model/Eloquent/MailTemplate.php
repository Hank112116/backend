<?php
namespace Backend\Model\Eloquent;
use Input;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * EMail Template Model
 *
 * @refer   hwp/application/schema/email_template.php
 * @table   email_template
 * @pk      email_template_id
 * @columns task, from_address, reply_address, subject, message
 **/
class MailTemplate extends Eloquent
{

    protected $table = 'email_template';
    protected $primaryKey = 'email_template_id';
    public $timestamps = false;
    public static $unguarded = true;

    public static function createTemplate()
    {
        $email = new MailTemplate;
        $email->fill(Input::except(['_token']));
        $email->save();
    }

    public function validUpdate()
    {
        $this->fill(Input::except(['_token']));
        $this->save();
    }

    public function trigger()
    {
        $this->active = $this->active ? 0 : 1;
        $this->save();
    }

    public static function getTags()
    {
        $emails = MailTemplate::where('active', '=', 1)
            ->select('message')->get();

        $pattern = '/{[\w_]*}/';
        $tags    = [];
        foreach ($emails as $e) {
            preg_match_all($pattern, $e->message, $matches);
            $tags = array_merge($tags, $matches[0]);
        }
        $tags = array_unique($tags);
        sort($tags);

        return $tags;
    }
}
