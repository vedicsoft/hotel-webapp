<?php

include 'DatabaseConnection.php';


$instance =  DatabaseConnection::getInstance("yourhotel");
$conn = $instance->getConnection();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else{
    echo "Connected Succesfully";
}

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

$data = $HTTP_RAW_POST_DATA;
$obj = json_decode($data);

if ($obj->command == 'registration') {
    try {
        $stmt = $conn->prepare("INSERT INTO users ( username, email,password) VALUES (?,?,?)");

        $stmt->bind_param("sss", $username, $email, $password);

        $username = isset($obj->username) ? $obj->username : '';
        $email = isset($obj->email) ? $obj->email : '';
        $password = isset($obj->password) ? $obj->password : '';

        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            echo "New records created successfully";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_POST['item'])) {
    if ($_POST['item'] == "pizza") {
        $item = $_POST['item'];
        $item_category = $_POST['item_category'];
        $price = $_POST['price'];
        $item_quantity = $_POST['item_quantity'];
        $payment_method = $_POST['payment_method'];
        $discount = $_POST['discount'];
        $sales_tax = $_POST['sales_tax'];

        echo "Item : " . $item . "  Item_category : " . $item_category . "Price : " . $price . "  Qty : " . $item_quantity;

        /*echo $item + " " + $item_category + " " + $price + " " + $price_quantity + " " + $payment_method + " " + $discount + " " + $sales_tax;
*/
        /*$stmt = $conn->prepare("INSERT INTO foods (item,price,discount) VALUES (?,?,?)");

        $stmt->bind_param("dddd",$item,$price,$discount);

        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }else{
            echo "New records created successfully";
        }  	*/
    }
}
$conn->close();
?>