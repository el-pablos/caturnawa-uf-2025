<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    /**
     * Get setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        // Cast value based on type
        switch ($setting->type) {
            case 'boolean':
                return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $setting->value;
            case 'float':
                return (float) $setting->value;
            case 'json':
                return json_decode($setting->value, true);
            default:
                return $setting->value;
        }
    }

    /**
     * Set setting value by key
     */
    public static function set($key, $value, $type = 'string', $description = null)
    {
        // Convert value to string for storage
        if ($type === 'boolean') {
            $value = $value ? '1' : '0';
        } elseif ($type === 'json') {
            $value = json_encode($value);
        } else {
            $value = (string) $value;
        }

        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description,
            ]
        );
    }

    /**
     * Check if maintenance mode is enabled
     */
    public static function isMaintenanceMode()
    {
        return static::get('maintenance_mode', false);
    }

    /**
     * Check if registration is open
     */
    public static function isRegistrationOpen()
    {
        return static::get('registration_open', true);
    }

    /**
     * Get maintenance message
     */
    public static function getMaintenanceMessage()
    {
        return static::get('maintenance_message', 'Website sedang dalam pemeliharaan. Silahkan coba lagi nanti.');
    }

    /**
     * Get registration closed message
     */
    public static function getRegistrationClosedMessage()
    {
        return static::get('registration_closed_message', 'Pendaftaran sedang ditutup. Silahkan tunggu periode selanjutnya.');
    }
}
