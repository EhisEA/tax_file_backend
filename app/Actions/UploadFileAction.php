<?php

namespace App\Actions;

use App\Exceptions\FailedToUploadException;
use App\Models\File;
use Cloudinary\Api\Exception\ApiError;
use Illuminate\Support\Facades\Log;

class UploadFileAction
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @throws FailedToUploadException* @return File
     */
    public function execute(string|false|null $path): File
    {
        if (! $path) {
            throw new FailedToUploadException;
        }

        try {
            Log::info($path);
            $file_upload = cloudinary()->upload($path);

            return File::query()->create([
                'file_url' => $file_upload->getSecurePath(),
                'file_name' => $file_upload->getFileName(),
                'file_type' => $file_upload->getFileType(),
                'file_size' => $file_upload->getSize(),
            ]);
        } catch (ApiError $exception) {
            throw new FailedToUploadException(previous: $exception);
        }
    }
}
