<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class DiningTable extends Model
{
    protected $fillable = [
        'table_number',
        'name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (DiningTable $table) {
            if (empty($table->qr_token)) {
                $table->qr_token = Str::random(64);
            }
        });
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
