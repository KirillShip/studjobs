<?php
session_start();
require_once 'error_handler.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    handle_error(
        401,
        "Unauthorized: User is not authenticated or doesn't have the required role.",
        "У вас нет доступа к этой странице. Пожалуйста, войдите как студент.",
        ["required_role" => "student"]
    );
}

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "studjobs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    handle_error(
        500,
        "Internal Server Error: Database query execution failed.",
        "Произошла ошибка на сервере. Попробуйте позже.",
        ["error_details" => $conn->connect_error]
    );
}

$student_id = $_SESSION['user'];
$job_id = $_GET['job_id'] ?? null;

if (!$job_id) {
    handle_error(
        400,
        "Validation Error: Missing required field 'job_id'.",
        "Пожалуйста, выберите вакансию для отклика.",
        ["missing_field" => "job_id"]
    );
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cover_letter = $conn->real_escape_string($_POST['cover_letter']);
    
    // Сохраняем сопроводительное письмо
    $sql = "INSERT INTO coverletters (student_id, content) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $student_id, $cover_letter);
    $stmt->execute();
    $cover_letter_id = $conn->insert_id; // Получение ID последней вставленной записи

    // Сохраняем заявку
    $sql = "INSERT INTO requests (student_id, job_id, cover_letter_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $student_id, $job_id, $cover_letter_id);
    $stmt->execute();

    // Перенаправление на страницу управления вакансиями
    header("Location: jobs.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Откликнуться</title>
    <!-- Подключение Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-4">Отклик на вакансию</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="cover_letter" class="form-label">Сопроводительное письмо</label>
                <textarea class="form-control" id="cover_letter" name="cover_letter" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Отправить заявку</button>
        </form>
    </div>
    <div class="text-center mt-4">
        <a href="logout.php" class="btn btn-danger">Выйти</a>
    </div>
</body>
</html>
