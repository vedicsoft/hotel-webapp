<?php
$crm_url = "http://ec2-52-10-227-189.us-west-2.compute.amazonaws.com/crm/service/v4_1/rest.php";
$crm_username = "crmadmin";
$crm_password = "Vsoft@123";

//function to make cURL request
function call($method, $parameters, $url)
{
    $curl_request = curl_init();
    curl_setopt($curl_request, CURLOPT_URL, $url);
    curl_setopt($curl_request, CURLOPT_POST, 1);
    //curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    // curl_setopt($curl_request, CURLOPT_HEADER, 1);
    curl_setopt($curl_request, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded')
    );
    curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
    $jsonEncodedData = json_encode($parameters);

    $post = array(
        "method" => $method,
        "input_type" => "JSON",
        "response_type" => "JSON",
        "rest_data" => $jsonEncodedData
    );

    curl_setopt($curl_request, CURLOPT_POSTFIELDS,http_build_query($post));
    $result = curl_exec($curl_request);
    curl_close($curl_request);

    $jsonData = stripslashes(html_entity_decode($result));

    $response=json_decode($jsonData,true);
    return $response;
}

//login ------------------------------
function loginCRM()
{
    $login_parameters = array(
        "user_auth" => array(
            "user_name" => $GLOBALS['crm_username'],
            "password" => md5($GLOBALS['crm_password']),
            "version" => "1"
        ),
        "application_name" => "RestTest",
        "name_value_list" => array(),
    );

    $login_result = call("login", $login_parameters, $GLOBALS['crm_url']);
    //get session id
    $session_id = $login_result['id'];  // this is the session id
    return $session_id;
}

function registerCRMUser($userdata)
{
    $session_id = loginCRM();
    $register_parameters = array(
        "session" => $session_id,
        "module_name" => "Leads",
        "name_value_list" => $userdata
    );
    $lead = call("set_entry", $register_parameters, $GLOBALS['crm_url']);
}

function pushReservationToCRM($reservation_data)
{
    $session_id = loginCRM();
    $register_parameters = array(
        "session" => $session_id,
        "module_name" => "vamps_reservation",
        "name_value_list" => $reservation_data
    );
    $reservation = call("set_entry", $register_parameters, $GLOBALS['crm_url']);
}