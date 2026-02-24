<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 9/9/2025
 * @time 9:42 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'options',
        'is_active',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options'   => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Automatically decrypt the value when retrieving a mail password setting.
     */
    public function getValueAttribute($value)
    {
        if ($this->key === 'mail_password' && $value !== null && $value !== '') {
            try {
                return decrypt($value);
            } catch (\Exception $e) {
                // in case the value wasn't encrypted already
                return $value;
            }
        }

        return $value;
    }

    /**
     * Encrypt the mail password when saving to database.
     */
    public function setValueAttribute($value)
    {
        if ($this->key === 'mail_password' && $value !== null && $value !== '') {
            $this->attributes['value'] = encrypt($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }
}
