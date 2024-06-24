<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

$title = '修改藝人資料';
$pageName = 'artist-edit';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id < 1) {
  header('Location: artist-list.php');
  exit;
}

$sql = "Select * FROM `artist_register` WHERE id=$id";
$row = $pdo->query($sql)->fetch();

if (empty($row)) {
  header('Location: artist-list.php');
  exit;
}

// echo json_encode($row);


?>


<?php include __DIR__ . "/part/html-header.php"; ?>
<?php include __DIR__ . "/part/navbar-head.php"; ?>

<style>
  form .mb-3 .form-text {
    color: red;
    font-weight: 800;
  }
</style>

</style>
<div class="container-fluid">
  <div class="row">
    <div class="col-2 p-0">
    <?php include __DIR__ . "/part/left-bar.php"; ?></div>
    <div class="col-6 mx-auto ">
      <div class="card mt-3"> 
          <h5 class="card-title">編輯藝人資料</h5>
          <form name="form1" onsubmit="sendData(event)">
            <input type="hidden" name="id" value="<?= $r['id'] ?>">
            <div class="mb-3">
              <label for="art_name" class="form-label">藝名</label>
              <input type="text" class="form-control" id="art_name" name="art_name" value="<?= htmlentities($r['art_name']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="text" class="form-control" id="email" name="email" value="<?= $r['email'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">密碼</label>
              <input type="text" class="form-control" id="password" name="password" value="<?= $r['password'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="ManagementCompany" class="form-label">經紀公司</label>
              <input type="date" class="form-control" id="ManagementCompany" name="ManagementCompany" value="<?= $r['ManagementCompany'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="phone_number" class="form-label">電話號碼</label>
              <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= $r['phone_number'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="debutDate" class="form-label">出道日期</label>
              <input type="date" class="form-control" id="debutDate" name="debutDate" value="<?= $r['debutDate'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3 text-truncate">
              <label for="art_picture" class="form-label">藝人頭貼</label>
              <input type="text" class="form-control" id="art_picture" name="art_picture" value="<?= $r['art_picture'] ?>">
              <div class="form-text"></div>
            </div>
            <button type="submit" class="btn btn-primary">修改</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">修改結果</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">
          資料修改成功
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
        <button class="btn btn-primary" onclick="backToList()">回到列表頁</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="failureModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">資料沒有修改</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert" id="failureInfo">
          資料沒有修改
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
        <button class="btn btn-primary" onclick="backToList()">回到列表頁</button>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/part/scripts.php' ?>
<script>
  function backToList() {
    if (document.referrer) {
      location.href = document.referrer;
    } else {
      location.href = 'artist-list.php';
    }
  }

  const {
    name: nameEl,
    email: emailEl,
    phone_number: phone_numberEL,
  } = document.form1;

  const fields = [nameEl, emailEl, phone_numberEL];

  function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }

  function validateMobile(phone_number) {
    const re = /^09\d{2}-?\d{3}-?\d{3}$/;
    return re.test(phone_number);
  }

  function sendData(e) {
    // 回復欄位的外觀
    for (let el of fields) {
      el.style.border = '1px solid #CCC';
      el.nextElementSibling.innerHTML = '';
    }

    e.preventDefault(); // 不要讓表單以傳統的方式送出
    let isPass = true; // 整個表單有沒有通過檢查

    // TODO: 檢查各個欄位的資料, 有沒有符合規定
       if (emailEl.value && !validateEmail(emailEl.value)) {
      isPass = false;
      emailEl.style.border = '1px solid red';
      emailEl.nextElementSibling.innerHTML = '請填寫正確的 Email !';
    }

    if (mobileEl.value && !validateMobile(phone_numberEL.value)) {
      isPass = false;
      mobileEl.style.border = '1px solid red';
      mobileEl.nextElementSibling.innerHTML = '請填寫正確的手機號碼!';
    }

    // 有通過檢查才發送表單
    if (isPass) {
      const fd = new FormData(document.form1); // 沒有外觀的表單物件

      fetch(`artist-edit-api.php`, {
        method: 'POST',
        body: fd,
      }).then(r => r.json()).then(data => {
        console.log(data);
        if (data.success) {
          successModal.show();
        } else {
          document.querySelector('#failureInfo').innerHTML = data.error;
          failureModal.show();
        }

      })
    }
  }

  const successModal = new bootstrap.Modal('#successModal')
  const failureModal = new bootstrap.Modal('#failureModal')
</script>
<?php include __DIR__ . "/part/html-footer.php"; ?>