<?php
session_start();

if (isset($_SESSION['admin'])) {
  include __DIR__ . '/employees-list-admin.php';
} else {
  include __DIR__ . '/employees-list-no-admin.php';
}
