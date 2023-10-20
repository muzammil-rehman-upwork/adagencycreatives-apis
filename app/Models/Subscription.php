<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'price',
        'quantity',
        'quota_left',
        'ends_at',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'name', 'slug');
    }
}
