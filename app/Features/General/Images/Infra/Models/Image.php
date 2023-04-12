<?php

namespace App\Features\General\Images\Infra\Models;

use App\Features\Base\Infra\Models\Register;
use App\Modules\Members\Church\Models\Church;
use App\Modules\Members\ChurchesImages\Models\ChurchImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Image extends Register
{
    use HasFactory;

    const ID = 'id';
    const PATH = 'path';
    const TYPE = 'type';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'general.images';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::PATH,
        self::TYPE,
    ];

    public function church(): BelongsToMany
    {
        return $this->belongsToMany(
            Church::class,
            ChurchImage::class,
            ChurchImage::IMAGE_ID,
            ChurchImage::CHURCH_ID,
        );
    }
}
