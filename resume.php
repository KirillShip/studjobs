<?php
session_start();

error_reporting(0);
ini_set('display_errors', 0);

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: index.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "studjobs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$student_id = $_SESSION['user'];
$resume_content = "";

// Проверка, есть ли резюме
$sql = "SELECT content FROM Resumes WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $resume_content = $row['content']; // Загружаем текущее резюме
}

// Обрабатываем отправку формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_content = $conn->real_escape_string($_POST['resume_content']);

    if ($result->num_rows > 0) {
        // Обновляем резюме
        $update_sql = "UPDATE Resumes SET content = ? WHERE student_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $new_content, $student_id);
        $update_stmt->execute();
    } else {
        // Создаем новое резюме
        $insert_sql = "INSERT INTO Resumes (student_id, content) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("is", $student_id, $new_content);
        $insert_stmt->execute();
    }

    header("Location: resume.php");
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Резюме</title>
    <!-- Подключение Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-4">Редактирование резюме</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="resume_content" class="form-label">Ваше резюме:</label>
                <textarea class="form-control" id="resume_content" name="resume_content" rows="10"><?php echo htmlspecialchars($resume_content); ?></textarea>
            </div>
            <div class="d-flex justify-content-between">
                <a href="main.php" class="btn btn-secondary">Назад</a>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
<div class="text-center mt-4">
    <a href="logout.php" class="btn btn-danger">Выйти</a>
</div>
</body>
</html>
