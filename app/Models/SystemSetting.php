<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model SystemSetting untuk mengelola pengaturan sistem
 * 
 * Kelas ini menangani pengaturan seperti maintenance mode,
 * registrasi terbuka/tutup, dan konfigurasi lainnya
 */
class SystemSetting extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara mass assignment
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'string',
    ];

    /**
     * Mendapatkan nilai setting berdasarkan key
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return self::castValue($setting->value, $setting->type);
    }

    /**
     * Mengatur nilai setting berdasarkan key
     * 
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string|null $description
     * @return void
     */
    public static function set($key, $value, $type = 'string', $description = null)
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            $setting->update([
                'value' => self::prepareValue($value, $type),
                'type' => $type,
                'description' => $description ?? $setting->description,
            ]);
        } else {
            self::create([
                'key' => $key,
                'value' => self::prepareValue($value, $type),
                'type' => $type,
                'description' => $description,
            ]);
        }
    }

    /**
     * Menghapus setting berdasarkan key
     * 
     * @param string $key
     * @return bool
     */
    public static function forget($key)
    {
        return self::where('key', $key)->delete();
    }

    /**
     * Cek apakah setting ada
     * 
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        return self::where('key', $key)->exists();
    }

    /**
     * Mendapatkan semua setting sebagai array
     * 
     * @return array
     */
    public static function all()
    {
        $settings = self::get();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting->key] = self::castValue($setting->value, $setting->type);
        }
        
        return $result;
    }

    /**
     * Cast value berdasarkan type
     * 
     * @param string $value
     * @param string $type
     * @return mixed
     */
    protected static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'json':
                return json_decode($value, true);
            case 'array':
                return json_decode($value, true) ?? [];
            default:
                return $value;
        }
    }

    /**
     * Prepare value untuk disimpan ke database
     * 
     * @param mixed $value
     * @param string $type
     * @return string
     */
    protected static function prepareValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return $value ? 'true' : 'false';
            case 'json':
            case 'array':
                return json_encode($value);
            default:
                return (string) $value;
        }
    }

    /**
     * Shortcut untuk maintenance mode
     * 
     * @return bool
     */
    public static function isMaintenanceMode()
    {
        return self::get('maintenance_mode', false);
    }

    /**
     * Shortcut untuk registration open
     * 
     * @return bool
     */
    public static function isRegistrationOpen()
    {
        return self::get('registration_open', true);
    }

    /**
     * Shortcut untuk maintenance message
     * 
     * @return string
     */
    public static function getMaintenanceMessage()
    {
        return self::get('maintenance_message', 'Website sedang dalam pemeliharaan.');
    }

    /**
     * Shortcut untuk registration closed message
     * 
     * @return string
     */
    public static function getRegistrationClosedMessage()
    {
        return self::get('registration_closed_message', 'Pendaftaran sedang ditutup.');
    }
}
