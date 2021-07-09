<?php
include './DB.php';
ALLError();

foreach($_POST as $key=>$value) { ${$key} = trim($value); }

$filtered = array(
    'title' => Quote($title),
    'source' => Quote($source),
    'date' => Quote($press_date),
    'content' => Quote($content)
);
$table_name = 'media';
$img_name = 'news-image';

$cnt_dup = check_duplicates($filtered, $table_name);

if ($cnt_dup > 0) {
    AlertAndRedirect('이미 동일한 데이터가 존재합니다.', 'https://webadmin.useb.co.kr/components/support-news.php');
} else {
    if (isset($_POST["add"])) {
        $img_id = upload_img($img_name);

        if ($img_id == -1) {
            $error = check_file_error($img_name);
            AlertAndRedirect($error, 'https://webadmin.useb.co.kr/components/support-news.php');
        } else if ($img_id == -2)  {
            AlertAndRedirect('Sorry, only JPG, JPEG, PNG imgs are allowed', 'https://webadmin.useb.co.kr/components/support-news.php');
        } else {  
            $sql = "
                INSERT INTO $table_name
                (title, source, date, img_id, content)
                VALUES(
                    '{$filtered['title']}',
                    '{$filtered['source']}',
                    '{$filtered['date']}',
                    '{$img_id}',
                    '{$filtered['content']}'
                )
            ";
            $result = Query($sql);

            if (!$result) {
                AlertAndRedirect('저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요', 'https://webadmin.useb.co.kr/components/support-news.php');
                error_log(mysqli_error($conn));
                delete_img($img_name, $img_id);
            } 
            else {
                Alert('저장에 성공했습니다');
                header('Location: https://webadmin.useb.co.kr/components/support-news.php');
            }
        }
    }
}


?>