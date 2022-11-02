<?php
require_once "vendor/autoload.php";
 
use Google\Cloud\Storage\StorageClient;

   
        try {
            $storage = new StorageClient([
                'keyFilePath' => 'wargabantuwarga-4be1dd332842.json',
            ]);
         
            $bucketName = 'simple-buckets';
            $fileName = '1.png';
            $bucket = $storage->bucket($bucketName);
            $object = $bucket->upload(
                fopen($fileName, 'r')
                
            );
            print('berhasil ditambahkan');
        } catch(Exception $e) {
            echo $e->getMessage();
        }


?>
<!--  -->