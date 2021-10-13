<?php

require "../vendor/autoload.php";
$uploader = new \Vcico\Uploader\Uploader([
    'driveClass' => \Vcico\Uploader\drive\local\Local::class,
    'drive' => [
        'basePath' => './upload',
//        'folderMode' =>1,
        'urlFunc' => function($path){
            return 'http://localhost:3000'.substr($path,1);
        }
    ],
//    'file' => [
//    ],
    'bucket' => [
        'company',
        'product'
//        => [
//            'maxSize' => 2048,
//        ]
        ,
        'video' => [
            'suffix' => [
                'mp4','flv','mp3','avi'
            ],
            'mimeType' => [
                'audio/mpeg',
                'video/x-flv',
                'video/mp4',
                'application/x-mpegURL',
                'video/x-msvideo'
            ]
        ]
    ],
]);

//var_dump($uploader->UploadSingle($_POST['bucket'],$_FILES['logo']));
var_dump($uploader->UploadMulti($_POST['bucket'],$_FILES['images']));


// php -S localhost:3000 -t  path/uploader/test
