<?php
require_once "vendor/autoload.php";
 
use Google\Cloud\Storage\StorageClient;
 
try {
    $storage = new StorageClient([
        'keyFilePath' => 'wargabantuwarga-4be1dd332842.json',
    ]);
 
    $bucket = $storage->bucket('simple-buckets');
    $object = $bucket->object('1.png');
 
    $object->delete();
    print('berhasil dihapus');
} catch(Exception $e) {
    echo $e->getMessage();
}
