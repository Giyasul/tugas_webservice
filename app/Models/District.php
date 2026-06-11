<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class District extends Model
{
    use SoftDeletes;

    protected $table = 'district';

    protected $primaryKey = 'district_id';

    protected $fillable = ['city_id', 'district_code', 'district_name'];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
        'deleted_at' => 'datetime:Y-m-d',
    ];
}
