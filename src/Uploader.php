<?php


namespace Vcico\Uploader;

use Vcico\Uploader\drive\local\Local;
use Vcico\Uploader\exception\DriveNotExistException;
use Vcico\Uploader\exception\FileSpecException;
use Vcico\Uploader\exception\UploadFailException;


class Uploader
{

    /* @var DriveContract */
    public $drive;

    /* @var BaseConfig */
    protected $config;

    public function __construct(array $configItems)
    {
        $driveClass = $configItems['driveClass']??Local::class;
        try{
            $this->drive = new $driveClass($configItems);
            if (!$this->drive instanceof DriveContract){
                throw new DriveNotExistException("[$driveClass] is not  uploader drive ");
            }
        }catch (\Exception $e){
            throw new DriveNotExistException("uploader drive [$driveClass] is not exist ");
        }
    }

    // $_FILES[?] 单个上传文件
    public function UploadSingle ($bucket, array $info):string{
        $this->config = $this->drive->configure->bucket($bucket);
        $this->verifySingle($info);
        return $this->drive->uploadSingle($bucket,$info);
    }

    // $_FILES[?] 多个上传文件
    public function UploadMulti ($bucket, array $filesInfo):array{
        $this->config = $this->drive->configure->bucket($bucket);
        $this->verifyMulti($filesInfo);
        return $this->drive->uploadMulti($bucket,$filesInfo);
    }

    protected function verifySingle(array $fileInfo){
        if ($fileInfo['error']){
            throw new UploadFailException('upload file fail '.$fileInfo['name']);
        }
        if($this->config->mimeType){
            if(!in_array($fileInfo['type'],$this->config->mimeType)){
                throw new FileSpecException(" file {$fileInfo['name']} mime type {$fileInfo['type']} is not allow ");
            }
        }
        if($fileInfo['size'] > $this->config->maxSize){
            throw new FileSpecException(" The file {$fileInfo['name']} size {$fileInfo['size']} exceeds the maximum limit [{$this->config->maxSize}] ");
        }
        if($this->config->suffix){
            $ext = pathinfo($fileInfo['name'],PATHINFO_EXTENSION);
            if(!in_array( $ext,$this->config->suffix)){
                throw new FileSpecException(" file {$fileInfo['name']} extension $ext is not allow ");
            }
        }
    }

    protected function verifyMulti(array $info){
        $count = count($info['type']);
        for($i=0;$i<$count;$i++){
            $this->verifySingle([
                'type' => $info['type'][$i],
                'name' => $info['name'][$i],
                'tmp_name' => $info['tmp_name'][$i],
                'error' => $info['error'][$i],
                'size' => $info['size'][$i]
            ]);
        }
    }
}
