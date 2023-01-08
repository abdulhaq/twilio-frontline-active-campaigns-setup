
# Twilio Frontline with Active Campaign Setup

Working for one of my client, I faild to find any example/tutorial on how to integrate Twilio Frontline with Active Campaigns. With this project you can integrate Frontline mobile app with your Active Campaign contacts.


## Features

- Pull list of your contacts from Active Campaign
- Receieve and make calls
- Voicemails
- Inbound and outbound SMS with your contacts
- Send automated SMS
- Template Messages


## Code explaination

### Inbound Calls

```html
  <Response>
      <Connect action="handleVoicemail.php">
          <Conversation serviceInstanceSid="xxxxxxx" inboundTimeout="20"/>
      </Connect>
  </Response>
```

**serviceInstanceSid** is the service ID which is found in Twilio console

**Action** is the url which will be called if call is not picked up or rejected.

### Inbound SMS

```php
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
```
Based on phone number we can assign the SMS to different agents.

### Outbound SMS

```php
{
    "proxy_address": "+1440000000"
}
```
Return the number from which to send SMS

### List Contacts
Return list of contacts as in the following json object
```json
{
   "objects":{
      "customers":[
         {
            "display_name":"Diane Randi",
            "customer_id":"332"
         },
         {
            "display_name":"Sarah Kenty",
            "customer_id":"331"
         },
         {
            "display_name":"Courtney Name",
            "customer_id":"44"
         }
      ],
      "searchable":true,
      "next_page_token":"20"
   }
}
```

### Vociemails
```html
<Response>
    <Say voice="woman">
    Any message you want to say before voicemail recording starts
    </Say>
    <Record recordingStatusCallback="voicemail.php" />
</Response>
```
Return response as above with **recordingStatusCallback** url on which recording link will be sent once its ready.

### Template Messages
```json
[
  {
    "display_name": "General",
    "templates": [
      { "content": "Great! Let me know if you have any questions. 😀" },
      { "content": "The easiest way to get an estimate is at www.example.com" },
      { "content": "Hi ' . $contact['firstName'] . '. 👋🏻 This is agent one. Do you have any questions about your service estimate?" },
      { "content": "Let's schedule a 15 minute phone interview https://calendly.com/xxxxx/15-minute-phone-interview 😀" },
    ]
  }
]
```
Return JSON as above with a list of all the template messages.



## Tech Stack

**Server:** PHP, cURL


## 🚀 About Me
I'm a full stack developer with experties in Twilio, Laravel, WordPress, AWS, React


## 🔗 Links
[![portfolio](https://img.shields.io/badge/my_portfolio-000?style=for-the-badge&logo=ko-fi&logoColor=white)](https://it.haq.life/)

[![linkedin](https://img.shields.io/badge/linkedin-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/abdulhaq0)

[![twitter](https://img.shields.io/badge/twitter-1DA1F2?style=for-the-badge&logo=twitter&logoColor=white)](https://twitter.com/AbdulHaqLife)

