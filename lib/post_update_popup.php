<?php
include './DB.php';
ALLError();

var_dump($_POST);
foreach($_POST as $key=>$value) { ${$key} = trim($value); }

$table_name = 'new_popup';
$img_name = 'popup-image';
$filtered = array(
    'id' => $id,
    'title' => Quote($title),
    'display_start' => Quote($display_start),
    'display_end' => Quote($display_end),
    'hide' => 0
);

if ($_POST['hide']) $filtered['hide'] = 1;

$sql = "
        SELECT img_id
        FROM $table_name
        WHERE id = {$filtered['id']}
";
$row = Fetch(Query($sql));
$img_id = $row['img_id'];

if (isset($_POST["update"])) {
    $new_img_uploaded = check_img_is_uploaded($img_name);
    $check_result = 1;

    if (!$new_img_uploaded) {
        $error = check_file_error($img_name);
        if ($error == null) {
            $sql = "
            UPDATE $table_name
            SET
                title = '{$filtered['title']}',
                display_start = '{$filtered['display_start']}',
                display_end = '{$filtered['display_end']}',
                hide = {$filtered['hide']}
            WHERE
                id = {$filtered['id']}
            ";
            $result = Query($sql);
        } else {
            AlertAndRedirect($error, '/popup');
        }
    } else {
        $updated_img_name = update_img_in_dir($img_name);
        $sql = "
            UPDATE $table_name
            SET
                title = '{$filtered['title']}',
                img_id = '{$updated_img_name}',
                display_start = '{$filtered['display_start']}',
                display_end = '{$filtered['display_end']}',
                hide = {$filtered['hide']}
            WHERE
                id = {$filtered['id']}
        ";
        $result = Query($sql);
    }

    if (!$result) {
        AlertAndRedirect('저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요', '/popup');
        error_log(Error());
    } else {
        echo '저장에 성공했습니다';
        header('Location: /popup');
    }
}



?>