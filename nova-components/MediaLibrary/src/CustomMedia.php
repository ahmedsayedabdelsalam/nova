<?php

namespace Code95\MediaLibrary;

use Carbon\Carbon;
use Code95\MediaLibrary\Traits\HasDynamicRelation;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\Models\Media as BaseMedia;

class CustomMedia extends BaseMedia
{
    use HasDynamicRelation;

    protected $table = 'media';

    /**
     * filterName function
     * 
     * @param Builder $query
     * @return void
     */
    public function scopeFilterName(Builder $query, $value = null)
    {
        if ($value) $query->where("name", "LIKE", "%{$value}%");
    }

    /**
     * filterType function
     *
     * @param Builder $query
     * @return void
     */
    public function scopeFilterType(Builder $query, $value = null)
    {
        if ($value) $query->where("mime_type", "LIKE", "%{$value}%");
    }

    /**
     * filterUploadedBy function
     *
     * @param Builder $query
     * @return void
     */
    public function scopeFilterUploadedBy(Builder $query, $value = null)
    {
        if ($value) $query->where("custom_properties->user->id", $value);
    }

    /**
     * filterType function
     *
     * @param Builder $query
     * @return void
     */
    public function scopeFilterDate(Builder $query, $value = null)
    {
        if ($value) $query->whereDate("created_at", Carbon::parse($value));
    }

    /**
     * filterStatus function
     *
     * @param Builder $query
     * @return void
     */
    public function scopeFilterStatus(Builder $query, $value = null)
    {
        if ($value) {
            if ($value == 'attached') {
                $query->whereHas('users')
                    ->orWhereHas('posts');
            } else {
                $query->whereDoesntHave('users')
                    ->WhereDoesntHave('posts');
            }
        }
    }
}
