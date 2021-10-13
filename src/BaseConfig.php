<?php


namespace Vcico\Uploader;


use Vcico\Uploader\exception\BucketNotExistException;
use Vcico\Uploader\exception\ConfigItemNotExistException;

abstract class BaseConfig
{

    protected $items;

    protected $bucketConfig = [];

    public $maxSize = 2097152; // 默认2M的字节数

//    public $maxWidth = 1024;
//    public $maxHeight = 1024;

    public $suffix = [
        'jpg' ,'jpeg', 'png', 'gif', 'bmp', 'ico'
    ];

    public $mimeType = [
        'image/png',
        'image/jpeg',
        'image/gif',
        'image/bmp',
        'image/vnd.microsoft.icon',
    ];

    public $bucket = [

    ];

    public function __construct(array $items)
    {
        $this->items = $items;
        $this->setAttribute();
    }

    protected function setAttribute(){
        foreach (array_merge($this->items['drive']??[],$this->items['file']??[]) as $key => $val){
            if(property_exists($this,$key)){
                $this->$key = $val;
            }else{
                throw new ConfigItemNotExistException('Configuration item does not exist');
            }
        }
        if(isset($this->items['bucket'])){
            foreach ($this->items['bucket'] as $key => $val){
                if(is_int($key)){
                    $this->bucket[]=$val;
                }else{
                    $this->bucket[]=$key;
                }
            }
        }
    }

    public function bucket($name):BaseConfig{
        if(!in_array($name,$this->bucket)){
            throw new BucketNotExistException("bucket [$name] is not exist ");
        }
        if(isset($this->items['bucket'][$name])){
            if(array_key_exists($name,$this->bucketConfig)){
                return $this->bucketConfig[$name];
            }
            $config = clone $this;
            foreach($this->items['bucket'][$name] as $key => $val){
                $config->$key = $val;
            }
            $this->bucketConfig[$name] = $config;
            return $config;
        }
        return $this;
    }



}
