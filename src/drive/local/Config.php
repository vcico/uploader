<?php


namespace Vcico\Uploader\drive\local;

use Vcico\Uploader\BaseConfig;

final class Config extends BaseConfig
{

    const FOLDER_MODE_NONE = 0; // 不需要分层
    const FOLDER_MODE_DATE = 1; // 年月分层
    const FOLDER_MODE_HASH = 2; // 原文件名hash的前缀分层

//    const FOLDER_MODE_DATE_YEAR = 1;
//    const FOLDER_MODE_DATE_MONTH = 2;
//    const FOLDER_MODE_DATE_DAY = 3;

    // 没有默认值 必须设置
    public $basePath;

    // 文件夹分层模式
    public $folderMode=1;


    /* @var \Closure(file_path){ return file_url } */
    public $urlFunc;


    // 是否压缩

    // 是否水印

}
