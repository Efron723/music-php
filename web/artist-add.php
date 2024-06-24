<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';


if (!isset($_SESSION)) {
session_start();
}


$title = '新增藝人列表';
$pageName = 'add_artist';


$sql = sprintf("SELECT * FROM artist_register");
$rows = $pdo->query($sql)->fetchAll();

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
    <div class="col-2 p-0">
      <?php include __DIR__ . "/part/left-bar.php"; ?></div>
    <div class="col-6  mx-auto">
      <div class="card mt-3">
        <div class="card-body">
          <h5 class="card-title">新增藝人資料</h5>
          <form name="form1" action="javascript:void(0)" onsubmit="sendData(event)">
            <div class="mb-3">
              <label for="art_name" class="form-label">藝名</label>
              <input type="text" class="form-control" id="art_name" name="art_name">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email">
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">密碼</label>
              <input type="password" class="form-control" id="password" name="password">
              <div class="form-text"></div>
            </div>
            
            <div class="mb-3">
              <label for="ManagementCompany" class="form-label">經紀公司</label>
              <input type="text" class="form-control" id="ManagementCompany" name="ManagementCompany">
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="phone_number" class="form-label">電話號碼</label>
              <input type="text" class="form-control" id="phone_number" name="phone_number" pattern="[0]{1}[9]{1}[0-9]{8}" maxlength="10">
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="debutDate" class="form-label">出道日期</label>
              <input type="date" class="form-control" id="debutDate" name="debutDate">
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
				<label for="art_picture" class="form-label">藝人頭貼</label>
				<input type="text" class="form-control" id="art_picture" name="art_picture">
				<div class="form-text"></div>
			</div>

            <button type="submit" class="btn btn-primary">新增</button>
          </form>
        </div>
      </div>
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

        <button type="button" class="btn btn-primary" onclick="location.href='artist-list.php'">到列表頁</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續新增</button>
      </div>
    </div>
  </div>
</div>
  <!-- Modal End-->

<?php include __DIR__ . "/part/scripts.php"; ?>
<script>  

  // const keepAdd = document.getElementById('keepAdd');
	const artist_nameField = document.form1.art_name;  

  // keepAdd.addEventListener('click', function() {
  //   formControls.forEach(function(control) {
  //     control.value = '';
  //   });
  // });
    
  const emailField = document.form1.email;

  function validateEmail(email) {
    const re =
      /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }


  function sendData(e) {
    // 回復欄位的外觀
    for (let el of fields) {
      el.style.border = '1px solid #CCC';
      el.nextElementSibling.innerHTML = '';
    }

    e.preventDefault(); 
    
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
    if (isPass) {
      const fd = new FormData(document.form1); // 沒有外觀的表單物件
      fetch('artist-add-api.php', {
          method: 'POST',
          body: fd, // Content-Type: multipart/form-data
        }).then(r => r.json())
				.then(data => {
					console.log(data);
					if (data.success) { 
						myModal1.show();
					} else { 


          }
				})
				.catch(ex => console.log(ex))
		   }
	};

	const myModal1 = new bootstrap.Modal('#staticBackdrop')

</script>
<?php include __DIR__ . "/part/html-footer.php"; ?>