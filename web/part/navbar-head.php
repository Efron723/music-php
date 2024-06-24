<?php
if (!isset($pageName))
  $pageName = '';
?>
<style>
  .bd-placeholder-img {
    font-size: 1.125rem;
    text-anchor: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
  }

  @media (min-width: 768px) {
    .bd-placeholder-img-lg {
      font-size: 3.5rem;
    }
  }

  .bxl-apple {
    font-size: 60px;
  }

  * {
    list-style: none;
  }

  .white {
    color: white;
  }

  .white:hover {
    color: white;
  }
</style>

<header class="p-3 bg-dark text-white">
  <div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
      <a href="index_R3.php" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
        <!-- <i class='bx bxl-apple fs-2'></i> -->
        <i class="bi bi-music-note-list fs-2 mr-3"></i>
      </a>
      <h4 class="my-auto mx-2">後臺管理系統</h4>
      <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
      </ul>

      <!-- <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
        <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
      </form> -->

      <div class="text-end">
        <?php if (isset($_SESSION['admin'])): ?>
          <div class="d-flex">
            <div style="width: 120px; height: 38px" class="d-flex justify-content-center align-items-center">
              <a class="white nav-link">Dear&nbsp;&nbsp;<?= $_SESSION['admin']['first_name'] ?></a>
            </div>
            <div>
              <a class="btn btn-primary" href="logout.php">登出</a>
            </div>
          </div>
        <?php else: ?>
          <a type="button" class="btn btn-outline-light me-2 <?= $pageName == 'login' ? 'active' : '' ?>"
            href="login.php">Login</a>
          <a type="button" class="btn btn-warning <?= $pageName == 'register' ? 'active' : '' ?>"
            href="register.php">Sign-up</a>
        <?php endif ?>
      </div>
    </div>
  </div>


  <!-- JQuery Jin using -->
  <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
</header>