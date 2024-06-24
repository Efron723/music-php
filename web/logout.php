<?php
session_start();
unset($_SESSION['admin']);
header('Location: index_R3.php'); // 按下登出後跳轉的頁面
