<?php
include './DB.php';
ALLError();
$today = date("Y-m-d");

var_dump($_POST);
foreach($_POST as $key=>$value) { ${$key} = trim($value); }

$filtered = array(
    'title' => Quote($title),
    'display_start' => Quote($display_start),
    'display_end' => Quote($display_end),
    'hide' => 0
);
if ($hide) $filtered['hide'] = 1;

$table_name = 'new_popup';
$img_name = 'popup-image';

$cnt_dup = check_duplicates($filtered, $table_name);

if ($cnt_dup > 0) {
    AlertAndRedirect('게시 기간이 겹치는 팝업이 이미 존재합니다.', '/popup');
} else {
    if (isset($_POST["add"])) {
        $img_id = upload_img($img_name);

        if ($img_id == -1) {
            $error = check_file_error($img_name);
            AlertAndRedirect($error, '/popup');
        } else if ($img_id == -2)  {
            AlertAndRedirect('Sorry, only JPG, JPEG, PNG imgs are allowed', '/popup');
        } else {  
            $sql = "
                INSERT INTO $table_name
                (title, add_date, img_id, display_start, display_end, hide)
                VALUES(
                    '{$filtered['title']}',
                    '$today',
                    '$img_id',
                    '{$filtered['display_start']}',
                    '{$filtered['display_end']}',
                    {$filtered['hide']}
                )
            ";
            $result = Query($sql);

            if (!$result) {
                AlertAndRedirect('저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요', '/popup');
                error_log(mysqli_error($conn));
                delete_img($img_name, $img_id);
            } 
            else {
                Alert('저장에 성공했습니다');
                header('Location: /popup');
            }
        }
    }    
}


?>