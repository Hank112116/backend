<?php

namespace Backend\Model\Eloquent;

use Config;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Builder;


class Comment extends Eloquent
{

    protected $table = 'comments';
    protected $primaryKey = 'comment_id';
    public $timestamps = false; // not use created_at, updated_at
    public static $unguarded = true;

    protected $appends = ['image_urls'];

    public function publisher()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function expert()
    {
        return $this->belongsTo(User::class, 'profession_id', 'user_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function solution()
    {
        return $this->belongsTo(Solution::class, 'solution_id', 'solution_id');
    }

    public function threads()
    {
        return $this->hasMany(Comment::class, 'main_comment', 'comment_id')
            ->with(['publisher' => function ($query) {
                $query->addSelect(User::$partial);
            }]);
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function scopeQueryEagerLoad(Builder $query)
    {
        return $query
            ->with(['threads' => function ($query) {

                $query->orderBy('date_added', 'desc');
            }])->with(['publisher' => function ($query) {

                $query->addSelect(User::$partial);
            }]);
    }

    public function scopeQueryTopic(Builder $query)
    {
        return $query->where('main_comment', 0);
    }

    public function scopeQueryProfession(Builder $query)
    {
        return $query->where('profession_id', '!=', 0)
            ->with(['expert' => function ($query) {
                $query->addSelect(User::$partial);
            }]);
    }

    public function scopeQueryProject(Builder $query)
    {
        return $query->where('project_id', '!=', 0)
            ->with(['project' => function ($query) {
                $query->addSelect(Project::$partial);
            }]);
    }

    public function scopeQuerySolution(Builder $query)
    {
        return $query->where('solution_id', '!=', 0)
            ->with(['solution' => function ($query) {
                $query->addSelect(Solution::$partial);
            }]);
    }

    public function getImageUrlsAttribute()
    {
        if (!$this->images) {
            return [];
        }

        $urls = [];
        foreach (explode(',', $this->images) as $image) {
            $urls[] = Config::get('s3.thumb') . $image;
        }

        return $urls;
    }

    public function isTopic()
    {
        return $this->main_comment == 0;
    }
}
