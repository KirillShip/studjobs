<?php
session_start();
require_once 'error_handler.php';

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
    handle_error(
        500,
        "Internal Server Error: Database query execution failed.",
        "Произошла ошибка на сервере. Попробуйте позже.",
        ["error_details" => $conn->connect_error]
    );
}

// Получаем фильтры
$job_type_id = isset($_GET['job_type_id']) ? $_GET['job_type_id'] : null;
$job_category_id = isset($_GET['job_category_id']) ? $_GET['job_category_id'] : null;

// Формируем SQL-запрос
$sql = "SELECT Jobs.id, Jobs.title, Jobs.organization, Jobs.description, Jobs.skills, JobTypes.name AS type_name, JobCategories.name AS category_name 
        FROM Jobs 
        LEFT JOIN JobTypes ON Jobs.job_type_id = JobTypes.id 
        LEFT JOIN JobCategories ON Jobs.job_category_id = JobCategories.id
        WHERE 1=1";

if ($job_type_id) {
    $sql .= " AND Jobs.job_type_id = " . intval($job_type_id);
}

if ($job_category_id) {
    $sql .= " AND Jobs.job_category_id = " . intval($job_category_id);
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вакансии</title>
    <!-- Подключение Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-4">Список вакансий</h1>
        
        <!-- Фильтры -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-6">
                <label for="job_type_id" class="form-label">Тип работы</label>
                <select class="form-select" id="job_type_id" name="job_type_id">
                    <option value="">Все типы</option>
                    <?php
                    $types_result = $conn->query("SELECT id, name FROM jobtypes");
                    while ($type = $types_result->fetch_assoc()) {
                        $selected = ($job_type_id == $type['id']) ? 'selected' : '';
                        echo "<option value='{$type['id']}' $selected>{$type['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="job_category_id" class="form-label">Категория</label>
                <select class="form-select" id="job_category_id" name="job_category_id">
                    <option value="">Все категории</option>
                    <?php
                    $categories_result = $conn->query("SELECT id, name FROM jobcategories");
                    while ($category = $categories_result->fetch_assoc()) {
                        $selected = ($job_category_id == $category['id']) ? 'selected' : '';
                        echo "<option value='{$category['id']}' $selected>{$category['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary">Применить фильтры</button>
            </div>
        </form>

        <!-- Список вакансий -->
        <div class="row">
            <?php
            if ($result && $result->num_rows > 0) {
                while ($job = $result->fetch_assoc()) {
                    echo "
                    <div class='col-md-12 mb-4'>
                        <div class='card'>
                            <div class='card-body'>
                                <h5 class='card-title'>{$job['title']} ({$job['organization']})</h5>
                                <p class='card-text'><strong>Тип работы:</strong> {$job['type_name']}</p>
                                <p class='card-text'><strong>Категория:</strong> {$job['category_name']}</p>
                                <p class='card-text'><strong>Описание:</strong> {$job['description']}</p>
                                <p class='card-text'><strong>Навыки:</strong> {$job['skills']}</p>
                                <a href='apply.php?job_id={$job['id']}' class='btn btn-primary'>Откликнуться</a>
                            </div>
                        </div>
                    </div>
                    ";
                }
            } else {
                echo "<p class='text-center'>Вакансий не найдено.</p>";
            }
            ?>
        </div>
    </div>
    <div class="text-center mt-4">
        <a href="logout.php" class="btn btn-danger">Выйти</a>
    </div>
</body>
</html>
