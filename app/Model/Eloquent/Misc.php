<?php namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Misc extends Model
{

    protected $table = 'misc';
    protected $primaryKey = 'misc_id';
    public $timestamps = false; // not use created_at, updated_at

    protected $fillable = ['misc_name', 'misc_value'];

    public function insertOrUpdate($key, $value)
    {
        $row = $this->where('misc_name', $key)->first();
        if (!$row) {
            $this->create([
                'misc_name' => $key,
                'misc_value' => $value
            ]);
        } else {
            $row->misc_value = $value;
            $row->save();
        }
    }
}
