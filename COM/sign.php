<?php
session_start(); // Start session to persist login state

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grt"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $conn->real_escape_string($_POST['fname']);
    $lastname = $conn->real_escape_string($_POST['lname']);
    $email = $conn->real_escape_string($_POST['email']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $password = $conn->real_escape_string($_POST['password']);

    // Validate inputs (basic validation, you may want to add more)
    if (empty($firstname) || empty($lastname) || empty($email) || empty($mobile) || empty($password)) {
        echo "All fields are required. <a href='signup.html'>Back to sign up</a>";
        exit();
    }

    // H

    // Check if email or mobile already exists
    $sql_check = "SELECT * FROM user WHERE email = '$email' OR mobile = '$mobile'";
    $result_check = $conn->query($sql_check);
    if ($result_check->num_rows > 0) {
        echo "Email or Mobile number already exists. <a href='signup.html'>Back to sign up</a>";
        exit();
    }

    // Prepare and execute SQL statement
    $sql = "INSERT INTO user (firstname, lastname, email, mobile, hashed_password) 
            VALUES ('$firstname', '$lastname', '$email', '$mobile', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful. <a href='login.html'>Proceed to login</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>
