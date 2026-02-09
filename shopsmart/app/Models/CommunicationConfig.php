<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationConfig extends Model
{
    protected $fillable = [
        'name',
        'type',
        'is_primary',
        'is_active',
        'config',
        'description',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
        'config' => 'array',
    ];

    /**
     * Set as primary configuration
     */
    public function setAsPrimary()
    {
        // Remove primary status from other configs of the same type
        static::where('type', $this->type)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        $this->update(['is_primary' => true]);
    }

    /**
     * Get primary configuration by type
     */
    public static function getPrimary($type)
    {
        return static::where('type', $type)
            ->where('is_primary', true)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get all active configurations by type
     */
    public static function getActive($type)
    {
        return static::where('type', $type)
            ->where('is_active', true)
            ->orderBy('is_primary', 'desc')
            ->orderBy('name', 'asc')
            ->get();
    }
}
