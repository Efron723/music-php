<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

$title = '修改會員通訊錄資料';
$pageName = 'members-edit';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id < 1) {
  header('Location: members-edit-list.php');
  exit;
}

$sql = "Select * FROM `members` WHERE id=$id";
$row = $pdo->query($sql)->fetch();

if (empty($row)) {
  header('Location: members-edit-list.php');
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

<div class="container-fluid">
  <div class="row">
    <div class="col-2 p-0"><?php include __DIR__ . "/part/left-bar.php"; ?></div>
    <div class="col-10">
      <!-- NEW START-->
      <div class="row">
        <div class="col-8 mx-auto border rounded-3 my-3 bg-white shadow">
          <h4 class="my-3">編輯會員</h4>
          <form name="form1" onsubmit="sendData(event)" class="needs-validation" novalidate enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">


            <div class="row g-3">

              <div class="mb-3">
                <label for="id" class="form-label">編號</label>
                <input type="text" class="form-control" id="id" name="id" value="<?= $row['id'] ?>" disabled>
              </div>




              <div class="col-sm-6">
                <label for="name" class="form-label">First name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $row['name'] ?>" required>
                <div class="invalid-feedback">
                  Valid first name is required.
                </div>
              </div>

              <!-- Email Start -->

              <div class="col-12">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" value="<?= $row['email'] ?>" required>
                <div class="invalid-feedback">
                  Please enter a valid email address for shipping updates.
                </div>
              </div>
              <!-- Email End -->

              <!-- Passwords Start -->
              <div class="col-12">
                <label for="passwords" class="form-label">Passwords</label>
                <input type="password" class="form-control" id="passwords" name="passwords" value="<?= $row['passwords'] ?>" required>
                <div class="invalid-feedback">
                  Please enter a valid password for shipping updates.
                </div>
              </div>
              <!-- Passwords End -->

              <!-- Gender Start -->
              <div class="col-4">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select" id="gender" name="gender" required>
                  <option value="" disabled>Select gender</option>
                  <option value="Male" <?= $row['gender'] === 'Male' ? 'selected' : '' ?>>男 - Male</option>
                  <option value="Female" <?= $row['gender'] === 'Female' ? 'selected' : '' ?>>女 - Female</option>
                </select>
              </div>
              <!-- Gender End -->

              <div class="col-4">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="09xxxxxxxx" pattern="[0]{1}[9]{1}[0-9]{8}" maxlength="10" value="<?= $row['phone_number'] ?>" required>
                <div class="invalid-feedback">
                  Phone number required.
                </div>
              </div>


              <div class="col-4">
                <label for="birthday" class="form-label">Birthday</label>
                <input type="date" class="form-control" id="birthday" name="birthday" value="<?= $row['birthday'] ?>" required>
                <div class="invalid-feedback">
                  Birthday required.
                </div>
              </div>


              <div class="col-12">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?= $row['address'] ?>" required>
                <div class="invalid-feedback">
                  address required.
                </div>
              </div>




              <div class="col-6">
                <label for="photo" class="form-label">原始照片</label><br>
                <img src="../img/members-img/<?= $row['photo'] ?>" alt="原始照片" class="w-50">
              </div>
              <div class="col-6">

                <label for="photo" class="form-label">選擇新照片:</label>
                <input type="file" id="photo" name="photo" accept="image/*" onchange="previewNewPhoto(event)" class="form-control">
                <br>
                <img src="" alt="新照片預覽" id="newPhotoPreview" class="w-50" style="display: none;">
              </div>





            </div>
            <div class="text-end">
              <button class="btn btn-primary my-3" type="submit">更新</button>
              <button class="btn btn-secondary my-3" type="reset">清除</button>
            </div>
          </form>
        </div>
      </div>
      <!-- NEW END -->
    </div>
  </div>

</div>

<!-- Modal Start-->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">更新成功</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">
          資料更新成功
        </div>
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-primary" onclick="location.href='members-list-admin.php'">到列表頁</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續更新</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal End-->

<!-- Modal Fail Start-->
<div class="modal fade" id="staticBackdropfail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabelfail" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabelfail">更新失敗</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert">
          資料更新失敗
        </div>
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-primary" onclick="location.href='members-list-admin.php'">到列表頁</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續更新</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal End-->


<?php include __DIR__ . "/part/scripts.php"; ?>
<script>
  //img
  function previewNewPhoto(event) {
    const fileInput = event.target;
    const preview = document.getElementById('newPhotoPreview');

    if (fileInput.files && fileInput.files[0]) {
      const reader = new FileReader();

      reader.onload = function(e) {
        preview.src = e.target.result;
        preview.style.display = 'block';
      };

      reader.readAsDataURL(fileInput.files[0]);
    }
  };







  const nameField = document.form1.name;

  const emailField = document.form1.email;

  function validateEmail(email) {
    const re =
      /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }


  const sendData = e => {
    e.preventDefault(); // 不要讓 form1 以傳統的方式送出

    // first_nameField.style.border = '1px solid #CCCCCC';
    // first_nameField.nextElementSibling.innerText = '';
    // last_nameField.style.border = '1px solid #CCCCCC';
    // last_nameField.nextElementSibling.innerText = '';
    emailField.style.border = '1px solid #CCCCCC';
    emailField.nextElementSibling.innerText = '';
    // TODO: 欄位資料檢查

    let isPass = true; // 表單有沒有通過檢查
    // if (first_nameField.value.length < 2) {
    //   isPass = false;
    //   first_nameField.style.border = '1px solid red';
    //   first_nameField.nextElementSibling.innerText = '請填寫正確的姓名';
    // }

    // if (last_nameField.value.length < 2) {
    //   isPass = false;
    //   last_nameField.style.border = '1px solid red';
    //   last_nameField.nextElementSibling.innerText = '請填寫正確的姓名';
    // }

    if (!validateEmail(emailField.value)) {
      isPass = false;
      emailField.style.border = '1px solid red';
      emailField.nextElementSibling.innerText = '請填寫正確的 Email';
    }
    // 有通過檢查, 才要送表單
    if (isPass) {
      const fd = new FormData(document.form1); // 沒有外觀的表單物件
      fetch('members-edit-api.php', {
          method: 'POST',
          body: fd, // Content-Type: multipart/form-data
        }).then(r => r.json())
        .then(data => {
          console.log(data);
          if (data.success) {
            myModal.show();
          } else {
            myModalfail.show();
          }
        })
        .catch(ex => console.log(ex))
    }


  };
  const myModal = new bootstrap.Modal('#staticBackdrop')
  const myModalfail = new bootstrap.Modal('#staticBackdropfail')
</script>
<?php include __DIR__ . "/part/html-footer.php"; ?>