<?php
namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class LowPrioritySolution extends Eloquent
{
    protected $table = 'low_priority_solution';
    protected $primaryKey = 'solution_id';
    public $timestamps = false;
    public static $unguarded = true;

    public function getKeyName()
    {
        return $this->primaryKey;
    }

    public function solution()
    {
        return $this->belongsTo(Solution::class, 'solution_id', 'solution_id');
    }
}
