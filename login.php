<?php

require_once 'error_handler.php';

$servername = "localhost";
$login = "root";
$password = "root";
$dbname = "studjobs";

$conn = new mysqli($servername, $login, $password, $dbname);

if ($conn->connect_error) {
    handle_error(
        500,
        "Internal Server Error: Database query execution failed.",
        "Произошла ошибка на сервере. Попробуйте позже.",
        ["error_details" => $conn->connect_error]
    );
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $login = $conn->real_escape_string($login);
    $password = $conn->real_escape_string($password);

    $hashed_password = md5($password);

    $sql = "SELECT id FROM Admins WHERE login='$login' AND password='$hashed_password'";
    $id = $conn->query($sql);

    if ($id->num_rows > 0) {
        session_start();
        $_SESSION['user'] = $id;
        $_SESSION['role'] = 'admin';
        header("Location: http://dolg/main.php");
        exit();
    }

    $sql = "SELECT id FROM Students WHERE login='$login' AND password='$hashed_password'";
    $id = $conn->query($sql);

    if ($id->num_rows > 0) {
        session_start();
        $_SESSION['user'] = $id;
        $_SESSION['role'] = 'student';
        header("Location: http://dolg/main.php");
        exit();
    }

    $sql = "SELECT id FROM Customers WHERE login='$login' AND password='$hashed_password'";
    $id = $conn->query($sql);

    if ($id->num_rows > 0) {
        session_start();
        $_SESSION['user'] = $id;
        $_SESSION['role'] = 'customer';
        header("Location: http://dolg/main.php");
        exit();
    }

    echo "<div style='text-align: center; color: red;'>Неверный логин или пароль</div>";
    
}

$conn->close();
?>
