<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: login.html");
    exit();
}

$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная</title>
    <!-- Подключение Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<?php if ($role === 'admin'): ?>
    <div class="container py-5">
        <h1 class="text-center mb-4">Панель администратора</h1>
        <p class="text-center">Вы можете управлять вакансиями, просматривать заявки и управлять пользователями.</p>
        <div class="text-center">
            <a href="manage_jobs.php" class="btn btn-primary">Управление вакансиями</a>
        </div>
        <br>
        <div class="text-center">
            <a href="manage_students.php" class="btn btn-primary">Управление студентами</a>
        </div>
    </div>
<?php endif; ?>
<?php if ($role === 'student'): ?>
    <div class="container py-5">
        <h1 class="text-center mb-4">Добро пожаловать, студент!</h1>
        <p class="text-center">Здесь вы можете просматривать вакансии, подавать заявки и отслеживать их статус.</p>
        <div class="text-center">
            <a href="jobs.html" class="btn btn-primary">Посмотреть вакансии</a>
        </div>
        <br>
        <div class="text-center">
            <a href="resume.html" class="btn btn-primary">Составить резюме</a>
        </div>
    </div>
<?php endif; ?>
</body>
</html>
