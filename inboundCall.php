<?php

echo '<Response>
    <Connect action="handleVoicemail.php">
        <Conversation serviceInstanceSid="xxxxxxx" inboundTimeout="20"/>
    </Connect>
    </Response>';

//send sms if non contact
//======== find user by phone number =======//
$caller = $_POST['From'] ?? '';
$curl = curl_init();
$url = 'https://xxxx.api-us1.com/api/3/contacts?orders[cdate]=DESC&search=' . $caller;

curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Api-Token: xxxx',
        'Cookie: PHPSESSID=xxxx; em_acp_globalauth_cookie=xxx'
    ),
));

$customer_ac = curl_exec($curl);
$customer_ac = json_decode($customer_ac, true);
if (!isset($customer_ac['contacts'][0])) {

    $to = $_POST['From'] ?? '';
    $from = $_POST['Called'] ?? '';
    $msg = "You called us";

    sendSms($to, $from, $msg);
}

function sendSms($to, $from, $msg)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.twilio.com/2010-04-01/Accounts/xxxxxxx/Messages.json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'To=' . $to . '&From=' . $from . '&Body=' . $msg . '',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic xxxxxxx',
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
}
