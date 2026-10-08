<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = static::query()
            ->where('key', $key)
            ->first();

        if (! $setting) {
            return $default;
        }

        $decoded = json_decode($setting->value, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $setting->value;
    }

    public static function setValue(string $key, mixed $value, string $group = 'general', ?string $type = null): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            [
                'value' => json_encode($value),
                'type' => $type ?? get_debug_type($value),
                'group' => $group,
            ]
        );
    }

    public static function bool(string $key, bool $default = false): bool
    {
        return (bool) static::getValue($key, $default);
    }

    public static function string(string $key, string $default = ''): string
    {
        return (string) static::getValue($key, $default);
    }
}
