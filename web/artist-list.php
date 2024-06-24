<?php
session_start();

if (isset($_SESSION['admin'])) {
    include __DIR__.'/artist-list-admin.php';
} else {
    include __DIR__.'/artist-list-no-admin.php';
}