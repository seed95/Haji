<?php
/**********************
** In the name of God
** Programmer : Meti (@futsal7)
** Channel : TurboTeam(@php08)
** Project : Vip Group
** Please do not touch this part to support the developer
************************/
include 'config.php';
//************************************************************
if(isset($update))
{
	$telegram_ip_ranges = [['lower' => '149.154.160.0', 'upper' => '149.154.175.255'],
						   ['lower' => '91.108.4.0',    'upper' => '91.108.7.255'],];
	$ip_dec = (float) sprintf("%u", ip2long($_SERVER['REMOTE_ADDR']));
	$ok = false;
	foreach ($telegram_ip_ranges as $telegram_ip_range)
	{ 
		if (!$ok) 
		{
			$lower_dec = (float) sprintf("%u", ip2long($telegram_ip_range['lower']));
			$upper_dec = (float) sprintf("%u", ip2long($telegram_ip_range['upper']));
			if ($ip_dec >= $lower_dec and $ip_dec <= $upper_dec)
			{ 
				$ok = true;
			}
		}
	}
	if (!$ok)
	{
		die('<h1 style="font-family:b yekan;color:red;text-align:center;margin-top:50px;">🖕🏿 کیر شدگان را تدبیری نیست</h1>');
	}

}
//************************************************************
if( $update->message->chat->type=='private' )
{
	if( strpos($text, "charge")!==false and in_array($from_id, $admins))
	{
		$split_text = explode(" ", $text);
		$day = $split_text[1];
		$user_id = $split_text[2];

		$time = time() + $day*24*60*60;//second
		$update_query = "INSERT INTO vip (id, time) VALUES('$user_id', '$time')";
		$update_query = $update_query." ON DUPLICATE KEY UPDATE time='$time';";
		$connect->query($update_query);
		$charge_text = "charge user " . $user_id . " " . $day;
		if( $user['pn']==null )
		{
			$charge_text = "charge user " . $user['text'];
			meti('sendmessage', ['chat_id'=>$chat_id, 'text'=>$charge_text]);
		}
		else
		{
			meti('sendmessage', ['chat_id'=>$chat_id, 'text'=>$update_query]);	
		}
	}
	elseif( $text=="/start" )
	{
		$start_message = "به ربات گروه حبوبات و خشکبار خوش آمدید\n\nبرای خرید اشتراک ماهانه از دکمه زیر استفاده کنید";
		meti('sendmessage',['chat_id'=>$chat_id,'text'=>$start_message,'reply_markup'=>$home]);
	}
	elseif( $text=="خرید اشتراک🌟" )
	{
		if( $user['pn']==null )
		{
			meti('sendmessage',['chat_id'=>$chat_id,'text'=>"جهت انجام عملیات پرداخت شما باید شماره تماس خود را برای ما از دکمه زیر ارسال کنید👇🏽",'reply_markup'=>$butphone]);
		}
		else
		{
			meti('sendmessage',['chat_id'=>$chat_id,'text'=>"خرید حساب ویژه جهت ارسال پیام در گروه 🌟\n\n💳مبلغ قابل پرداخت : {$config['coin']} تومان\n📞شماره تماس : $phone_number\n\nجهت ساخت لینک پرداخت از دکمه زیر استفاده کنید👇🏽",'reply_markup'=>$butpay]);
		}
	}
	elseif( isset($contact) )
	{
		if( $update->message->contact->user_id==$from_id )
		{
			if( strpos($phone_number,'98')===0 || strpos($phone_number,'+98')===0 )
			{
				$phone_number='0'.strrev(substr(strrev($phone_number),0,10));
				$connect->query("UPDATE user SET pn = '$phone_number' WHERE id = '$from_id'");
				meti('sendmessage',['chat_id'=>$chat_id,'text'=>"شماره تماس $phone_number با موفقیت تایید شد ✅",'reply_markup'=>$home]);
				meti('sendmessage',['chat_id'=>$chat_id,'text'=>"خرید حساب ویژه جهت ارسال پیام در گروه 🌟\n\n💳مبلغ قابل پرداخت : {$config['coin']} تومان\n📞شماره تماس : $phone_number\n\nجهت ساخت لینک پرداخت از دکمه زیر استفاده کنید👇🏽",'reply_markup'=>$butpay]);
			}
			else
			{
				meti('sendmessage',['chat_id'=>$chat_id,'text'=>"کاربر عزیز به دلیل اینکه شماره شما برای ایران نمیباشد ما نمیتوانیم هویت شما را تایید  کنیم ❗️" ,'reply_markup'=>$home]);
			}
		}
		else
		{
			meti('sendmessage',['chat_id'=>$chat_id,'text'=>"لطفا شماره این اکانت را ارسال کنید❗️",'reply_markup'=>$home]);
		}
	}
	else
	{
		meti('sendmessage', ['chat_id'=>$chat_id, 'text'=>$text]);
	}

}
elseif($data == "payment")
{
	$pay = pay($from_id, $user['pn']);
	if($pay != 'false')
	{
		meti('AnswerCallbackQuery',['callback_query_id'=>$callback_id,'text'=>'لینک پرداخت ساخته شد ✅','show_alert'=>true]);    
		meti('EditMessageReplyMarkup',['chat_id'=>$chat_id,'message_id'=>$message_id,'reply_markup'=>json_encode(['inline_keyboard'=>[[['text'=>"پرداخت فاکتور💳",'url'=>$pay]]]])]);
	}
	else
	{
		meti('editmessagetext',['chat_id'=>$chat_id,'message_id'=>$message_id,'text'=>"مشکلی در ارتباط با درگاه به وجود آمد بعدا امتحان کنید ❗️"]);
	}
}
if($update->message->chat->type != 'private')
{
	$chat_member = meti('getChatMember', ['chat_id'=>$chat_id, 'user_id'=>$from_id]);
	$status = $chat_member->result->status;

	if( $status!='creator' and $status!='administrator' )
	{
		if($chat_id == $config['group'])
		{
			$vip = mysqli_fetch_assoc(mysqli_query($connect,"SELECT * FROM vip WHERE id = '$from_id'"));
			if($vip != true)
			{
				meti('deleteMessage',['chat_id'=>$chat_id,'message_id'=>$message_id]);
				meti('restrictChatMember',['chat_id'=>$chat_id,'user_id'=>$from_id,'can_post_messages'=>true]);
			}
			else
			{
				if($vip['time'] <= time())
				{
					meti('deleteMessage',['chat_id'=>$chat_id,'message_id'=>$message_id]);
					meti('sendmessage',['chat_id'=>$from_id,'text'=>"با عرض پوزش مهلت اشتراک 1 ماهه شما به اتمام رسیده است و نمیتوانید هیچ پیامی ارسال کنید❗️"]);
					meti('restrictChatMember',['chat_id'=>$chat_id,'user_id'=>$from_id,'can_post_messages'=>true]);
				}
			}
		}
	}
}
//************************************************************
if($user["id"] != true)
{
	$connect->query("INSERT INTO user (id) VALUES ('$from_id')");
}
//************************************************************