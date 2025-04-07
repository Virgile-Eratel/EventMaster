<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'banner_image',
        'description',
        'event_date',
        'address',
        'latitude',
        'longitude',
        'status',
        'max_participants',
        'organisateur_id',
        'price',
        'is_free',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'is_free' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function organisateur()
    {
        return $this->belongsTo(User::class, 'organisateur_id');
    }
    public function clients()
    {
        return $this->belongsToMany(User::class, 'event_user')
            ->where('role', 'client');
    }
    public function updateStatus()
    {
        if ($this->clients()->count() >= $this->max_participants) {
            $this->update(['status' => 'complet']);
        } else {
            $this->update(['status' => 'remplissage_en_cours']);
        }
    }
}
