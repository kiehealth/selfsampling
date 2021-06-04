<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Kit extends Model
{
    //
    public $timestamps = true;
    
    protected $fillable = [
        'order_id',
        'user_id',
        'sample_id',
        'barcode',
        'kit_dispatched_date',
        'sample_received_date',
        'created_at',
        'updated_at'
    ];
    
    
    /**
     * Get the user for the kit.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    
    
    /**
     * Get the order for the kit.
     */
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }
    
    
    /**
     * Get the sample associated with the kit.
     */
    public function sample()
    {
        return $this->hasOne('App\Models\Sample');
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
