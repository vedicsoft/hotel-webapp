<?php
/**
 * Created by IntelliJ IDEA.
 * User: Sandun
 * Date: 8/11/16
 * Time: 2:35 PM
 */

include 'DatabaseConnection.php';
include 'crm.php';

$instance = DatabaseConnection::getInstance("vamps_hotel");
$conn = $instance->getConnection();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['action']) && $_POST['action'] == 'update_status') {
        try {
            $updatestatus = "UPDATE h_booking SET status = ? WHERE username= ?";
            $stmt = $conn->prepare($updatestatus);

            $stmt->bind_param("ss", $is_arrived, $username);

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

            $stmt->bind_param("ss", $key_issued, $username);

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

// table reserve

    if (isset($_POST['action']) && $_POST['action'] == 'table_reserve') {
        echo "table reserve";
        try {
            $stmt = $conn->prepare("INSERT INTO h_restaurant ( table_number, seats, status, assignee, guest_name, guest_requests, reserved_from, notes, last_updated) VALUES (?,?,?,?,?,?,?,?,NOW())");

            $stmt->bind_param("iissssss", $table_number, $seats, $status, $assignee, $guest_name, $guest_requests, $reserved_from, $notes);

            $table_number = isset($_POST['table_number']) ? $_POST['table_number'] : '';
            $seats = isset($_POST['seats']) ? $_POST['seats'] : '';
            $status = isset($_POST['status']) ? $_POST['status'] : '';
            $assignee = isset($_POST['assignee']) ? $_POST['assignee'] : '';
            $guest_name = isset($_POST['guest_name']) ? $_POST['guest_name'] : '';
            $guest_requests = isset($_POST['guest_requests']) ? $_POST['guest_requests'] : '';
            $reserved_from = isset($_POST['reserved_from']) ? $_POST['reserved_from'] : '';
            $notes = isset($_POST['notes']) ? $_POST['notes'] : '';

            if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            } else {
                echo "New records created successfully";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

// house keeping

    if (isset($_POST['action']) && $_POST['action'] == 'housekeep') {

        try {
            $stmt = $conn->prepare("INSERT INTO h_cleaning ( room_number, status, assignee, guest_requests, notes, last_updated) VALUES (?,?,?,?,?,NOW())");

            $stmt->bind_param("issss", $room_number, $status, $assignee, $guest_requests, $notes);

            $room_number = isset($_POST['room_number']) ? $_POST['room_number'] : '';
            $status = isset($_POST['status']) ? $_POST['status'] : '';
            $assignee = isset($_POST['assignee']) ? $_POST['assignee'] : '';
            $guest_requests = isset($_POST['guest_requests']) ? $_POST['guest_requests'] : '';
            $notes = isset($_POST['notes']) ? $_POST['notes'] : '';

            if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            } else {
                echo "New records created successfully";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'hotel_mb_booking') {
        try {
            $stmt = $conn->prepare("INSERT INTO h_booking (username, email, check_in, check_out, nights, room_id, room_number, no_of_adults, no_of_childrens, pay_deposite) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssiiiiii", $username, $email, $checkin, $checkout, $nights, $roomid, $room_number, $no_of_adults, $no_of_childrens, $deposit);

            $username = isset($_POST['username']) ? $_POST['username'] : '';
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $in = isset($_POST['chek_in_time']) ? $_POST['chek_in_time'] : '';
            $checkin = date_create_from_format('d/M/Y:H:i:s', $in);
            $checkin->getTimestamp();
            $out = isset($_POST['chek_out_time']) ? $_POST['chek_out_time'] : '';
            $checkout = date_create_from_format('d/M/Y:H:i:s', $out);
            $checkout->getTimestamp();
            $nights = isset($_POST['night-count']) ? $_POST['night-count'] : '';
            $roomid = isset($_POST['room_id']) ? $_POST['room_id'] : '';
            $room_number = isset($_POST['room_number']) ? $_POST['room_number'] : '';
            $no_of_adults = isset($_POST['no_of_adults']) ? $_POST['no_of_adults'] : '';
            $no_of_childrens = isset($_POST['no_of_childrens']) ? $_POST['no_of_childrens'] : '';
            $deposit = isset($_POST['deposit']) ? $_POST['deposit'] : '';

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
                    "name" => "no_of_adult",
                    "value" => $no_of_adults
                );
                $crm_no_of_children = array(
                    "name" => "no_of_children",
                    "value" => $no_of_childrens
                );
                $crm_assigned_user_id = array(
                    "name" => "assigned_user_id",
                    "value" => 1
                );

                $reservation_data = array(
                    $crm_username_2,
                    $crm_roomtype,
                    $crm_checkin,
                    $crm_checkout,
                    $crm_no_of_adults,
                    $crm_no_of_children,
                    $crm_assigned_user_id);
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
                    "name" => "no_of_adult",
                    "value" => $no_of_adults
                );
                $crm_no_of_children = array(
                    "name" => "no_of_children",
                    "value" => $no_of_childrens
                );
                $crm_assigned_user_id = array(
                    "name" => "assigned_user_id",
                    "value" => 1
                );

                $reservation_data = array(
                    $crm_username_2,
                    $crm_roomtype,
                    $crm_checkin,
                    $crm_checkout,
                    $crm_no_of_adults,
                    $crm_no_of_children,
                    $crm_assigned_user_id);
                echo "New records created successfully";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    $conn->close();
}
