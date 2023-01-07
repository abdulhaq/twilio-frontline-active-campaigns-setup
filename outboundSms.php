<?php

$agent = $_POST['Worker'] ?? '';
if ($agent == 'agent1@example.com') {

    $number = '+14403000000';
} elseif ($agent == 'agent2@example.com') {

    $number = '+1440900000';
} elseif ($agent == 'agent3@example.com') {

    $number = '+1440900000';
} else {

    $number = '+14403300000';
}
echo '{
        "proxy_address": "' . $number . '"
        }';
