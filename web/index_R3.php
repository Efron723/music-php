<?php
require __DIR__ . '/../config/pdo-connect.php';
if (!isset($_SESSION)) {
    session_start();
}
$title = 'Music Home';
$pageName = 'index_R3';
?>

<?php include __DIR__ . "/part/html-header.php"; ?>
<?php include __DIR__ . "/part/navbar-head.php"; ?>
<div class="container-fluid p-0">
    <div class="row m-0">
        <div class="col-2 p-0"><?php include __DIR__ . "/part/left-bar.php"; ?></div>
    </div>
</div>
<?php include __DIR__ . "/part/scripts.php"; ?>
<?php include __DIR__ . "/part/html-footer.php"; ?>