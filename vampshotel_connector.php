<?php
/**
 * Created by IntelliJ IDEA.
 * User: Sandun
 * Date: 8/11/16
 * Time: 2:35 PM
 */

include 'DatabaseConnection.php';

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


// POST Requests

$data = $HTTP_RAW_POST_DATA;
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
            echo "New records created successfully";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

$conn->close();
?>