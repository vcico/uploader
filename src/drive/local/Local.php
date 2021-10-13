<?php


namespace Vcico\Uploader\drive\local;


use Vcico\Uploader\BaseConfig;
use Vcico\Uploader\BaseDrive;
use Vcico\Uploader\DriveContract;
use Vcico\Uploader\exception\ConfigItemNotExistException;
use Vcico\Uploader\exception\MakeDirException;
use Vcico\Uploader\exception\UploadFailException;

final class Local extends BaseDrive implements DriveContract
{

    public function __construct(array $items)
    {
        if(!isset($items['drive']['basePath'])){
            throw new ConfigItemNotExistException('Missing setting item : basePath ');
        }
        parent::__construct($items);
    }

    public function config(array $items):BaseConfig{
        return  new Config($items);
    }

    public function uploadSingle($bucket,array $info):string{
        $path = $this->makeDir($bucket,$info['name']);
        $name = $this->fileName($info['name']);
        $f = $path.'/'.$name;
        if(!move_uploaded_file($info['tmp_name'],$f)){
            throw new UploadFailException('Temp file move fail: '.$info['name']);
        }
        $url_func = $this->configure->bucket($bucket)->urlFunc;
        return $url_func($f);
    }

    public function uploadMulti($bucket,array $info):array{
        $result = [];
        $count = count($info['type']);
        for($i=0;$i<$count;$i++){
            $result[] = $this->uploadSingle( $bucket,[
                'type'=> $info['type'][$i],
                'name' => $info['name'][$i],
                'tmp_name' => $info['tmp_name'][$i],
                'error' => $info['error'][$i],
                'size' => $info['size'][$i]
            ]);
        }
        return $result;
    }

    // 创建文件夹
    protected function makeDir($bucket,$origin_name):string{
        switch ($this->configure->bucket($bucket)->folderMode){
            case Config::FOLDER_MODE_DATE:
                $d = sprintf('%s/%s/%s',$this->configure->bucket($bucket)->basePath,$bucket,date('Y/m'));
                break;
            case Config::FOLDER_MODE_HASH:
                $hash = hash('sha256',$origin_name);
                $d = sprintf('%s/%s/%s',$this->configure->bucket($bucket)->basePath,$bucket,substr($hash,0,3));
                break;
            default:
                $d = sprintf('%s/%s',$this->configure->bucket($bucket)->basePath,$bucket);
                break;
        }
        if(!is_dir($d)){
            if (!mkdir($d,0777, true)){
                throw new MakeDirException('mkdir fail: '.$d);
            }
        }
        return $d;
    }

    // 生成文件名 $fileInfo['name']
    protected function fileName($origin_name):string{
        return sprintf('%s%s.%s',date('Ymd'),uniqid(microtime(true),true),pathinfo($origin_name,PATHINFO_EXTENSION));
    }


}
