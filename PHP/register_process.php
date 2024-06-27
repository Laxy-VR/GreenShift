<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    // Include your PDO database connection file
    include_once "../db/dbconnection.php";

    // Function to validate email format
    function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Function to validate phone number format (10 digits)
    function validatePhoneNumber($phone)
    {
        return preg_match("/^\d{10}$/", $phone);
    }

    // Function to validate ZIP code format (1234ab)
    function validateZIPCode($zipCode)
    {
        return preg_match("/^\d{4}[a-zA-Z]{2}$/", $zipCode);
    }

    // Get form data
    $full_name = $_POST['name'];
    $street = $_POST['street'];
    $house_number = $_POST['housenumber'];
    $city = $_POST['city'];
    // $province = $_POST['province']; // Province can be left out
    $postal_code = $_POST['postal_code'];
    $phone = $_POST['phone'];
    // $age = $_POST['age']; // Change to date of birth
    $date_of_birth = $_POST['date_of_birth']; // Update variable name
    $email = $_POST['email'];
    // $username = $_POST['username']; // Remove username
    $password = $_POST['password']; // Note: You should hash this password before storing it in the database

    // Validate email format
    if (!validateEmail($email)) {
        echo "Invalid email format.";
        header('location: ../index.php?page=register');
        exit;
    }

    // Validate phone number format
    if (!validatePhoneNumber($phone)) {
        echo "Invalid phone number format.";
        header('location: ../index.php?page=register');
        exit;
    }

    // Validate ZIP code format
    if (!validateZIPCode($postal_code)) {
        echo "Invalid ZIP code format.";
        header('location: ../index.php?page=register');
        exit;
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetchColumn() > 0) {
        $post = $_POST;
        $data = array("post" => $post);
        $_SESSION['data'] = $data;

        $_SESSION['notification'] = 'Email not availible';
        header('location: ../index.php?page=register');
        exit;
    }

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Prepare SQL statement
        $stmt = $pdo->prepare("INSERT INTO users (full_name, street, house_number, city, postal_code, phone, date_of_birth, email, password_hash) 
                                VALUES (:full_name, :street, :house_number, :city, :postal_code, :phone, :date_of_birth, :email, :password_hash)");

        // Bind parameters
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':street', $street);
        $stmt->bindParam(':house_number', $house_number);
        $stmt->bindParam(':city', $city);
        // $stmt->bindParam(':province', $province); // No need to bind province
        $stmt->bindParam(':postal_code', $postal_code);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':date_of_birth', $date_of_birth); // Bind date of birth
        $stmt->bindParam(':email', $email);
        // $stmt->bindParam(':username', $username); // Remove username binding
        $stmt->bindParam(':password_hash', $password_hash);

        // Execute the statement
        $stmt->execute();

        // Success
        header('Location: ../?page=login');
    } catch (PDOException $e) {
        // Error message
        echo "Error: " . $e->getMessage();
    }

    // Close the connection
    $pdo = null;
}
?>
