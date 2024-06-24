<?php
session_start();

if (isset($_SESSION['admin'])) {
  include __DIR__ . '/activities-list-admin.php';
} else {
  include __DIR__ . '/activities-list-no-admin.php';
}
