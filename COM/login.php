<?php
session_start(); // Start session to persist login state

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grt";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $name = $conn->real_escape_string($_POST['name']);
    $pass = $conn->real_escape_string($_POST['pass']);

    // Prepare SQL statement using a prepared statement
    $sql = "SELECT * FROM user WHERE firstname = ?"; // Assuming 'firstname' is your username field
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name); // Bind parameter (username)

    // Execute query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Fetch row
        $row = $result->fetch_assoc();
        $hashed_password = $row['hashed_password']; // Assuming 'hashed_password' is your hashed password field

        // Verify hashed password
        if (password_verify($pass, $hashed_password)) {
            // Valid credentials, set session variables and redirect
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $name;

            // Redirect to index.html or dashboard page after successful login
            header("Location: index.html");
            exit(); // Ensure no further output is sent
        } else {
            // Invalid password
            echo "<script>alert('Invalid username or password'); window.location='login.html';</script>";
            exit();
        }
    } else {
        // No user found with the given username
        echo "<script>alert('Invalid username or password'); window.location='login.html';</script>";
        exit();
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
