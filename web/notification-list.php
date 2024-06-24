<?php
session_start();

if (isset($_SESSION['admin'])) {
  include __DIR__ . '/notification-list-admin.php';
} else {
  include __DIR__ . '/notification-list-no-admin.php';
}
