<?php


namespace Vcico\Uploader;


interface DriveContract
{
    public function config(array $items):BaseConfig;
    public function uploadSingle($bucket,array $info):string;
    public function uploadMulti($bucket,array $info):array;
}
