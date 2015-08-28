<?php
namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class PmsTempComment extends Eloquent
{

    protected $table = 'pms_temp_comments';
    protected $primaryKey = 'comment_id';
    public $timestamps = false; // not use created_at, updated_at
    public static $unguarded = true;

    protected $appends = [ 'image_urls' ];

    public function scopeQueryDate($query, $dstart, $dend)
    {
        return $query->whereBetween('date_added', [ $dstart, $dend ]);
    }
}
