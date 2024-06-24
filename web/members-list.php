<?php
session_start();

if (isset($_SESSION['admin'])) {
  include __DIR__ . '/members-list-admin.php';
} else {
  include __DIR__ . '/members-list-no-admin.php';
}
