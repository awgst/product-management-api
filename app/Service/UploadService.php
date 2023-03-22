<?php

namespace App\Service;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    /**
     * @var string
     */
    private $disk;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $extension;

    /**
     * Create instance of UploadService
     */
    public function __construct()
    {
        $this->disk = config('filesystems.default');
    }

    /**
     * Set disk
     * @param string $disk
     * @return self
     */
    public function disk(string $disk): self
    {
        $this->disk = $disk;
        return $this;
    }

    /**
     * Set filename
     * @param string $filename
     */
    public function as(string $filename): self
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * Set file extension
     * @param string $extension
     * @return self
     */
    public function extension(string $extension): self
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * Upload image
     * @param UploadedFile $file
     * @param string $path
     * @return array|null
     */
    public function upload(UploadedFile $file, string $path): ?array
    {
        try {
            // Generate file name
            $fileName = $file->getClientOriginalName();
            if (!is_null($this->filename)) {
                $extension = $this->extension ?? $file->getClientOriginalExtension();
                $fileName = $this->filename.".".$extension;
            }

            // Delete old file if exists
            $this->delete($path.$fileName);

            // Upload file
            $uploaded = Storage::disk($this->disk)
                            ->putFileAs($path, $file, $fileName);
            
            if ($uploaded === false) {
                return null;
            }

            return [
                'file' => $fileName,
                'url' => Storage::disk($this->disk)
                                ->url($path.$fileName)
            ];
        } catch (\Exception $e) {
            Log::channel('exception')->error('[service] upload: '. $e->getMessage());
            return null;
        }
    }

    /**
     * Delete image
     * @param string $path
     * @return bool
     */
     public function delete(string $path): bool
     {
        try {
            $deleted = false;
            if (Storage::disk($this->disk)->exists($path)) {
                $deleted = Storage::disk($this->disk)->delete($path);
            }

            return $deleted;
        } catch (\Exception $e) {
            Log::channel('exception')->error('[service] delete: '. $e->getMessage());
            return false;
        }
     }
     
}