<?php

session_start();

$_SESSION['admin'] = [
    'id' => 1,
    'email' => 'jin@test.com',
    'first_name' => 'Jin',
  ];

header('Location: index_R3.php');