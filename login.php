<?php

$servername = "localhost";
$login = "root";
$password = "root";
$dbname = "studjobs";

$conn = new mysqli($servername, $login, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
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
        header("Location: http://dolg/main.html");
        exit();
    }

    $sql = "SELECT id FROM Students WHERE login='$login' AND password='$hashed_password'";
    $id = $conn->query($sql);

    if ($id->num_rows > 0) {
        session_start();
        $_SESSION['user'] = $id;
        $_SESSION['role'] = 'student';
        header("Location: http://dolg/main.html");
        exit();
    }

    echo "<div style='text-align: center; color: red;'>Неверный логин или пароль</div>";
    
}

$conn->close();
?>
