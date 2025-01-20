<?php

namespace App\Actions;

use App\Exceptions\FailedToUploadException;
use App\Models\File;
use Cloudinary\Api\Exception\ApiError;

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
     * @throws FailedToUploadException
     */
    public function execute(string|false $path) {
        if ($path === false) {
            throw new FailedToUploadException();
        }

        try {
            $file_upload = cloudinary()->upload($path);
            return File::query()->create([
                'file_url' =>  $file_upload->getSecurePath(),
                'file_name' => $file_upload->getFileName(),
                'file_type' => $file_upload->getFileType(),
                'file_size' => $file_upload->getSize(),
            ]);
        } catch (ApiError $exception) {
            throw new FailedToUploadException(previous: $exception);
        }
    }
}
