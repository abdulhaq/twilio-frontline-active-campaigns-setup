<?php

$recordingStatus = $_REQUEST['RecordingStatus'] ?? '';
$RecordingUrl = $_REQUEST['RecordingUrl'] ?? '';
$CallSid = $_REQUEST['CallSid'] ?? '';

if ($recordingStatus == 'completed') {

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.twilio.com/2010-04-01/Accounts/xxxxxx/Calls/' . $CallSid . '.json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic xxxxx'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $callDeatils = json_decode($response, true);
    $to = $callDeatils['to'];
    $from = $callDeatils['from'];

    if ($to == '+1440300000') {
        $toEmail = 'agent1@example.com';
    } elseif ($to == '+14409992440') {
        $toEmail = 'agent2@example.com';
    } elseif ($to == '+14409992499') {

        $toEmail = 'agent3@example.com';
    } else {
        $toEmail = 'agent1@example.com';
    }

    $msg = 'You recieved a new voicemail. Listen it here: ' . $RecordingUrl;
    //send email
    $headers = 'From: <info@example.com>' . "\r\n";
    $sendMail = mail($toEmail, $from . " New Voicemail to " . $to, $msg, $headers);

    //send sms
    $msg = "Hi. We detected a voicemail. For the fastest service, please reply to this text message.";

    sendSms($from, $to, $msg); //to and from will switch here as person who called need to recieve sms
}


function sendSms($to, $from, $msg)
{

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.twilio.com/2010-04-01/Accounts/xxxxx/Messages.json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'To=' . $to . '&From=' . $from . '&Body=' . $msg . '',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic xxxxx',
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
}
