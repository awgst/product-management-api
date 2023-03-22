<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static self as(string $filename)
 * @method static self disk(string $disk)
 * @method static self extension(string $extension)
 * @method static array|null upload(\Illuminate\Http\UploadedFile $file, string $path)
 * @method static bool delete(string $path)
 * 
 * @see \App\Service\UploadService
 */

class Upload extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \App\Service\UploadService::class;
    }
}