<?php

namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ProjectAttachment extends Eloquent
{
    protected $table = 'project_attachment';
    public static $unguarded = true;

    public function attachment()
    {
        return $this->belongsTo(Attachment::class, 'attachment_id', 'id');
    }
}
