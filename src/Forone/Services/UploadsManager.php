<?php

namespace Forone\Services;

use Illuminate\Http\UploadedFile;

class UploadsManager
{
    protected $uploadPath;
    protected $file;

    public function __construct (UploadedFile $file , $uploadPath)
    {
        $this->file = $file;
        if(!$file->isValid()){
            return false;
        }
        $this->uploadPath = $uploadPath;
    }


    public function upload()
    {
        if(!$this->uploadPath)
        {
            return false;
        }

        $fileFullName = $this->getClientName() . '.' . $this->getEntension();

        $res = $this->file->move($this->uploadPath , $fileFullName);
        return [
            'status'   => 200,
            'name'     => $res->getFileName(),
            'realPath' => $res->getRealPath(),
            'mimeType' => $res->getMimeType(),
            'size'     => $res->getSize(),
        ];

    }

    /*
     * 加密文件名
     */
    public function getClientName()
    {
        return md5(date('ymdhis') . $this->file->getClientOriginalName());
    }

    /*
     * 文件拓展名
     */
    public function getEntension()
    {
        return $this->file->getClientOriginalExtension();
    }
}