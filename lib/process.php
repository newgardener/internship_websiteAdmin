<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);

include "./keep_login.php";

$today = time();
$_today = date('Y-m-d H:i:s', $today);

foreach($_POST as $key=>$value) { ${$key} = trim($value); }

function send_email($content, $title, $from_email, $from_name, $to_email, $support_id) {

	$_title = '[useB API 대시보드] Support ID ' . '"' . $support_id . '" ' . $title;

	$data = array(
		'key'=> 'hTVLOIuAxLaYOakhzTAZxg',
		"message"=> array(
			'html' => '
				<div style="background-color:white; color:black; width:100vw; height:100px; "> 
					<h4> 답변이 뭐라고 달렸는지 궁금하시다면 다음 링크를 눌러보세요! </h4>
					<img src="https://dashboard.useb.co.kr/assets/img/favicon_io/favicon-32x32.png" width="32" height="32">
					
					<br>
					<br>

					<a href="https://dashboard.useb.co.kr/support_detail.html?support_id='.$support_id.'">
						링크 
					</a>
				</div>
			',
			'text' => $content,
			'subject' => $_title,
			'from_email' => $from_email,
			'from_name' => $from_name,
			'to' => [
				array(
					'email' => $to_email, 
					'type' => 'to'
				),
				// array(
				// 	'email' => 'dge03078@useb.co.kr', 
				// 	'type' => 'cc'
				// )
			],
			'attachments' => [
				array(
					'type' => 'application/pdf',
					'name' => '1',
					'content' => base64_encode(file_get_contents('https://dashboard.useb.co.kr/uploads/poster.pdf'))
				)
			]
		)
	);

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://mandrillapp.com/api/1.0/messages/send/",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => json_encode($data),
		CURLOPT_HTTPHEADER => array(
		'Content-Type: application/json'
		),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	if ($err) {
		echo 'cURL Error #:' . $err;
	}

	return $response;

}

switch ($Action) {
	
	case "ticket":

		$transaction_id = Quote(uniqid());
		$category = Quote($category);
		$service = Quote($service);
		$title = Quote($title);
		$content = Quote($content);
		$timestamp = Quote($_today);
		$status = 0;
		$user_id = Quote($user_id);
		$is_admin = 0;

		$sth_ticket = Query("INSERT INTO tb_support SET 
			transaction_id=$transaction_id, 
			category=$category,
			service=$service,
			title=$title,
			content=$content,
			timestamp=$timestamp,
			status=$status,
			user_id=$user_id
		");

		// $new_transaction_id = Quote(uniqid());

		// $sth_reply = Query("INSERT INTO tb_support_reply SET 
		// 	transaction_id=$new_transaction_id,
		// 	pair_transaction_id=$transaction_id, 
		// 	content=$content,
		// 	timestamp=$timestamp,
		// 	user_id=$user_id,
		// 	is_admin = $is_admin
		// ");

		// if ($sth_ticket && $sth_reply) {
		if ($sth_ticket) {

			echo $transaction_id;
		}

		else {
			echo "fail";
		}

		break;

	case "reply":

		$pair_transaction_id=Quote($support_id);
		$transaction_id = Quote(uniqid());
		$content = Quote($content);
		$timestamp=Quote($_today);
		$user_id = Quote($user_id);
		$is_admin = 0;

		$sth = Query("INSERT INTO tb_support_reply SET 
			transaction_id=$transaction_id,
			pair_transaction_id=$pair_transaction_id, 
			content=$content,
			timestamp=$timestamp,
			user_id=$user_id,
			is_admin=$is_admin
		");

		// Send Email (Mailchimp)
		// $response = send_email(
		// 	"",
		// 	"에 답변이 달렸습니다.",
		// 	"contact@useb.co.kr", 
		// 	"useB", 
		// 	"koyrkr@useb.co.kr",
		// 	$support_id
		// );

		if ($sth) {
			
			echo $transaction_id;
		}

		else {
			echo "fail";
		}

		break;

}

?>