<?php

/**
 * 1. Change convo name
 * 2. Update participent (customer) if exist in contacts with name and contact_id
 * 3. Add participent (agent)
 */

//dd($_REQUEST);
$cid = $_POST['ConversationSid'];
$agentNum = $_REQUEST['MessagingBinding_ProxyAddress'];
$customerNum = $_REQUEST['MessagingBinding_Address'];

//======== get participents =============//
$part_url = 'https://conversations.twilio.com/v1/Conversations/' . $cid . '/Participants';
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $part_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Basic xxxxxxx'
    ),
));

$part_res = curl_exec($curl);
$part_res = json_decode($part_res, true);

curl_close($curl);
$customer_url = $part_res['participants'][0]['url'];

//======== find user by phone number =======//
$curl = curl_init();
$url = 'https://xxxx.api-us1.com/api/3/contacts?orders[cdate]=DESC&search=' . $customerNum;

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
        'Api-Token: xxxxxxx',
        'Cookie: PHPSESSID=xxxx; em_acp_globalauth_cookie=xxxx'
    ),
));

$customer_ac = curl_exec($curl);
$customer_ac = json_decode($customer_ac, true);
if (isset($customer_ac['contacts'][0])) {

    $cus_name = $customer_ac['contacts'][0]['firstName'] . '%20' . $customer_ac['contacts'][0]['lastName'];
    $cus_id = $customer_ac['contacts'][0]['id'];

    $attributes = 'Attributes=%7B%22customer_id%22%3A' . $cus_id . '%2C%22display_name%22%3A%22' . $cus_name . '%22%7D';
} else {

    $cus_name = $customerNum;
    $attributes = '';
}

// ========== Change convo name ============//
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://conversations.twilio.com/v1/Conversations/' . $cid,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => 'FriendlyName=' . $cus_name,
    CURLOPT_HTTPHEADER => array(
        'Authorization: Basic xxxxx',
        'Content-Type: application/x-www-form-urlencoded'
    ),
));

$convo_res = curl_exec($curl);

curl_close($curl);
$convo_res = json_decode($convo_res, true);

// update customer info
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $customer_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $attributes,
    CURLOPT_HTTPHEADER => array(
        'Authorization: Basic xxxx',
        'Content-Type: application/x-www-form-urlencoded'
    ),
));

$cus_update_res = curl_exec($curl);
curl_close($curl);

// add agent to convo
if ($agentNum == '+14400000000') {

    $agentId = 'agent1@example.com';
} elseif ($agentNum == '+14403000001') {

    $agentId = 'agent2@example.com';
} elseif ($agentNum == '+1440111111') {

    $agentId = 'agent3@example.com';
} else {

    $agentId = 'agent1@example.com';
}
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $part_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => 'Identity=' . $agentId,
    CURLOPT_HTTPHEADER => array(
        'Authorization: Basic xxxx',
        'Content-Type: application/x-www-form-urlencoded'
    ),
));

$agent_add_res = curl_exec($curl);
curl_close($curl);
