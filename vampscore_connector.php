<?php

include 'DatabaseConnection.php';

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
        $query = "SELECT username, email FROM wf_users WHERE email='" . $email . "' and password = '" . $password . "'";
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

$data = $HTTP_RAW_POST_DATA;
$obj = json_decode($data);

if ($obj->action == 'user_registration') {

    $userdetails = $obj->contact;
    $user = $obj->user;

    try {
        $stmt = $conn->prepare("INSERT INTO wf_users (
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
        $tenantid = 1;
        $first_name = isset($userdetails->first_name) ? $userdetails->first_name : '';
        $last_name = isset($userdetails->last_name) ? $userdetails->last_name : '';
        $mobile_number = isset($userdetails->phone) ? $userdetails->phone : '';
        $marital_status = isset($userdetails->marital) ? $userdetails->marital : '';



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
            echo "New records created successfully";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}