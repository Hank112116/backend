<?php

namespace Backend\Model\Eloquent;

use DB;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Backend\Model\Eloquent\User;

class Inbox extends Eloquent
{

    protected $table = 'message_conversation';
    protected $primaryKey = 'message_id';
    //protected $appends = ['image_url', 'full_name'];

    public $timestamps = false; // not use created_at, updated_at

    public static $unguarded = true;

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function sender()
    {
        return $this->hasOne(User::class, 'user_id', 'sender_id');
    }

    public function receiver()
    {
        return $this->hasOne(User::class, 'user_id', 'receiver_id');
    }

    public function threads()
    {
        return $this->hasMany(Inbox::class, 'reply_message_id', 'message_id')
            ->with(['sender' => function ($query) {

                $query->addSelect(User::$partial);

            }])->with(['receiver' => function ($query) {

                $query->addSelect(User::$partial);

            }]);;
    }

    public function scopeQueryEagerLoad(Builder $query)
    {
        return $query
            ->with(['threads' => function ($query) {

                $query->where('message_id', '!=', \DB::raw('reply_message_id'))
                    ->orderBy('date_added', 'desc');

            }])->with(['sender' => function ($query) {

                $query->addSelect(User::$partial);

            }])->with(['receiver' => function ($query) {

                $query->addSelect(User::$partial);

            }]);;
    }

    public function scopeQueryTopic(Builder $q)
    {
        $q->where(function (Builder $query) {
            $query->where('reply_message_id', 0)
                ->orWhere('message_id', '=', DB::raw('reply_message_id'));
        });
    }

    public function scopeQueryResponses(Builder $q)
    {
        $q->where('reply_message_id', '!=', 0);
    }

    public function scopeBySender(Builder $q, $sender_id)
    {
        $q->where('sender_id', $sender_id);
    }

    public function scopeByReceiver(Builder $q, $receiver_id)
    {
        $q->where('receiver_id', $receiver_id);
    }

}
