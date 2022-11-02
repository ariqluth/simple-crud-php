<?php
require_once 'vendor/autoload.php';
 
use Google\Cloud\Storage\StorageClient;

$privateKeyFileContent = '{
    "type": "service_account",
  "project_id": "wargabantuwarga",
  "private_key_id": "4be1dd332842b08ec44c43863ca37fa070bb9a54",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDIrsClJ8IOjNDi\npliNprY2XNCLP0AM5b/WxrWGj+kGdLbOQKuZQ4NxPMmDJU5H34fgxTw4k1Et3Wj6\nV8Pz2r5rVMH/ach1ZfWaoA7Bf/CsA7fqx9vrA9b6sPZ7jP8P53Eokn/i+qyw4lna\nj8yfPQTX3ZyU75GeCp7irOkmDgGkFi1dhzhIJa6882k03CZAyV/aXHne9wXS24/+\n1mPkwFYj766aueW219EXilx938lIbpEOWbDS8yZxgCJE8EkU4SKMkM3QXGLYK/nl\n09vDtVNuqCxbwRagLvm4Kn1FAqR97C3uPVvQfkVCYT9t/EmspQrm57m0KaCx3M0I\nYL8UEY9DAgMBAAECggEABRR/Ze7it7GS3Bnv5e4RcMDaupuoSGtMduhi0fw0nug3\ndHMopOGTozsxsrMwDxu2edckVh5MkMepD6VHHZYs+dSUcJ3B6E7jb72bggR2GDSj\n5DqY85MOTxxJsQj5r/Jg5/EFcb58vqGMOr8nI+TMiZZfj1mdKw4DxaMw57i1C+1E\nTq8MN8AEj/k6EYsIS/gjrHoTpWz0KaahHHzCYWpxehjGkWreHO5c0Nmd0bzT8Q1N\nEIvqwM+NjhLfc95uGNSb6C5uNJFPNVLW5zGVKC0K7CcbK2HROimrcwHuLN3izXZB\noS7MdZ5g8hHpR4j5xZsHHkxGDit0Ofqxcjvum0VF6QKBgQDr1TB6k6g2ze1eThUO\ntl88h9IYPteR9ZETceqjI8hYJPon1TcZ0UPaOqoiV2zL4U8aaSSP4+6DGAuCMX7L\n4iv78/j4tx1zSj+EBZuHD4UAm8YaD8aGLz2A2SR9bjlGYDHq0RnAxHY3B5Atqst9\nS7eXNLJyXQVmA3qBO1DO6UNY9wKBgQDZ2A/tkoFurSp0BZ/EhZBOviIMhfOxL7l4\n1DtlrxiRbstzm3ivOel9sl+880XTmnh7c9TLBm0MpXbb1W3Kd5M+POGIaCxMgWrO\nIs6TFRCFQL9NUnY6B9/JUP/z/QvFmrWeZcC/jdS81tiTn1nzo0/KNrMkFt66iYQt\nuMoPdfkVFQKBgD7Gd+oZBezYsfepZzeMWht9t/IWQEGtEVoQt7mHW9wCs8gInGs1\n5g7gEMulY+N9bRYFqOLYdHPW4bFdRXg2Eyk1Dy/9Zkx7Fh8pcvXnrqqzzG7BhkHP\nr1pt8qWkwoA2OPNdh7JlWWZakAln7lTC4/LO5zVEB5zZDjH6PQ9mzjOPAoGBAKOs\nSotdABJ/2N3DO/8TuxDumfZ+zlHaYaf/DYGgxPzAGeyKa2p8QIpIctYskdIixVM7\n/C1ubpCJ4XvaJ9tvbBne9DTg0CfLNMwcKsknknoL46/cvSdssx821JglD6swSjTw\njpldnvEjGyYPHC6KDXEJGB+Nb1sH51/S7z4ejT7hAoGAcSntGhKy0HEPpTnQJI5y\no3cLf4VdVbH5FmcVE6uSQ2msk4Rey3JR3u9XuYZCbwX8ffgqgTjmBXbg1mEjf/we\nTVh4XfUpSjSZMGyWivwWkiCYC4Guq0/Z8M6nNVCPde9pZbL55leqDkn8JPMtK3ra\nPOHyR3ms8HePxkDlLiuretc=\n-----END PRIVATE KEY-----\n",
  "client_email": "bucket-account@wargabantuwarga.iam.gserviceaccount.com",
  "client_id": "114072322422854721966",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/bucket-account%40wargabantuwarga.iam.gserviceaccount.com"
}';

// *
//  * NOTE: if the server is a shared hosting by third party company then private key should not be stored as a file,
//  * may be better to encrypt the private key value then store the 'encrypted private key' value as string in database,
//  * so every time before use the private key we can get a user-input (from UI) to get password to decrypt it.
//  */
 
function uploadFile($bucketName, $fileContent, $cloudPath) {
    $privateKeyFileContent = $GLOBALS['privateKeyFileContent'];
    // connect to Google Cloud Storage using private key as authentication
    try {
        $storage = new StorageClient([
            'keyFile' => json_decode($privateKeyFileContent, true)
        ]);
    } catch (Exception $e) {
        // maybe invalid private key ?
        print $e;
        return false;
    }
 
    // set which bucket to work in
    $bucket = $storage->bucket($bucketName);
 
    // upload/replace file 
    $storageObject = $bucket->upload(
            $fileContent,
            ['name' => $cloudPath]
            // if $cloudPath is existed then will be overwrite without confirmation
            // NOTE: 
            // a. do not put prefix '/', '/' is a separate folder name  !!
            // b. private key MUST have 'storage.objects.delete' permission if want to replace file !
    );
 
    // is it succeed ?
    return $storageObject != null;
};
 
function getFileInfo($bucketName, $cloudPath) {
    $privateKeyFileContent = $GLOBALS['privateKeyFileContent'];
    // connect to Google Cloud Storage using private key as authentication
    try {
        $storage = new StorageClient([
            'keyFile' => json_decode($privateKeyFileContent, true)
        ]);
    } catch (Exception $e) {
        // maybe invalid private key ?
        print $e;
        return false;
    }
 
    // set which bucket to work in
    $bucket = $storage->bucket($bucketName);
    $object = $bucket->object($cloudPath);
    return $object->info();
}
//this (listFiles) method not used in this example but you may use according to your need 
function listFiles($bucket, $directory = null) {
 
    if ($directory == null) {
        // list all files
        $objects = $bucket->objects();
    } else {
        // list all files within a directory (sub-directory)
        $options = array('prefix' => $directory);
        $objects = $bucket->objects($options);
    }
 
    foreach ($objects as $object) {
        print $object->name() . PHP_EOL;
        // NOTE: if $object->name() ends with '/' then it is a 'folder'
    }
}
