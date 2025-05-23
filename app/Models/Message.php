<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'category_id',
    ];

    protected $appends = ['is_new'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getIsNewAttribute()
    {
        return $this->created_at->diffInDays(Carbon::now()) < 7;
    }
}
