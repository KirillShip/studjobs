<?php
session_start();
session_unset(); // Удаление переменных сессии
session_destroy(); // Завершение сессии
header("Location: index.html");
exit();
?>