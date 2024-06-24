<?php
if (!isset($pageName))
  $pageName = '';
?>
<style>
  /* .container-85 {
    width: 100%;
    margin: auto;
  } */
  .li-hover a:hover {
    color: white;
    transform: scale(1.3);
    font-weight: 900;
    background-color: #BEBEBE;
  }

  .li-hover {
    overflow: hidden;
  }

  .logo {
    width: 200px;
    height: 70px;
    background-color: aliceblue;
  }

  .bg-color {
    background-color: #EEA9A9;
  }


  /* 增加XX資料前面的icon css */
  .bi-person-plus-fill {
    color: #00BB00;
    /* font-weight: 900; */
    font-size: 1.5em;
  }


  .bi-search {
    color: #005AB5;
    /* font-weight: 900; */
    font-size: 1.5em;
  }

  /* Active */

  .dropdown-item.active {
    color: #fff;
    text-decoration: none;
    background-color: #0d6efd;
    font-size: 1.3em;
    font-weight: 900;
  }

  .bglightblue {
    background-color: #cfe2ff;
  }
</style>



<div class="container-fluid p-0">
  <div class="row m-0">
    <div class="col-12 p-0 ">

      <ul class="nav flex-column text-center">
        <!-- 1 -->
        <div class="accordion" id="accordionExample">
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading1">
              <button class="accordion-button fs-5 fw-bold <?= $pageName == 'members-add' || $pageName == 'members-list' ? '' : 'bg-white' ?> " type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
                會員資料管理
              </button>
            </h2>
            <div id="collapse1" class="accordion-collapse collapse <?= $pageName == 'members-add' || $pageName == 'members-list' ? 'show' : '' ?> " aria-labelledby="heading1" data-bs-parent="#accordionExample">
              <div class="accordion-body p-0"> <!-- p-0 把li上下左右的空間清掉不要動-->
                <!-- members-add -->
                <li class="li-hover"><a class="dropdown-item py-3 border <?= $pageName == 'members-add' ? 'active' : '' ?> " href="members-add.php">
                    <i class="bi bi-person-plus-fill "></i><span class="ms-2">增加會員資料</span></a>
                </li>
                <!-- members-list -->
                <li class="li-hover"><a class="dropdown-item py-3 border <?= $pageName == 'members-list' ? 'active' : '' ?>" href="members-list.php">
                    <i class="bi bi-search"></i><span class="ms-2">查詢會員資料</span></a>
                </li>
              </div>
            </div>
          </div>

          <!-- 2 -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading2">
              <button class="accordion-button collapsed fs-5 fw-bold <?= $pageName == 'employees-add' || $pageName == 'employees-list' ? '' : 'bg-white' ?> " type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                員工資料管理
              </button>
            </h2>
            <div id="collapse2" class="accordion-collapse collapse <?= $pageName == 'employees-add' || $pageName == 'employees-list' ? 'show' : '' ?> " aria-labelledby="heading2" data-bs-parent="#accordionExample">
              <div class="accordion-body p-0"> <!-- p-0 把li上下左右的空間清掉不要動-->
                <li class="li-hover"><a class="dropdown-item py-3 border <?= $pageName == 'employees-add' ? 'active' : '' ?> " href="employees-add.php"><i class="bi bi-person-plus-fill"></i><span class="ms-2">增加員工資料</span></a>
                </li>
                <!-- <li class="li-hover"><a class="dropdown-item py-3 border <?= $pageName == 'employees-delete' ? 'active' : '' ?> " href="employees-delete-list.php">刪除員工資料</a></li>
                <li class="li-hover"><a class="dropdown-item py-3 border <?= $pageName == 'employees-edit' ? 'active' : '' ?> " href="employees-edit-list.php">編輯員工資料</a></li> -->
                <li class="li-hover"><a class="dropdown-item py-3 border <?= $pageName == 'employees-list' ? 'active' : '' ?> " href="employees-list.php"><i class="bi bi-search"></i><span class="ms-2">查詢員工資料</span></a></li>
              </div>
            </div>
          </div>
          <!-- 3 -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading3">
              <button class="accordion-button collapsed fs-5 fw-bold <?= $pageName == 'ticket-order-list' || $pageName == 'ticket-list' || $pageName == 'ticket-add' || $pageName == 'ticket-edit' ? '' : 'bg-white' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                購票資料管理
              </button>
            </h2>
            <div id="collapse3" class="accordion-collapse collapse <?= $pageName == 'ticket-order-list' || $pageName == 'ticket-list' || $pageName == 'ticket-add' || $pageName == 'ticket-edit' ? 'show' : '' ?>" aria-labelledby="heading3" data-bs-parent="#accordionExample">
              <div class="accordion-body p-0"> <!-- p-0 把li上下左右的空間清掉不要動-->
                <!-- <li class="li-hover"><a class="dropdown-item py-3 border <?= $pageName == 'ticket-order-list' ? 'active' : '' ?>" href="ticket-order-list.php" href="#">購票訂單</a></li> -->
                <li class="li-hover"><a class="dropdown-item py-3 border <?= $pageName == 'ticket-list' ? 'active' : '' ?>" href="ticket-list.php">上架購票清單</a></li>
                <li class="li-hover"><a class="dropdown-item py-3 border <?= $pageName == 'ticket-add' ? 'active' : '' ?>" href="ticket-add.php">新增購票</a></li>
              </div>
            </div>
          </div>

          <!-- 4 -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading4">
              <button class="accordion-button collapsed fs-5 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                通知資料管理
              </button>
            </h2>
            <div id="collapse4" class="accordion-collapse collapse <?= $pageName == 'notification-add' || $pageName == 'notification-list' ? 'show' : '' ?>" aria-labelledby="heading4" data-bs-parent="#accordionExample">
              <div class="accordion-body p-0"> <!-- p-0 把li上下左右的空間清掉不要動-->
                <li class="li-hover">
                  <a class="dropdown-item py-3 border <?= $pageName == 'notification-list' ? 'active' : '' ?>" href="notification-list.php">通知列表</a>
                </li>
                <li class="li-hover">
                  <a class="dropdown-item py-3 border <?= $pageName == 'notification-add' ? 'active' : '' ?>" href="notification-add.php">新增通知</a>
                </li>
              </div>
            </div>
          </div>

          <!-- 5 -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading5">
              <button class="accordion-button collapsed fs-5 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                活動資料管理
              </button>
            </h2>
            <div id="collapse5" class="accordion-collapse collapse <?= $pageName == 'activities-add' || $pageName == 'activities-list' ? 'show' : '' ?>" aria-labelledby="heading5" data-bs-parent="#accordionExample">
              <div class="accordion-body p-0"> <!-- p-0 把li上下左右的空間清掉不要動-->
                <li class="li-hover">
                  <a class="dropdown-item py-3 border <?= $pageName == 'activities-list' ? 'active' : '' ?>" href="activities-list.php">活動列表</a>
                </li>
                <li class="li-hover">
                  <a class="dropdown-item py-3 border <?= $pageName == 'activities-add' ? 'active' : '' ?>" href="activities-add.php">新增活動</a>
                </li>
              </div>
            </div>
          </div>

          <!-- 6 -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading6">
              <button class="accordion-button collapsed fs-5 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                藝人資料管理
              </button>
            </h2>
            <div id="collapse6" class="accordion-collapse collapse" aria-labelledby="heading6" data-bs-parent="#accordionExample">
              <div class="accordion-body p-0"> <!-- p-0 把li上下左右的空間清掉不要動-->
                <li class="li-hover"><a class="dropdown-item py-3 border" href="artist-add.php">新增藝人資料</a></li>
                <li class="li-hover"><a class="dropdown-item py-3 border" href="artist-edit.php">編輯藝人資料</a></li>
                <li class="li-hover"><a class="dropdown-item py-3 border" href="artist-list.php">查詢藝人資料</a></li>
              </div>
            </div>
          </div>
          <!-- 7 -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading7">
              <button class="accordion-button collapsed fs-5 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse7" aria-expanded="false" aria-controls="collapse7">
                商品資料管理
              </button>
            </h2>
            <div id="collapse7" class="accordion-collapse collapse" aria-labelledby="heading7" data-bs-parent="#accordionExample">
              <div class="accordion-body p-0"> <!-- p-0 把li上下左右的空間清掉不要動-->
                <li class="li-hover"><a class="dropdown-item py-3 border  " href="product-add.php">增加商品資料</a></li>
                <!-- <li class="li-hover"><a class="dropdown-item py-3 border" href="product-list.php">編輯與刪除商品資料</a></li> -->
                <li class="li-hover"><a class="dropdown-item py-3 border" href="product-list.php">查詢商品資料</a></li>
              </div>
            </div>
          </div>
          <!-- 8 -->
          <!-- <div class="accordion-item">
            <h2 class="accordion-header" id="heading8">
              <button class="accordion-button collapsed fs-5 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse8" aria-expanded="false" aria-controls="collapse8">
                訂單資料管理
              </button>
            </h2>
            <div id="collapse8" class="accordion-collapse collapse" aria-labelledby="heading8" data-bs-parent="#accordionExample">
              <div class="accordion-body p-0"> <!-- p-0 把li上下左右的空間清掉不要動-->
          <!-- <li class="li-hover"><a class="dropdown-item py-3 border  " href="#">增加訂單資料</a></li>
                <li class="li-hover"><a class="dropdown-item py-3 border" href="#">刪除訂單資料</a></li>
                <li class="li-hover"><a class="dropdown-item py-3 border" href="#">編輯訂單資料</a></li>
                <li class="li-hover"><a class="dropdown-item py-3 border" href="#">查詢訂單資料</a></li>
              </div>
            </div>
          </div> -->
        </div>
      </ul>
    </div>
  </div>
</div>