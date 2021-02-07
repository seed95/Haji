<?php
/**********************
** In the name of God
** Programmer : Meti (@futsal7)
** Channel : TurboTeam(@php08)
** Project : Vip Group
** Please do not touch this part to support the developer
************************/
require_once('config.php');
##########################[ Code Pay ]#########################
$Authority = $_GET['Authority'];
$userid = $_GET['userid'];
$data = array("merchant_id" => "aec082bc-d960-417b-9b0b-6fe6a1ef01b2", "authority" => $Authority, "amount" => $config['coin']*10);
$jsonData = json_encode($data);
$ch = curl_init('https://api.zarinpal.com/pg/v4/payment/verify.json');
curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v4');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData)
));
$result = curl_exec($ch);
curl_close($ch);
$result = json_decode($result, true);
if ($err) {
    meti('sendmessage',['chat_id'=>$userid,'text'=>"مشکلی در تراکنش شما به وجود آمد هزینه کسر شده طی 72 ساعت آینده به حساب شما برگشت خواهد شد❗️"]);
    header("Location: https://t.me/php08");
} else {
    if ($result['data']['code'] == 100) {
         meti('sendmessage',['chat_id'=>477628584,'text'=>"یک پرداخت انجام شد"]);
        meti('sendmessage',['chat_id'=>$userid,'text'=>"پرداخت شما با موفقیت انجام شد ✅ \n\nلینک گروه \nhttps://t.me/joinchat/UL1sI0bP6OnqOa2P"]);
        meti('restrictChatMember',['chat_id'=> $config['group'],'user_id'=>$userid,'can_post_messages'=>true,'can_add_web_page_previews'=>false,'can_send_other_messages'=>true,'can_send_media_messages'=>true,]);
        $time = time() + 2628000;
        // INSERT INTO vip (id, time) VALUES(118205890, 19) ON DUPLICATE KEY UPDATE time=20;
        $connect->query("INSERT INTO vip (id,time) VALUES ('$userid', '$time')");
        header("Location: https://t.me/php08");
    } else {
        meti('sendmessage',['chat_id'=>$userid,'text'=>"تراکنش توسط شما لغو شد❗️️"]);
        header("Location: https://t.me/php08");
    }
}
?>