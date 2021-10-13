<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:3000/web.php');
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'bucket' => 'product',
//    'logo' => new \CURLFile(realpath('C:\Users\vcico\Pictures\233fe315653dd9a1fba9bab573f5cb2f.jpeg'),'image/jpeg'),
    'images[0]' => new \CURLFile(realpath('C:\Users\vcico\Pictures\233fe315653dd9a1fba9bab573f5cb2f.jpeg'),'image/jpeg'),
    'images[1]' =>  new \CURLFile(realpath('C:\Users\vcico\Pictures\233fe315653dd9a1fba9bab573f5cb2f.jpeg'),'image/jpeg'),
]);
$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

