<?php

namespace futura\TwillGeo\Models;

use Illuminate\Database\Eloquent\Model;

class GeoStructuredData extends Model
{
    protected $fillable = [
        'model_id',
        'model_type',
        'structured_data',
    ];
}
