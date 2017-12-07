<?php
/**
 * Created by PhpStorm.
 * User: jasonswords
 * Date: 07/12/2017
 * Time: 09:57
 */

namespace Itb;


class FileUpload
{
    public function uploadImage(){
        $storage = new \Upload\Storage\FileSystem(__DIR__ .'/../web/images');
        $file = new \Upload\File('upload', $storage);

        $file->addValidations(array(
            new \Upload\Validation\Mimetype(array('image/png', 'image/gif', 'image/jpg', 'image/jpeg')),
            new \Upload\Validation\Size('5M')
        ));
        try {
            $file->upload();
        } catch (\Exception $e) {

        }
        return $file->getNameWithExtension();
    }

    public function fileWasUploaded()
    {
        if(!isset($_FILES['upload']['error']))
            return false;

        if(UPLOAD_ERR_NO_FILE == $_FILES['upload']['error'])
            return false;

        return true;
    }

}