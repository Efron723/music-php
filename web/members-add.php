<?php
require __DIR__ . '/admin-required.php';

if (!isset($_SESSION)) {
  session_start();
}

$title = '新增會員列表';
$pageName = 'members-add';

?>

<?php include __DIR__ . "/part/html-header.php"; ?>
<?php include __DIR__ . "/part/navbar-head.php"; ?>

<style>
  form .mb-3 .form-text {
    color: red;
    font-weight: 800;
  }

  .bi-x-circle-fill {
    color: red;
  }
</style>


<div class="container-fluid">
  <div class="row">
    <div class="col-2 p-0"><?php include __DIR__ . "/part/left-bar.php"; ?></div>
    <div class="col-10">
      <!-- NEW START-->
      <div class="row">
        <div class="col-8 mx-auto border rounded-3 my-3 bg-white shadow">
          <h4 class="my-3">新增會員</h4>

          <!-- 錯誤提示訊息 -->
          <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content border border-danger border-2 ">
                <div class="modal-header">
                  <h5 class="modal-title" id="errorModalLabel"><i class="bi bi-x-circle-fill"></i><span class="ms-3">錯誤提示</span></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <p id="errorModalMessage"></p>
                </div>
              </div>
            </div>
          </div>

          <form name="form1" onsubmit="sendData(event)" class="needs-validation" novalidate>
            <div class="row g-3">
              <div class="col-sm-6">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
                <div class="invalid-feedback">
                  Valid first name is required.
                </div>
              </div>



              <div class="col-12">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required>
                <div class="invalid-feedback">
                  Please enter a valid email address for shipping updates.
                </div>
              </div>

              <div class="col-12">
                <label for="passwords" class="form-label">Passwords</label>
                <input type="password" class="form-control" id="passwords" name="passwords" placeholder="" required>
                <div class="invalid-feedback">
                  Please enter a valid password for shipping updates.
                </div>
              </div>

              <!-- gender -->
              <div class="col-4">
                <label for="gender" class="form-label">Gender</label><br>
                <select class="form-select" id="gender" name="gender" required>
                  <option value="" selected disabled>Select gender</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
                <div class="invalid-feedback">
                  Gender required.
                </div>
              </div>


              <!-- phone number -->
              <div class="col-4">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="09xxxxxxxx" pattern="[0]{1}[9]{1}[0-9]{8}" maxlength="10" required>
                <div class="invalid-feedback">
                  Phone number required.
                </div>
              </div>
              <!-- birthday -->
              <div class="col-4">
                <label for="birthday" class="form-label">Birthday</label>
                <input type="date" class="form-control" id="birthday" name="birthday" placeholder="" required>
                <div class="invalid-feedback">
                  Birthday required.
                </div>
              </div>
              <!-- address -->
              <div class="col-12">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St" required>
                <div class="invalid-feedback">
                  Please enter your shipping address.
                </div>
              </div>



              <div class="col-12">
                <label for="photo" class="form-label">Photo:</label>
                <input type="file" class="form-control" id="photo" name="photo" onchange="previewPhoto(event)">
                <img id="photoPreview" class="mt-2" style="max-width: 200px; display: none;">
              </div>


            </div>
            <div class="text-end">
              <button class="btn btn-primary my-3" type="submit">新增</button>
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
        <h1 class="modal-title fs-5" id="staticBackdropLabel">新增成功</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">
          資料新增成功
        </div>
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-primary" onclick="location.href='members-list.php'">到列表頁</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="keepAdd">繼續新增</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal End-->

<?php include __DIR__ . "/part/scripts.php"; ?>


<script>
  function previewPhoto(event) {
    const fileInput = event.target;
    const photoPreview = document.getElementById('photoPreview');

    if (fileInput.files && fileInput.files[0]) {
      const reader = new FileReader();

      reader.onload = function(e) {
        photoPreview.src = e.target.result;
        photoPreview.style.display = 'block';
      };

      reader.readAsDataURL(fileInput.files[0]);
    }
  }
  // (function() {
  //   'use strict'

  //   // Fetch all the forms we want to apply custom Bootstrap validation styles to
  //   var forms = document.querySelectorAll('.needs-validation')

  //   // Loop over them and prevent submission
  //   Array.prototype.slice.call(forms)
  //     .forEach(function(form) {
  //       form.addEventListener('submit', function(event) {
  //         if (!form.checkValidity()) {
  //           event.preventDefault()
  //           event.stopPropagation()
  //         }

  //         form.classList.add('was-validated')
  //       }, false)
  //     })
  // })()

  // 繼續新增
  const keepAdd = document.getElementById('keepAdd');
  const formControls = document.querySelectorAll('.form-control');

  keepAdd.addEventListener('click', function() {
    formControls.forEach(function(control) {
      control.value = ''; // 清空表單控件的值
    });
  });

  // const first_nameField = document.form1.first_name;
  // const last_nameField = document.form1.last_name;
  const emailField = document.form1.email;

  function validateEmail(email) {
    const re =
      /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }


  const sendData = e => {
    e.preventDefault(); // 不要讓 form1 以傳統的方式送出

    emailField.style.border = '1px solid #CCCCCC';
    emailField.nextElementSibling.innerText = '';

    // TODO: 欄位資料檢查
    let isPass = true; // 表單有沒有通過檢查

    if (!validateEmail(emailField.value)) {
      isPass = false;
      emailField.style.border = '1px solid red';
      emailField.nextElementSibling.innerText = '請填寫正確的 Email';
    }
    // 有通過檢查, 才要送表單
    // if (isPass) {
    //   const fd = new FormData(document.form1); // 沒有外觀的表單物件
    //   fetch('members-add-api.php', {
    //       method: 'POST',
    //       body: fd, // Content-Type: multipart/form-data
    //     }).then(r => r.json())
    //     .then(data => {
    //       console.log(data);
    //       if (data.success) {
    //         myModal.show();
    //       } else {

    //       }
    //     })
    //     .catch(ex => console.log(ex))
    // }
    //信箱驗證
    if (isPass) {
      const fd = new FormData(document.form1); // 創建 FormData 对象
      fetch('members-add-api.php', {
          method: 'POST',
          body: fd, // 表单数据
        })
        .then(response => response.json())
        .then(data => {
          console.log(data);
          if (data.success) {
            myModal.show();
          } else {
            // 处理后端返回的错误消息
            if (data.message) {
              // 使用 Bootstrap 的模态框显示错误消息
              const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
              document.getElementById('errorModalMessage').innerText = data.message;
              errorModal.show();
            }
          }
        })
        .catch(ex => console.log(ex));
    }

  };
  const myModal = new bootstrap.Modal('#staticBackdrop')
</script>
<?php include __DIR__ . "/part/html-footer.php"; ?>