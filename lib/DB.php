<?php
$today = time();
$news_img_dir = '/assets/uploaded/news/';
$news_content_dir = '/assets/uploaded/news-content/';
$popup_img_dir = '/assets/uploaded/popup/';

define("NEWS_IMG_PATH", $_SERVER['DOCUMENT_ROOT'].$news_img_dir);
define("NEWS_CONTENT_PATH", $_SERVER['DOCUMENT_ROOT'].$news_content_dir);
define("POPUP_IMG_PATH", $_SERVER['DOCUMENT_ROOT'].$popup_img_dir);

function Connect() {
    global $conn;
	$username = "chae";
	$password = "Cotmddhks!23";
    $dbname = "website";
    if(!isset($conn)) {
        $conn = mysqli_connect('localhost', $username, $password, $dbname);
        mysqli_query($conn, "set names utf8");
    }
    if ($conn === false) {
        return mysqli_connect_error();
    }
    return $conn;
}

function Console_log($data){
    echo "<script>console.log( 'PHP_Console: " . $data . "' );</script>";
}

function AlertAndRedirect($data, $href) {
    echo "<script>alert('" . $data . "');window.location.href='$href'</script>";
}

function Alert($data) {
    echo "<script>alert('" . $data . "')</script>";
}

function ALLError() {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

function Error() {
    $conn = Connect();
    return mysqli_error($conn);
}

function Query($sql) {
    $conn = Connect();
    $result = mysqli_query($conn, $sql) or die("error : " . Error()."<br />".$sql);;
    if(!$result) $result = Error();
    return $result;
}

function Fetch($sth) {
    $result = mysqli_fetch_array($sth);
    return $result;
}

function Quote($str) {
    $conn = Connect();
    $sth = mysqli_real_escape_string($conn, $str);
    return $sth;
}

function toHTML($str) {
    $sth = htmlspecialchars($str);
    return $sth;
}

function toLocalScale($time) {
    $date = explode(' ', $time)[0];
    $time = explode(' ', $time)[1];
    $datetime = $date.'T'.$time;
    return $datetime;
}

function lastId() {
    $conn = Connect();
    $last_id = mysqli_insert_id($conn);
    return $last_id;
}

function total_count($table_name) {
    $sql = "SELECT * FROM $table_name";
    $data = Query($sql);
    $total_row = mysqli_num_rows($data);
    return $total_row;
}

function delete_content($content_id) {
    if (!unlink(NEWS_CONTENT_PATH .$content_id)) {
        Alert('content is not successfully deleted!');
        return;
    }
    return;
}

function delete_img($img_name, $img_id) {
    if ($img_name == 'news-image') {
        if (!unlink(NEWS_IMG_PATH .$img_id)) {
            Alert('news-image is not successfully deleted!');
            return;
        }
        return;
    } else if ($img_name == 'popup-image') {
        if(!unlink(POPUP_IMG_PATH .$img_id)) {
            Alert('popup-image is not successfully deleted!');
            return;
        }
        return;
    }
}

function upload_content($content_id, $filtered) {
    $fp = fopen(NEWS_CONTENT_PATH .$content_id, 'w') or die("Unable to open file!");
    fwrite($fp, $filtered['content']);
    fclose($fp);
}

function read_content($content_id) {
    $myfile = NEWS_CONTENT_PATH .$content_id;
    $fp = fopen($myfile, "r") or die("Unable to open file!");
    $content = fread($fp, filesize($myfile));
    fclose($fp);
    return $content;
}

function upload_img($img_name) {
    $img_uploaded = check_img_is_uploaded($img_name);

    if (!$img_uploaded) {
        return -1;
    } else {
        $img_type = $_FILES[$img_name]["type"];
        $img_type = explode("/", $img_type)[1];
        if (check_img_type($img_name)) {
            $generated_uniqid = uniqid();
            $generated_img_name = $generated_uniqid.'.'.$img_type;
            
            if ($img_name == 'news-image') {
                $target_file = NEWS_IMG_PATH .$generated_uniqid.'.'.$img_type;
            } else if ($img_name == 'popup-image') {
                $target_file = POPUP_IMG_PATH .$generated_uniqid.'.'.$img_type;
            }
            $tmp_file_name = $_FILES[$img_name]['tmp_name'];
            $result = move_uploaded_file($tmp_file_name, $target_file);  

            if ($result) return $generated_img_name; 
            else return -1; // failed while uploading image
        } else {
            return -2; // failed for unmatched image type
        }
    }
}

function check_img_is_uploaded($img_name) {
    $file_uploaded = 0;
    if (is_uploaded_file($_FILES[$img_name]['tmp_name'])) {
        $file_uploaded = 1;
    } 
    return $file_uploaded;
}

function check_img_type($img_name) {
    $upload_400 = 1;
    $type = $_FILES[$img_name]['type'];
    if ($type != 'image/jpeg' &&
        $type != 'image/jpg' &&
        $type != 'image/png') {
            $upload_400 = 0;
    }
    return $upload_400;
}

function check_file_error($img_name) {
    $error = $_FILES[$img_name]['error'];
    $error_message = null;

    if ($error != UPLOAD_ERR_OK) {
        switch ($error) {
            case UPLOAD_ERR_INI_SIZE:
                $error_message = 'The uploaded file exceeds the upload_max_filesize 2MB in php.ini.';
                break;

            case UPLOAD_ERR_PARTIAL:
                $error_message = 'The uploaded file was only partially uploaded.';
                break;

            case UPLOAD_ERR_EXTENSION:
                $error_message = 'A PHP extension interrupted the upload.';
                break;
        }
    }
    return $error_message;
}

function configure_filepath($img_name, $stored_img) {
    if ($img_name == 'news-image') {
        $filepath = NEWS_IMG_PATH .$stored_img;
    } else if ($img_name == 'popup-image') {
        $filepath = POPUP_IMG_PATH .$stored_img;
    } else if ($img_name == 'news-content') {
        $filepath = NEWS_CONTENT_PATH .$stored_img;
    }
    return $filepath;
}

function update_img_in_dir($img_name) {
    $uploaded_tmp_file= $_FILES[$img_name]['tmp_name'];
    $img_type =  explode("/", $_FILES[$img_name]["type"])[1];

    if ($img_name == 'news-image') $table_name = 'media';
    else $table_name = 'new_popup';

    $sql = "
    SELECT img_id
    FROM $table_name
    WHERE id = {$_POST["id"]}
    ";

    $row = Fetch(Query($sql));
    
    $stored_img_name = $row['img_id'];
    if ($stored_img_name) {
        $filepath = configure_filepath($img_name, $stored_img_name);
        unlink($filepath);
    } 

    $generated_uniqid = uniqid();
    $img_name_to_store = $generated_uniqid.'.'.$img_type;
    $target_file = configure_filepath($img_name, $img_name_to_store);
    $result = move_uploaded_file($uploaded_tmp_file, $target_file);  

    if ($result) return $img_name_to_store;
    else return -1; # upload 과정에서 문제가 발생하는 경우
}


function check_duplicates($filtered, $table_name) {

    switch($table_name) {
        case 'media':
            $title = $filtered['title'];
            $source = $filtered['source'];
            $dup_sql = "
                    SELECT *
                    FROM $table_name
                    WHERE title = '$title' AND source = '$source'
            ";
            $result = Query($dup_sql);
            $total = mysqli_num_rows($result);
            if ($total > 0)
                return 1;
            return 0;

        case 'new_popup':
            // $display_start = $filtered['display_start'];
            // $display_end = $filtered['display_end'];
            // $dup_sql = "
            //         SELECT MIN(display_start), MAX(display_end)
            //         FROM $table_name
            // ";
            // $result = Query($dup_sql);
            // while ($row = Fetch($result)) {
                
            // }
            // if ($filtered['title'] == $row['title'] && $filtered['type'] == $row['type'])
            //     return 1;
            return 0;
    }
}










