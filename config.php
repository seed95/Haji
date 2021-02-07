<?php
/**********************
** In the name of God
** Programmer : Meti (@futsal7)
** Channel : TurboTeam(@php08)
** Project : Vip Group
** Please do not touch this part to support the developer
************************/
error_reporting(0);
date_default_timezone_set("asia/tehran");

##########################[ Set Webhook ]######################
/*
https://api.telegram.org/bot1493103296:AAGaF87B7rzzIl4KguQ7rCepl3EMGjkS4lo/setWebhook?url=https://khoshkbardostan.xyz/bots/meti.php
*/
##########################[ Config ]###########################
$config = 
[
	'token' => "1493103296:AAGaF87B7rzzIl4KguQ7rCepl3EMGjkS4lo", // Token Bot
	'group'=>-1001355530846,// Group ID
	'coin'=>35000 // Coin Payment(Toman)
];
$admins = [66469061/*09103583256*/,118205890/*09383843814*/]; // ids for charge users
//Database logging
$servername = 'localhost';
$username = 'khoshkba_botu';
$password = '}y.urL[IE5PV';
$dbname = 'khoshkba_bot';
$connect = mysqli_connect($servername,$username,$password, $dbname);//Dont touch
$connect->set_charset('utf8mb4_general_ci');
$connect->query('SET NAMES utf8mb4');
###############################################################
function meti($method, $datas=[])
{
	global $config;
	$url = 'https://api.telegram.org/bot'.$config['token'].'/'.$method;
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
	$res = curl_exec($ch);
	if(curl_error($ch))
	{
		var_dump(curl_error($ch));
	}
	else
	{
		return json_decode($res);
	}
}
##########################[ Variable ]##########################
$update = json_decode(file_get_contents('php://input'));
if(isset($update->message))
{
	$text = $update->message->text;
	$chat_id = $update->message->chat->id;
	$from_id = $update->message->from->id;
	$message_id = $update->message->message_id;
	$contact = $update->message->contact;
	$phone_number =$update->message->contact->phone_number;
}
if(isset($update->callback_query))
{
	$data = $update->callback_query->data;
	$chat_id = $update->callback_query->message->chat->id;
	$from_id = $update->callback_query->from->id;
	$callback_id = $update->callback_query->id;
	$message_id = $update->callback_query->message->message_id;
}
$user = mysqli_fetch_assoc(mysqli_query($connect,"SELECT * FROM user WHERE id = '$from_id'"));
##########################[ Keyboard ]###########################
$home = json_encode(['resize_keyboard'=>true,'keyboard'=>[
	[['text'=>"خرید اشتراک🌟"]],
]]);
$butpay = json_encode(['inline_keyboard'=>[
	[['text'=>"ساخت لینک پرداخت",'callback_data'=>"payment"]],
]]);
$butphone = json_encode(['resize_keyboard'=>true,'keyboard'=>[
	[['text'=>"ارسال شماره 📲",'request_contact'=>true]],
]]);
##########################[ Payment ]###########################
function pay($from_id,$phone)
{
	global $config;
	$data = array("merchant_id" => "aec082bc-d960-417b-9b0b-6fe6a1ef01b2",
		"amount" => $config['coin']*10,
		"callback_url" => "https://khoshkbardostan.xyz/bots/verify.php?userid=$from_id",
		"description" => "اشتراک $from_id",
		"metadata" => ["email" => "info@email.com","mobile"=>$phone],
	);
	$jsonData = json_encode($data);
	$ch = curl_init('https://api.zarinpal.com/pg/v4/payment/request.json');
	curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($jsonData)
	));
	$result = curl_exec($ch);
	$err = curl_error($ch);
	$result = json_decode($result, true, JSON_PRETTY_PRINT);
	curl_close($ch);
	if ($err) 
	{
		 return 'false';
	} 
	else
	{
		if( empty($result['errors']) )
		{
			if( $result['data']['code']==100 )
			{
				return 'https://www.zarinpal.com/pg/StartPay/' . $result['data']["authority"];
			}
		} 
		else 
		{
			return 'false';
		}
	}
}
?>