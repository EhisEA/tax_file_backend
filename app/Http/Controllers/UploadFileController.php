<?php

namespace App\Http\Controllers;

use App\Actions\UploadFileAction;
use App\Exceptions\FailedToUploadException;
use App\Http\Resources\FileResource;
use Illuminate\Http\Request;

class UploadFileController extends Controller
{
    /**
     * @throws FailedToUploadException
     */
    public function __invoke(
        Request $request,
        UploadFileAction $action
    ): FileResource {
        $request->validate([
            "file" => ["required", "file"],
        ]);

        $file = $action->execute($request->file("file"));

        return new FileResource($file);
    }
}
