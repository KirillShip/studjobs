<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
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

$customer_id = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $organization = $_POST['organization'];
    $description = $_POST['description'];
    $skills = $_POST['skills'];
    $job_type_id = $_POST['job_type_id'];
    $job_category_id = $_POST['job_category_id'];

    $sql = "INSERT INTO jobs (customer_id, title, organization, description, skills, job_type_id, job_category_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssii", $customer_id, $title, $organization, $description, $skills, $job_type_id, $job_category_id);

    if ($stmt->execute()) {
        header("Location: main.php");
        exit();
    } else {
        $error_message = "Ошибка добавления вакансии: " . $stmt->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить вакансию</title>
    <!-- Подключение Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-4">Добавление вакансии</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Название вакансии</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="organization" class="form-label">Организация</label>
                <input type="text" class="form-control" id="organization" name="organization" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="skills" class="form-label">Ключевые навыки (через запятую)</label>
                <input type="text" class="form-control" id="skills" name="skills" required>
            </div>
            <div class="mb-3">
                <label for="job_type_id" class="form-label">Тип вакансии</label>
                <select class="form-select" id="job_type_id" name="job_type_id" required>
                    <option value="" disabled selected>Выберите тип вакансии</option>
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "root";
                    $dbname = "studjobs";
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("Ошибка подключения: " . $conn->connect_error);
                    }
                    $types_result = $conn->query("SELECT id, name FROM jobtypes");

                    if ($types_result && $types_result->num_rows > 0) {
                        while ($row = $types_result->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['name']}</option>";
                        }
                    }
                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="job_category_id" class="form-label">Категория вакансии</label>
                <select class="form-select" id="job_category_id" name="job_category_id" required>
                    <option value="" disabled selected>Выберите категорию вакансии</option>
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "root";
                    $dbname = "studjobs";
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("Ошибка подключения: " . $conn->connect_error);
                    }
                    $types_result = $conn->query("SELECT id, name FROM jobcategories");

                    if ($types_result && $types_result->num_rows > 0) {
                        while ($row = $types_result->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['name']}</option>";
                        }
                    }
                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="d-flex justify-content-between">
                <a href="main.php" class="btn btn-secondary">Назад</a>
                <button type="submit" class="btn btn-primary">Добавить вакансию</button>
            </div>
        </form>
    </div>
    <div class="text-center mt-4">
        <a href="logout.php" class="btn btn-danger">Выйти</a>
    </div>
</body>
</html>