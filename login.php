<?php

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "studjobs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    $hashed_password = md5($password);

    $sql = "SELECT id FROM Admins WHERE username='$username' AND password='$hashed_password'";
    $id = $conn->query($sql);

    if ($id->num_rows > 0) {
        session_start();
        $_SESSION['user'] = $id;
        header("Location: http://dolg/main.php");
        exit();
    } else {
        echo "<div style='text-align: center; color: red;'>Неверный логин или пароль</div>";
    }
}

$conn->close();
?>
