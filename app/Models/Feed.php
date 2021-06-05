<?php

namespace App\Models;

use App\Models\User;
use App\Models\FeedResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feed extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'url',
        'is_notify'
    ];

    public function results()
    {
        return $this->hasMany(FeedResult::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('filter_by_user', function (Builder $builder) {
            $builder->where('user_id', auth()->id());
        });
    }

    public function getIsNotifyTextAttribute()
    {
        return $this->is_notify ? 'Yes' : 'No';
    }
}
