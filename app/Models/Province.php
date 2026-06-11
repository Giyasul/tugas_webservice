<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use SoftDeletes;

    protected $table = 'province';

    protected $primaryKey = 'province_id';

    protected $fillable = ['province_code', 'province_name'];

    public function cities()
    {
        return $this->hasMany(City::class, 'province_id', 'province_id');
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
        'deleted_at' => 'datetime:Y-m-d',
    ];
}
