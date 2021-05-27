<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class User extends Model
{
    //
    public $timestamps = true;
    
    protected $fillable = [
        'first_name',
        'last_name',
        'pnr',
        'phonenumber',
        //'roles',
        'street',
        'zipcode',
        'city',
        'country',
        'consent',
        'created_at',
        'updated_at'
    ];
    
    
    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }
    
    
    /**
     * Get the kits for the user.
     */
    public function kits()
    {
        return $this->hasMany('App\Models\Kit');
    }
    
    
    /**
     * Get all of the samples for the user.
     */
    public function samples()
    {
        return $this->hasManyThrough(Sample::class, Kit::class);
    }
    
    
    /**
     * Get the formatted created_at timestamp.
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedAtAttribute($value) {
        return Carbon::parse($value)->timezone('Europe/Stockholm')->toDateTimeString();
    }
    
    /**
     * Get the formatted updated_at timestamp.
     *
     * @param  string  $value
     * @return string
     */
    public function getUpdatedAtAttribute($value) {
        return Carbon::parse($value)->timezone('Europe/Stockholm')->toDateTimeString();
    }
   
}
