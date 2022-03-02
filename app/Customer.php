<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'customers';
    
    protected $fillable = [
        'business_id', 'ubigeo_id', 'ruc', 'name', 'code', 'alias', 'address'
    ];

    public function getNameCodeAttribute() {
        return $this->name.' ('.$this->code.')';
    }
    
    public function business()
    {
    	return $this->belongsTo(Business::class);
    }

    public function ubigeo()
    {
    	return $this->belongsTo(Ubigeo::class);
    }

    public function contacts()
    {
    	return $this->hasMany(Contact::class);
    }
    
    public function proposals()
    {
    	return $this->hasMany(Proposal::class);
    }
    
    public function projects()
    {
    	return $this->hasMany(Project::class);
    }
}
