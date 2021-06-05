<?php

namespace App\Models;

use App\Models\Feed;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeedResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'feed_id',
        'link',
        'title'
    ];

    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }
}
