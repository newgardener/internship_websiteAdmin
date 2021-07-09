<?php
session_cache_expire(10);
session_start();

date_default_timezone_set('Asia/Seoul');

include "./DB.php";

$today = time();
$_today = date('Y-m-d H:i:s', $today);

foreach($_POST as $key=>$value) { ${$key} = trim($value); }

function verify_user_api($email, $password) {

    $data = array(
        "email"=> $email,
        "password"=> $password
    );
    
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api3.useb.co.kr/users/login",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    curl_setopt($curl, CURLOPT_REFERER, 'http://www.google.com');
    curl_setopt($curl, CURLOPT_AUTOREFERER, true); 
    curl_setopt($curl, CURLOPT_TIMEOUT, 10); 
    curl_setopt($curl, CURLOPT_VERBOSE, 1); 
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    if ($err) {
        echo 'cURL Error #:' . $err;
    }

    return $response;

}

function verify_user($email, $password) {

	$verified = false;
	list($user_email, $user_password) = Fetch(Query("SELECT email, pw FROM tb_user WHERE email='$email'"));

	if ($user_password == $password) {
		$verified = true;
	}

	return $verified;
}

switch ($Action) {

    case "keep_login":

        list($user_email) = Fetch(Query("SELECT email FROM tb_user_login WHERE email='$email'"));

        // Set cookie variables
        $keep_login_identifier = uniqid();
        $keep_login_token = uniqid();

        if ($user_email != "") {

            $keep_login_info = array(
                'user_id' => $email,
                'keep_login_identifier' => $keep_login_identifier,
                'keep_login_token' => $keep_login_token
            );

            $days = 30;
            $cookie_duration = $days * 24 * 60 * 60;
            setcookie("keep_login", json_encode($keep_login_info), time() + $cookie_duration, '/');

            // Save DB variable
            $sth = Query("UPDATE tb_user_login SET 
                keep_login_identifier='$keep_login_identifier',
                keep_login_token='$keep_login_token'
                WHERE email='$user_email'");

        } else {

            $sth = Query("INSERT INTO tb_user_login(email, keep_login_identifier, keep_login_token) 
                VALUES('$email', '$keep_login_identifier', '$keep_login_token')");

        }

        if ($sth) {
            echo "success";
            exit;
        } else {

            echo "fail";
            exit;
        }

        break;

    case "just_login":

        $_SESSION["user_id"] = $email;

        if (isset($_SESSION["user_id"])) {

            echo "success";
            exit;

        } else {

            echo "fail";
            exit;

        }

        break;

    case "logout":

        unset($_SESSION["user_id"]);
        
        setcookie("keep_login", '', time() - 3600, '/');

        session_unset();
        session_destroy();

		echo "success";

        break;
}