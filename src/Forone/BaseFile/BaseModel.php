<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $table = 'BaseTable';
    protected $guarded = [];
    public $timestamps = true;

}
