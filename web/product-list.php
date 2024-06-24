<?php
session_start();

if (isset($_SESSION['admin'])) {
  include __DIR__ . '/product-list-admin.php';
} else {
  include __DIR__ . '/product-list-no-admin.php';
}
