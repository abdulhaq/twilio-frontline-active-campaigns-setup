<?php
/*
- $_POST['Worker']
- $_POST['CustomerId']
- $_POST['Location']
- $_POST['ConversationSid']
*/
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://xxx.api-us1.com/api/3/contacts/' . $_POST['CustomerId'],
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Api-Token: xxxx',
    'Cookie: PHPSESSID=xxx; em_acp_globalauth_cookie=xxxx'
  ),
));

$response = curl_exec($curl);
$res = json_decode($response, true);

//dd($res['contact']['phone']);

$contact = $res['contact'];

echo '[
  {
    "display_name": "General",
    "templates": [
      { "content": "Great! Let me know if you have any questions. 😀" },
      { "content": "The easiest way to get an estimate is at www.example.com" },
      { "content": "Hi ' . $contact['firstName'] . '. 👋🏻 This is agent one. Do you have any questions about your service estimate?" },
      { "content": "Let\'s schedule a 15 minute phone interview https://calendly.com/xxxxx/15-minute-phone-interview 😀" },
    ]
  }
]';
