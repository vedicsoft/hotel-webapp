<?php
/**
 * Created by IntelliJ IDEA.
 * User: Sandun
 * Date: 8/11/16
 * Time: 2:35 PM
 */

include 'DatabaseConnection.php';
include 'crm.php';

$instance =  DatabaseConnection::getInstance("vamps");
$conn = $instance->getConnection();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// GET REQUEST
if (isset($_GET['command'])) {
    $command = $_GET['command'];
    if ($command == 'login') {
        $email = $_GET['email'];
        $password = $_GET['password'];
        $query = "SELECT username, email FROM wf_subscribers WHERE email='" . $email . "' and password = '" . $password . "'";
        $result = $conn->query($query);
        $count = $result->num_rows;
        if ($count > 0) {
            while ($user = $result->fetch_assoc()) {
                $userdetails = $user;
            }
            $response = array("status" => $count, "user" => $userdetails);
            echo json_encode($response);
        } else {
            $response = array("status" => $count);
            echo json_encode($response);
        }
    } else if ($command == 'registration') {
        try {
            echo $_GET['name'];
            $stmt = $conn->prepare("INSERT INTO hotels ( name, address,city,latitude,longitude) VALUES (?,?,?,?,?)");

            $stmt->bind_param("sssss", $name, $address, $city, $latitude, $longitude);

            $name = isset($_GET['name']) ? $_GET['name'] : '';
            $address = isset($_GET['address']) ? $_GET['address'] : '';
            $city = isset($_GET['city']) ? $_GET['city'] : '';
            $latitude = isset($_GET['latitude']) ? $_GET['latitude'] : '';
            $longitude = isset($_GET['longitude']) ? $_GET['longitude'] : '';

            if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            } else {
                echo "New records created successfully";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

// POST Requests
$data = file_get_contents('php://input');
$obj = json_decode($data);

if ($obj->action == 'user_registration') {
    $userdetails = $obj->contact;
    $user = $obj->user;
    $tenantid = 15;
    try {
        $stmt = $conn->prepare("INSERT INTO wf_subscribers (
              tenantid,
              username,
              password,
              email,
              first_name,
              last_name,
              mobile_number,
              marital_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("isssssss", $tenantid, $username, $password, $email, $first_name, $last_name,$mobile_number, $marital_status);


//      account_status, gender, birthday, age, age_upper, age_lower, religion, occupation, profile_image, admin_notes

//      $account_status, $first_name, $last_name, $gender, $birthday, $age, $age_upper, $age_lower, $religion, $occupation, $marital_status, $profile_image, $mobile_number, $admin_notes

        $username = isset($user->username) ? $user->username : '';
        $email = isset($user->email) ? $user->email : '';
        $password = isset($user->password) ? $user->password : '';
        $tenantid = 15;
        $first_name = isset($userdetails->first_name) ? $userdetails->first_name : '';
        $last_name = isset($userdetails->last_name) ? $userdetails->last_name : '';
        $mobile_number = isset($userdetails->phone) ? $userdetails->phone : '';
        $marital_status = isset($userdetails->marital) ? $userdetails->marital : '';
        $age = isset($userdetails->age) ? $userdetails->age : 0;
        $gender = isset($userdetails->gender) ? $userdetails->gender : '';

        $street = isset($userdetails->street) ? $userdetails->street : '';
        $city = isset($userdetails->city) ? $userdetails->city : '';
        $state = isset($userdetails->state) ? $userdetails->state : '';
        $postalcode = isset($userdetails->postalcode) ? $userdetails->postalcode : '';
        $country = isset($userdetails->country) ? $userdetails->country : '';


    /*  $account_status = isset($userdetails->account_status) ? $userdetails->account_status : '';
        $gender = isset($userdetails->gender) ? $userdetails->gender : '';
        $birthday = isset($userdetails->birthday) ? $userdetails->birthday : '';
        $age = isset($userdetails->age) ? $userdetails->age : 0;
        $age_upper = isset($userdetails->age_upper) ? $userdetails->age_upper : 0;
        $age_lower = isset($userdetails->age_lower) ? $userdetails->age_lower : 0;
        $religion = isset($userdetails->religion) ? $userdetails->religion : '';
        $occupation = isset($userdetails->occupation) ? $userdetails->occupation : '';
        $profile_image = isset($userdetails->account_status) ? $userdetails->account_status : '';
        $admin_notes = isset($userdetails->admin_notes) ? $userdetails->admin_notes : '';*/

        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            /*Provisioning the user to CRM*/
            $crm_first_name = array(
                "name" => "first_name",
                "value" => $first_name
            );
            $crm_last_name = array(
                "name" => "last_name",
                "value" => $last_name
            );
            $crm_email = array(
                "name" => "email1",
                "value" => $email
            );
            $crm_phone_mobile = array(
                "name" => "phone_mobile",
                "value" => $mobile_number
            );

            $crm_age = array(
                "name" => "age_c",
                "value" => $age
            );

            $crm_gender = array(
                "name" => "gender_c",
                "value" => $gender
            );

            $crm_address_street = array(
                "name" => "primary_address_street",
                "value" => $street
            );

            $crm_address_city = array(
                "name" => "primary_address_city",
                "value" => $city
            );

            $crm_address_state = array(
                "name" => "primary_address_state",
                "value" => $state
            );

            $crm_address_country = array(
                "name" => "primary_address_country",
                "value" => $country
            );

            $crm_address_postalcode = array(
                "name" => "primary_address_postalcode",
                "value" => $postalcode
            );

            $crm_registration_data = array(
                $crm_first_name,
                $crm_last_name,
                $crm_email,
                $crm_phone_mobile,
                $crm_age,
                $crm_gender,
                $crm_address_street,
                $crm_address_city,
                $crm_address_state,
                $crm_address_country,
                $crm_address_postalcode);
            
            registerCRMUser($crm_registration_data);
            echo "New records created successfully";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if ($obj->action == 'user_mb_reg') {
    $tenantid = 15 ;
    try {
        $stmt = $conn->prepare("INSERT INTO wf_subscribers (
              tenantid,
              username,
              password,
              email,
              first_name,
              last_name,
              mobile_number,
              marital_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("isssssss", $tenantid, $username, $password, $email, $first_name, $last_name,$mobile_number, $marital_status);

//      account_status, gender, birthday, age, age_upper, age_lower, religion, occupation, profile_image, admin_notes
//      $account_status, $first_name, $last_name, $gender, $birthday, $age, $age_upper, $age_lower, $religion, $occupation, $marital_status, $profile_image, $mobile_number, $admin_note

        $username = isset($obj->username) ? $obj->username : '';
        $email = isset($obj->email) ? $obj->email : '';
        $password = isset($obj->password) ? $obj->password : '';
        $tenantid = 15;
        $first_name = isset($obj->first_name) ? $obj->first_name : '';
        $last_name = isset($obj->last_name) ? $obj->last_name : '';
        $mobile_number = isset($obj->phone) ? $obj->phone : '';
        $marital_status = isset($obj->marital) ? $obj->marital : '';
        $age = isset($obj->age) ? $obj->age : 0;
        $gender = isset($obj->gender) ? $obj->gender : '';

        $street = isset($obj->street) ? $obj->street : '';
        $city = isset($obj->city) ? $obj->city : '';
        $state = isset($obj->state) ? $obj->state : '';
        $postalcode = isset($obj->postalcode) ? $obj->postalcode : '';
        $country = isset($obj->country) ? $obj->country : '';
        
        
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            /*Provisioning the user to CRM*/
            $crm_first_name = array(
                "name" => "first_name",
                "value" => $first_name
            );
            $crm_last_name = array(
                "name" => "last_name",
                "value" => $last_name
            );
            $crm_email = array(
                "name" => "email1",
                "value" => $email
            );
            $crm_phone_mobile = array(
                "name" => "phone_mobile",
                "value" => $mobile_number
            );
            $crm_age = array(
                "name" => "age_c",
                "value" => $age
            );

            $crm_gender = array(
                "name" => "gender_c",
                "value" => $gender
            );

            $crm_address_street = array(
                "name" => "primary_address_street",
                "value" => $street
            );

            $crm_address_city = array(
                "name" => "primary_address_city",
                "value" => $city
            );

            $crm_address_state = array(
                "name" => "primary_address_state",
                "value" => $state
            );

            $crm_address_country = array(
                "name" => "primary_address_country",
                "value" => $country
            );

            $crm_address_postalcode = array(
                "name" => "primary_address_postalcode",
                "value" => $postalcode
            );

            $crm_registration_data = array(
                $crm_first_name,
                $crm_last_name,
                $crm_email,
                $crm_phone_mobile,
                $crm_age,
                $crm_gender,
                $crm_address_street,
                $crm_address_city,
                $crm_address_state,
                $crm_address_country,
                $crm_address_postalcode);

            registerCRMUser($crm_registration_data);
            echo "New records created successfully";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
