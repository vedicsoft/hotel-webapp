<?php
/**
 * Created by IntelliJ IDEA.
 * User: Sandun
 * Date: 8/11/16
 * Time: 2:35 PM
 */

include 'DatabaseConnection.php';
include 'crm.php';

$instance =  DatabaseConnection::getInstance("vamps_hotel");
$conn = $instance->getConnection();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else{
    echo "Connected Succesfully";
}


// GET Requests

if (isset($_GET['command'])) {
    $command = $_GET['command'];
    if ($command == 'login') {
        $email = $_GET['email'];
        $password = $_GET['password'];
        $query = "SELECT username, email FROM users WHERE email='" . $email . "' and password = '" . $password . "'";
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

/*$json = file_get_contents('php://input');
  $obj = json_decode($json);*/

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['action']) && $_POST['action'] == 'update_status') {
        try {
            $updatestatus = "UPDATE h_booking SET status = ? WHERE username= ?";
            $stmt = $conn->prepare($updatestatus);

            $stmt->bind_param("ss",$is_arrived , $username);

            $username = isset($_POST['username']) ? $_POST['username'] : '';
            $is_arrived = isset($_POST['is_arrived']) ? $_POST['is_arrived'] : '';

            if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            } else {
                echo "New records created successfully";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'update_keyissued') {
        try {
            $updatestatus = "UPDATE h_booking SET key_issued = ? WHERE username= ?";
            $stmt = $conn->prepare($updatestatus);

            $stmt->bind_param("ss",$key_issued , $username);

            $username = isset($_POST['username']) ? $_POST['username'] : '';
            $key_issued = isset($_POST['key_issued']) ? $_POST['key_issued'] : '';

            if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            } else {
                echo "New records created successfully";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    // need to test

    $data = file_get_contents('php://input');
    $obj = json_decode($data);

if ($obj->action == 'hotel_booking') {

    $userdetails = $obj->contact;
    $userbooking = $obj->data;

    try {
        $stmt = $conn->prepare("INSERT INTO h_booking (username, email, check_in, check_out, nights, room_id, room_number, no_of_adults, no_of_childrens, pay_deposite) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssssiiiiii", $username, $email, $checkin, $checkout, $nights, $roomid, $room_number, $no_of_adults, $no_of_childrens, $deposit);

        $username = isset($userdetails->first_name) ? $userdetails->first_name : '';
        $email = isset($userdetails->email) ? $userdetails->email : '';
        $checkin = isset($userbooking->{'gdlr-check-in'}) ? $userbooking->{'gdlr-check-in'} : null;
        $checkout = isset($userbooking->{'gdlr-check-out'}) ? $userbooking->{'gdlr-check-out'} : null;
        $nights = isset($userbooking->{'gdlr-night'}) ? $userbooking->{'gdlr-night'} : 0;
        $roomid = isset($userbooking->{'gdlr-room-id[]'}) ? $userbooking->{'gdlr-room-id[]'} : 0;
        $room_number = isset($userbooking->{'gdlr-room-number'}) ? $userbooking->{'gdlr-room-number'} : 0;
        $no_of_adults = isset($userbooking->{'gdlr-adult-number[]'}) ? $userbooking->{'gdlr-adult-number[]'} : 0;
        $no_of_childrens = isset($userbooking->{'gdlr-children-number[]'}) ? $userbooking->{'gdlr-children-number[]'} : 0;
        $deposit = isset($userbooking->{'pay_deposit'}) ? $userbooking->{'pay_deposit'} : 0;

        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            $crm_username_2 = array(
                "name" => "name",
                "value" => $username
            );
            $crm_roomtype = array(
                "name" => "roomtype",
                "value" => "king"
            );
            $crm_checkin = array(
                "name" => "checkin",
                "value" => $checkin
            );
            $crm_checkout = array(
                "name" => "checkout",
                "value" => $checkout
            );
            $crm_no_of_adults = array(
                "name" => "no_of_adults",
                "value" => $no_of_adults
            );
            $crm_no_of_children = array(
                "name" => "no_of_children",
                "value" => $no_of_childrens
            );

            $reservation_data = array(
                $crm_username_2,
                $crm_roomtype,
                $crm_checkin,
                $crm_checkout,
                $crm_no_of_adults,
                $crm_no_of_children);

            pushReservationToCRM($reservation_data);
            echo "New records created successfully";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

$conn->close();
