<?php namespace Backend\Http\Controllers;

use ImageUp;

class ImageUploadController extends BaseController
{

    /**
     * Upload Editor Image
     * Ajax post by update.js redactor
     * Save both origin and thumb image
     *
     * @route  post /upload-editor-image
     * @vendor aws/aws-sdk-php-laravel
     * @return success ['status' => 'success', 'filelink' => image_path]
     * @return fail    ['status' => 'fail',    'msg' => fail_msg]
     **/
    public function index()
    {
        if (!$this->request->hasFile('file')) {
            return;
        }

        $uploader = ImageUp::instance();
        if ($image = $uploader->uploadImage($this->request->file('file'))) {
            return response()->json(['status' => 'success', 'filelink' => $uploader->getOriginImage()]);
        }

        return response()->json(['error' => true]);
    }
}
