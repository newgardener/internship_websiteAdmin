<?php

include "./DB.php";

session_cache_expire(10);
session_start();

$today = time();
$_today = date('Y-m-d H:i:s', $today);

if (isset($_COOKIE["keep_login"])) {

	$cookie_info = json_decode($_COOKIE["keep_login"]);

	$user_id = $cookie_info->user_id;
	$keep_login_identifier = $cookie_info->keep_login_identifier;
	$keep_login_token = $cookie_info->keep_login_token;

	// DB 조회
	$sth_keep_login = Query("SELECT * FROM tb_user_login WHERE 
				email='$user_id' 
				AND keep_login_identifier='$keep_login_identifier' 
				AND keep_login_token='$keep_login_token'
		");
	$rows_keep_login = mysqli_num_rows($sth_keep_login);

	// if 모두 있음 -> 토큰 삭제 / 새 토큰 생성 -> 토큰 바꿔치기 -> 새 로그인 쿠키 발급
	if ($rows_keep_login > 0) {

		$new_keep_login_token = uniqid();

		Query("UPDATE tb_user_login SET keep_login_token='$new_keep_login_token' WHERE email='$user_id' AND keep_login_identifier='$keep_login_identifier'");

		$new_keep_login_info = array(
			'user_id' => $user_id,
			'keep_login_identifier' => $keep_login_identifier,
			'keep_login_token' => $new_keep_login_token
		);

		$days = 30;
		$cookie_duration = $days * 24 * 60 * 60;
		setcookie("keep_login", json_encode($new_keep_login_info), time() + $cookie_duration, '/');

		$sth = Query("UPDATE tb_user_login SET 
			keep_login_identifier='$keep_login_identifier',
			keep_login_token='$new_keep_login_token'
			WHERE email='$user_id'");

		$cookie_info = json_decode($_COOKIE["keep_login"]);
		$user_id = $cookie_info->user_id;

	}
}
