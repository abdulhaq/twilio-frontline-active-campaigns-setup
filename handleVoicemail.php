<?php

$callStatus = $_POST['DialCallStatus'] ?? '';
if ($callStatus == 'no-answer' || $callStatus == 'busy') {
    echo '<Response>
    <Say voice="woman">
    Hi. You have reached us. For the fastest service please send us a text message. If you prefer to leave a voicemail, please provide your name, phone number, and the reason for your call and we will be in touch within 24 hours.
    </Say>
    <Record recordingStatusCallback="voicemail.php" />
    </Response>';

    $to = $_POST['From'] ?? '';
    $from = $_POST['To'] ?? '';
    $msg = 'Hi. We detected a voicemail. For the fastest service, please reply to this text message.';
} else {
    echo '<Response><Hangup/></Response>';
}
