<?php

namespace App\Base\Infra\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Register extends Model
{
    public $hidden = [
        'pivot',
        'creator_id',
        'updater_id'
    ];

    protected static function booted()
    {
        if($id = Auth::id()) {
            static::creating(function ($register) {
                $register->creator_id = $register->creator_id ?? $id ?? null;
            });
            static::updating(function ($register) {
                $register->updater_id = $register->updater_id ?? $id ?? null;
            });
        }
    }

    public static function tableName(): string
    {
        return (new static)->getTable();
    }

    public static function tableField(string $field): string
    {
        return self::tableName().'.'.$field;
    }
}
