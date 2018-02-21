<?php

namespace App\Storage\Form;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Form extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public $fillable = [
        'title',
        'guid',
        'description',
    ];

    protected $casts = [
        'title' => 'string',
        'guid' => 'string',
        'description' => 'string',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function steps()
    {
        return $this->hasMany(FormStep::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->steps()->with('questions.answers');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'form_categories');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(FormAnswer::class, 'form_id')
            ->where('user_id', auth()->id());
    }
}
