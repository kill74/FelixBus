<?php
// Login process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $user["email"] === "admin@email.com" && $user["password"] === "246810" && $user["role"] === "admin") {
        // Admin login
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["role"] = "admin";
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}

// Admin dashboard
if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") {
    // Display admin-specific functionality
} else {
    header("Location: user_dashboard.php");
    exit();
}
?>