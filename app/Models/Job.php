<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'address_id',
        'category_id',
        'title',
        'agency_name',
        'description',
        'employement_type',
        'industry_experience',
        'media_experience',
        'salary_range',
        'years_of_experience',
        'apply_type',
        'external_link',
        'status',
        'is_remote',
        'is_hybrid',
        'is_onsite',
        'is_featured',
        'expired_at',
        'is_urgent',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    const STATUSES = [
        'PENDING' => 0,
        'APPROVED' => 1,
        'REJECTED' => 2,
        'EXPIRED' => 3,
        'FILLED' => 4,
        'DRAFT' => 5,
        'PUBLISHED' => 6,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function attachment()
    {
        return $this->hasOne(Attachment::class, 'resource_id');
    }

    public function scopeUserId(Builder $query, $user_id): Builder
    {
        $user = User::where('uuid', $user_id)->firstOrFail();

        return $query->where('user_id', $user->id);
    }

    public function scopeCategoryId(Builder $query, $category_id): Builder
    {
        $category = Category::where('uuid', $category_id)->firstOrFail();

        return $query->where('category_id', $category->id);
    }

    public function scopeCountry(Builder $query, $country): Builder
    {
        $country_ids = Address::where('country', $country)->pluck('id');

        return $query->whereIn('address_id', $country_ids);
    }

    public function scopeState(Builder $query, $state): Builder
    {
        $state_ids = Address::where('state', $state)->pluck('id');

        return $query->whereIn('address_id', $state_ids);
    }

    public function scopeIndustryExperience(Builder $query, $industries): Builder
    {
        $industries = Industry::whereIn('uuid', $industries)->pluck('id');

        return $query->whereIn('address_id', $state_ids);
    }

    public function getStatusAttribute($value)
    {
        switch ($value) {
            case Job::STATUSES['PENDING']:
                return 'pending';
            case Job::STATUSES['APPROVED']:
                return 'approved';
            case Job::STATUSES['REJECTED']:
                return 'rejected';
            case Job::STATUSES['EXPIRED']:
                return 'expired';
            case Job::STATUSES['FILLED']:
                return 'filled';
            case Job::STATUSES['DRAFT']:
                return 'draft';
            case Job::STATUSES['PUBLISHED']:
                return 'published';

            default:
                return null;
        }
    }

    public function setStatusAttribute($value)
    {
        switch ($value) {
            case 'approved':
                $this->attributes['status'] = Job::STATUSES['APPROVED'];
                break;
            case 'rejected':
                $this->attributes['status'] = Job::STATUSES['REJECTED'];
                break;
            case 'expired':
                $this->attributes['status'] = Job::STATUSES['EXPIRED'];
                break;
            case 'filled':
                $this->attributes['status'] = Job::STATUSES['FILLED'];
                break;
            case 'draft':
                $this->attributes['status'] = Job::STATUSES['DRAFT'];
                break;
            case 'published':
                $this->attributes['status'] = Job::STATUSES['PUBLISHED'];
                break;
            default:
                $this->attributes['status'] = Job::STATUSES['PENDING'];
                break;
        }
    }

    protected static function booted()
    {
        static::created(function () {
            Cache::forget('dashboard_stats_cache');
        });

        static::updated(function () {
            Cache::forget('dashboard_stats_cache');
        });

        static::deleted(function () {
            Cache::forget('dashboard_stats_cache');
        });
    }
}
