<?php namespace Backend\Http\Controllers;

use Backend\Http\Controllers\Controller;

class ImageUploadController extends Controller
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
        if (!Input::hasFile('file')) {
            return;
        }

        $uploader = ImageUp::instance();
        if ($image = $uploader->uploadImage(Input::file('file'))) {
            return response()->json(['status' => 'success', 'filelink' => $uploader->getOriginImage()]);
        }

        return response()->json(['status' => 'fail', 'filelink' => $uploader->err]);
    }

}
