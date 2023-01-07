<?php

if (isset($_POST['Location'])) {
    $location = $_POST['Location'];
} else {
    $location = null;
}

if ($location == 'GetCustomerDetailsByCustomerId') {
    header('Content-Type: application/json; charset=utf-8');
    //echo json_encode( $data );
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://xxxx.api-us1.com/api/3/contacts/' . $_POST['CustomerId'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Api-Token: xxxxxx',
            'Cookie: PHPSESSID=xxxx; em_acp_globalauth_cookie=xxxx'
        ),
    ));

    $response = curl_exec($curl);
    $res = json_decode($response, true);

    //dd($res['contact']['phone']);

    $contact = $res['contact'];
    $fields = $res['fieldValues'];
    //$fieldSort[] = '';
    foreach ($fields as $field) {
        if ($field['field'] == 1) {
            $fieldSort[0] = $field['value'];
        } elseif ($field['field'] == 29) {
            $fieldSort[1] = $field['value'];
        } elseif ($field['field'] == 2) {
            $fieldSort[2] = $field['value'];
        } elseif ($field['field'] == 3) {
            $fieldSort[3] = $field['value'];
        } elseif ($field['field'] == 4) {
            $fieldSort[4] = $field['value'];
        } elseif ($field['field'] == 5) {
            $fieldSort[5] = $field['value'];
        } elseif ($field['field'] == 6) {
            $fieldSort[6] = $field['value'];
        } elseif ($field['field'] == 19) {
            $fieldSort[7] = $field['value'];
        } elseif ($field['field'] == 18) {
            $fieldSort[8] = $field['value'];
        } elseif ($field['field'] == 31) {
            $fieldSort[9] = $field['value'];
        } elseif ($field['field'] == 7) {
            $fieldSort[10] = $field['value'];
        } elseif ($field['field'] == 28) {
            $fieldSort[11] = $field['value'];
        } elseif ($field['field'] == 11) {
            $fieldSort[12] = $field['value'];
        } elseif ($field['field'] == 12) {
            $fieldSort[13] = $field['value'];
        } elseif ($field['field'] == 23) {
            $fieldSort[14] = $field['value'];
        } elseif ($field['field'] == 24) {
            $fieldSort[15] = $field['value'];
        } elseif ($field['field'] == 25) {
            $fieldSort[16] = $field['value'];
        } elseif ($field['field'] == 26) {
            $fieldSort[17] = $field['value'];
        } elseif ($field['field'] == 27) {
            $fieldSort[18] = $field['value'];
        }
    }

    $message = str_replace(array("\r", "\n"), ' ',  $fieldSort[10]); //preg_replace( "/\r|\n/", "", $fieldSort[10]);

    $fieldDetails = 'Bathrooms: ' . $fieldSort[0] . '\r\nHalf Bathrooms: ' . $fieldSort[1] . '\r\nBedrooms: ' . $fieldSort[2] . '\r\nTotal Square Feet: ' . $fieldSort[3] . '\r\nInclude Basement: ' . $fieldSort[4] . '\r\nPets / Kids: ' . $fieldSort[5] . '\r\nFrequency: ' . $fieldSort[6] . '\r\nCurrent Condition: ' . $fieldSort[7] . '\r\nCity: ' . $fieldSort[8] . '\r\nZip Code: ' . $fieldSort[9] . '\r\nMessage: ' . $message . ' \r\nLocation: ' . $fieldSort[11] . '\r\nCompany Name: ' . $fieldSort[12] . '\r\nCompany City: ' . $fieldSort[13] . '\r\n \r\nPricing: \r\nOne Time: ' . $fieldSort[14] . '\r\nWeekly: ' . $fieldSort[15] . '\r\nEvery 2 Weeks: ' . $fieldSort[16] . '\r\nEvery 4 Weeks: ' . $fieldSort[17] . '\r\nInitial Clean: ' . $fieldSort[18];

    curl_close($curl);
    echo '{
            "objects":{
                "customer":{
                    "customer_id":' . $contact['id'] . ',
                    "display_name":"' . $contact['firstName'] . ' ' . $contact['lastName'] . '",
                    "channels":[
                        {
                        "type":"sms",
                        "value":"' . formatNum($contact['phone']) . '"
                        },
                        {
                        "type":"phone",
                        "value":"' . formatNum($contact['phone']) . '"
                        },
                        {
                        "type":"email",
                        "value":"' . $contact['email'] . '"
                        }
                    ],
                    "links":[
                        {
                        "type":"Facebook",
                        "value":"https://facebook.com",
                        "display_name":"Social Media Profile"
                        }
                    ],
                    "details":{
                        "title":"More information",
                        "content":"' . $fieldDetails . '"
                    }
                }
            }
            }';
} else {
    //header('Content-Type: application/json; charset=utf-8');
    $curl = curl_init();
    if (isset($_POST['NextPageToken'])) {
        $pg_limit = 'limit=20&offset=' . $_POST['NextPageToken'];
    } else {
        $pg_limit = '';
    }

    if (isset($_POST['Query'])) {
        $url = 'https://xxxx.api-us1.com/api/3/contacts?orders[cdate]=DESC&search=' . $_POST['Query'] . '&' . $pg_limit;
    } else {
        $url = 'https://xxxx.api-us1.com/api/3/contacts?orders[cdate]=DESC&' . $pg_limit;
    }

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
            'Api-Token: xxxxx',
            'Cookie: PHPSESSID=xxxx; em_acp_globalauth_cookie=xxxx'
        ),
    ));

    $response = curl_exec($curl);

    $limit = 20;
    $offset = $res['meta']['page_input']['offset'] + $limit;

    if (!isset($_POST['Query'])) {
        $nextPageToken = ', "next_page_token": "' . $offset . '"';
    } else {
        $nextPageToken = null;
    }

    echo '{
        "objects": {
            "customers": [';
    curl_close($curl);
    foreach ($res['contacts'] as $contact) {
        echo '{"display_name":"' . $contact['firstName'] . ' ' . $contact['lastName'] . '",
        "customer_id":"' . $contact['id'] . '"},';
    }
    echo '],
        "searchable": true' . $nextPageToken . '
            }
        }';
}

function formatNum($num)
{
    $num = preg_replace('/[() -.]/', '', $num);
    $num = preg_replace('/^(?:\+?1|0)?/', '+1', $num);
    return $num;
}
