<?php

namespace Plugins\Marketing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class MarketingList extends Model
{
    protected $table = 'marketing_lists';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'contact_count',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($list) {
            if (empty($list->slug)) {
                $list->slug = Str::slug($list->name);
            }
        });
    }

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(MarketingContact::class, 'marketing_contact_list', 'list_id', 'contact_id');
    }

    public function activeContacts(): BelongsToMany
    {
        return $this->contacts()->where('status', 'active');
    }

    public function updateContactCount(): void
    {
        $this->update(['contact_count' => $this->contacts()->count()]);
    }

    public function addContact(MarketingContact $contact): void
    {
        if (!$this->contacts()->where('contact_id', $contact->id)->exists()) {
            $this->contacts()->attach($contact->id);
            $this->increment('contact_count');
        }
    }

    public function removeContact(MarketingContact $contact): void
    {
        $this->contacts()->detach($contact->id);
        $this->decrement('contact_count');
    }
}
