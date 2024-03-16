<?php

namespace App\Http\Controllers\API\Device;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Device\FileUploadAPIRequest;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class FileUploadController extends AppBaseController
{
    /**
     * @param  FileUploadAPIRequest $request
     *
     * @return  JsonResponse
     */
    public function upload(FileUploadAPIRequest $request): JsonResponse
    {
        $files = $request->file('files');
        foreach ($files as $file){
            $user = User::first();
            $user->addMedia($file)->toMediaCollection('default',config('app.media_disc'));
        }

        return $this->successResponse('File upload successfully.');
    }
}
