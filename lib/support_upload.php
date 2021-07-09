<?php

include "./keep_login.php";
// upload.php
// 'images' refers to your file input name attribute
if (empty($_FILES['input-fas'])) {
    // echo json_encode(['error'=>'No files found for upload.']); 
    echo json_encode([]);
    // or you can throw an exception 
    return; // terminate
}

// get the files posted
$images = $_FILES['input-fas'];

// get user id posted
$support_id = empty($_POST['support_id']) ? '' : $_POST['support_id'];


// a flag to see if everything is ok
$success = null;

// file paths to store
$paths= [];

// get file names
$filenames = $images['name'];

$upload_folder = "../uploads";

// loop and process files
for ($i = 0; $i < count($filenames); $i++){
    $ext = explode('.', basename($filenames[$i]));
    $target = $upload_folder . DIRECTORY_SEPARATOR . uniqid() . "." . array_pop($ext);

    if( move_uploaded_file($images['tmp_name'][$i], $target) ) {
        $success = true;
        $paths[] = $target;
    } else {
        $success = false;
        break;
    }
}

// check and process based on successful status 
if ($success === true) {
    // call the function to save all data to database
    // code for the following function `save_data` is not 
    // mentioned in this example

    // save_data($userid, $username, $paths);
    $today = time();
    $_today = date('Y-m-d H:i:s', $today);
    $timestamp = Quote($_today);

    foreach ($paths as $file) {
        list(, , $filename_with_ext) = explode('/', $file);

        $filename_with_ext=Quote($filename_with_ext);

        $sth = Query("INSERT INTO tb_support_attach SET 
            support_id=$support_id,
            file_name=$filename_with_ext, 
            timestamp=$timestamp,
            flag=0
        ");
    }

    // store a successful response (default at least an empty array). You
    // could return any additional response info you need to the plugin for
    // advanced implementations.
    $output = [];
    // for example you can get the list of files uploaded this way
    // $output = ['uploaded' => $paths];
} elseif ($success === false) {
    $output = ['error'=>'Error while uploading images. Contact the system administrator'];
    // delete any uploaded files
    foreach ($paths as $file) {
        unlink($file);
    }
} else {
    $output = ['error'=>'No files were processed.'];
}

// return a json encoded response for plugin to process successfully
echo json_encode($output);
