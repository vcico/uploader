<?php


namespace Vcico\Uploader;


abstract class BaseDrive implements DriveContract
{
    /* @var BaseConfig */
    public $configure;

    public function __construct(array $items)
    {
        $this->configure = $this->config($items);
    }

    abstract public function config(array $items):BaseConfig;

    abstract public function uploadSingle($bucket,array $info):string;
    abstract public function uploadMulti($bucket,array $info):array;
}
