<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    //
    public $timestamps = true;
    
    protected $fillable = [
        'kit_id',
        'sample_id',
        'lab_id',
        'sample_registered_date',
        'cobas_result',
        'cobas_analysis_date',
        'luminex_result',
        'luminex_analysis_date',
        'rtpcr_result',
        'rtpcr_analysis_date',
        'final_reporting_result',
        'reporting_date',
        'reported_via',
        'created_at',
        'updated_at'
    ];
    
    
    /**
     * Get the kit for the sample.
     */
    public function kit()
    {
        return $this->belongsTo('App\Models\Kit');
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
