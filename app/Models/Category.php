<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Message;
use App\Models\Affirmation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'id',
        'name',
        'color',
        'backgroundColor',
        'description',
    ];

    protected $appends = [
        'category_icon',
    ];

    public function getCategoryIconAttribute()
    {
        $media = $this->getFirstMedia('category_icons');
        if ($media) {
            return [
                'original' =>$media->getUrl('large'),
                'thumbnail' => $media->getUrl('thumb'),
                'large' => $media->getUrl('large'),
                'name' => $media->file_name,
            ];
        }
        return null;
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function affirmations(): HasMany
    {
        return $this->hasMany(Affirmation::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('category_icons')
            ->singleFile()
            ->useDisk('media')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100)
            ->sharpen(10)
            ->format('png')
            ->optimize()
            ->nonQueued()
            ->performOnCollections('category_icons');

        $this->addMediaConversion('medium')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->format('png')
            ->optimize()
            ->nonQueued()
            ->performOnCollections('category_icons');

        $this->addMediaConversion('large')
            ->width(600)
            ->height(600)
            ->format('png')
            ->optimize()
            ->nonQueued()
            ->performOnCollections('category_icons');
    }
}
