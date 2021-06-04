<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    public $timestamps = true;
    
    protected $fillable = [
        'user_id',
        'status',
        'order_created_by',
        'created_at',
        'updated_at'
    ];
    
    
    /**
     * Get the user for the order.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    
    
    /**
     * Get the kit associated with the order.
     */
    public function kit()
    {
        return $this->hasOne('App\Models\Kit');
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
