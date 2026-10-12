<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'description',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            if (! Schema::hasTable('site_settings')) {
                return $default;
            }
        } catch (\Throwable $e) {
            return $default;
        }

        return Cache::rememberForever("site_setting:{$key}", function () use ($key, $default) {
            try {
                $setting = static::where('key', $key)->first();
                if ($setting === null) {
                    return $default;
                }

                return match ($setting->type) {
                    'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                    'integer' => (int) $setting->value,
                    'json' => json_decode($setting->value ?? '[]', true),
                    default => $setting->value,
                };
            } catch (\Throwable $e) {
                return $default;
            }
        });
    }

    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'string', ?string $description = null): void
    {
        $stringValue = is_array($value) ? json_encode($value) : (is_bool($value) ? ($value ? '1' : '0') : (string) $value);

        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $stringValue,
                'group' => $group,
                'type' => $type,
                'description' => $description,
            ]
        );

        Cache::forget("site_setting:{$key}");
        Cache::forget('site_settings_all');
    }

    protected static function booted(): void
    {
        static::saved(function (SiteSetting $setting) {
            Cache::forget("site_setting:{$setting->key}");
            Cache::forget('site_settings_all');
        });

        static::deleted(function (SiteSetting $setting) {
            Cache::forget("site_setting:{$setting->key}");
            Cache::forget('site_settings_all');
        });
    }
}
