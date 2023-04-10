<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description',
    ];

    public function colours(): HasMany
    {
        return $this->hasMany(Colour::class);
    }

    protected static function boot()
    {
        parent::boot();

        self::deleting(function ($item) {
            $item->colours()->each(function ($colour) {
                $colour->delete();
            });
        });
    }
}
