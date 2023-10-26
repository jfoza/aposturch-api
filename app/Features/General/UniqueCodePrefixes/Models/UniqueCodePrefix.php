<?php

namespace App\Features\General\UniqueCodePrefixes\Models;

use App\Base\Infra\Models\Register;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class UniqueCodePrefix extends Register
{
    use HasFactory;

    const ID = 'id';
    const PREFIX = 'prefix';
    const ACTIVE = 'active';
    const CREATED_AT = 'created_at';

    protected $table = 'general.unique_code_prefixes';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::PREFIX,
        self::ACTIVE,
    ];
}
