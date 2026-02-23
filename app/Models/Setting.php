<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'display_name',
        'instructions',
        'is_encrypted'
    ];

    /**
     * Get the value of the setting.
     * 
     * @param string $value
     * @return mixed
     */
    public function getValueAttribute($value)
    {
        if ($this->is_encrypted && !empty($value)) {
            try {
                $value = Crypt::decryptString($value);
            }
            catch (\Exception $e) {
            // Return undecrypted if decryption fails (e.g. key changed)
            }
        }

        if ($this->type === 'boolean') {
            return (bool)$value;
        }

        if ($this->type === 'json' && !empty($value)) {
            return json_decode($value, true);
        }

        return $value;
    }

    /**
     * Set the value of the setting.
     * 
     * @param mixed $value
     */
    public function setValueAttribute($value)
    {
        if ($this->type === 'json' && is_array($value)) {
            $value = json_encode($value);
        }

        if ($this->is_encrypted && !empty($value)) {
            $value = Crypt::encryptString($value);
        }

        $this->attributes['value'] = $value;
    }
}
