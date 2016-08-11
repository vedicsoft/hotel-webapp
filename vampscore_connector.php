<?php
/**
 * Created by IntelliJ IDEA.
 * User: Sandun
 * Date: 8/11/16
 * Time: 2:35 PM
 */

include 'DatabaseConnection.php';

$instance =  DatabaseConnection::getInstance("vamps");
$conn = $instance->getConnection();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else{
    echo "Connected Succesfully";
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