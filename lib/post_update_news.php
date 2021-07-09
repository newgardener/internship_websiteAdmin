<?php
include './DB.php';
ob_start(); 

var_dump($_POST);
foreach($_POST as $key=>$value) { ${$key} = trim($value); }

$table_name = 'media';
$img_name = 'news-image';
$filtered = array(
    'id' => $id,
    'title' => Quote($title),
    'source' => Quote($source),
    'date' => Quote($press_date),
    'content' => Quote($content)
);

$content_edited = 1;
if ($filtered['content'] == '') $content_edited = 0;

$sql = "
        SELECT img_id
        FROM $table_name
        WHERE id = {$filtered['id']}
";
$row = Fetch(Query($sql));
$img_id = $row['img_id'];


if (isset($_POST["update"])) {
    $new_img_uploaded = check_img_is_uploaded($img_name);

    if (!$new_img_uploaded) {
        $error = check_file_error($img_name);
        if ($error == null) {
            if ($content_edited) {
                $sql = "
                UPDATE $table_name
                SET
                    title = '{$filtered['title']}',
                    source = '{$filtered['source']}',
                    date = '{$filtered['date']}',
                    content = '{$filtered['content']}'
                WHERE
                    id = {$filtered['id']}
                ";
            } else {
                $sql = "
                UPDATE $table_name
                SET
                    title = '{$filtered['title']}',
                    source = '{$filtered['source']}',
                    date = '{$filtered['date']}'
                WHERE
                    id = {$filtered['id']}
                ";
            }
            $result = Query($sql);
        } else {
            AlertAndRedirect($error, 'https://webadmin.useb.co.kr/components/support-news.php');
        }
    } else {
        $updated_img_name = update_img_in_dir($img_name);

        if ($content_edited) {
            $sql = "
                UPDATE $table_name
                SET
                    title = '{$filtered['title']}',
                    source = '{$filtered['source']}',
                    date = '{$filtered['date']}',
                    img_id = '{$updated_img_name}',
                    content = '{$filtered['content']}'
                WHERE
                    id = {$filtered['id']}
            ";
        } else {
            $sql = "
            UPDATE $table_name
            SET
                title = '{$filtered['title']}',
                source = '{$filtered['source']}',
                date = '{$filtered['date']}',
                img_id = '{$updated_img_name}'
            WHERE
                id = {$filtered['id']}
        ";
        }
        $result = Query($sql);
    }

    if (!$result) {
        AlertAndRedirect('저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요', 'https://webadmin.useb.co.kr/components/support-news.php');
        error_log(Error());
    } else {
        echo '저장에 성공했습니다';
        header('Location: https://webadmin.useb.co.kr/components/support-news.php');
    }
}

// DELTE PROCESS
if (isset($_POST["delete"])) {
    $img_to_delete = configure_filepath($img_name, $img_id);
    unlink($img_to_delete);

    $sql = "
        DELETE FROM $table_name
        WHERE id = {$filtered['id']}
    ";
    $result = Query($sql);
    if (!$result) {
        echo '저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
        error_log(Error());
    } else {
        echo '삭제에 성공했습니다';
        header('Location: https://webadmin.useb.co.kr/components/support-news.php');
    }
}









?>