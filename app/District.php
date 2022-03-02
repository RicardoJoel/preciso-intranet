<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ubigeo extends Model
{
    use SoftDeletes;

    protected $table = 'ubigeos';
    
    protected $fillable = [
        'name'
    ];

    public function users()
    {
    	return $this->hasMany(User::class);
    }

    public function customers()
    {
    	return $this->hasMany(Customer::class);
    }
}
