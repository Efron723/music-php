<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

$title = '修改會員通訊錄資料';
$pageName = 'product-edit';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id < 1) {
  header('Location: product-list.php');
  exit;
}

$sql = "Select * FROM products WHERE id=$id";
$row = $pdo->query($sql)->fetch();

if (empty($row)) {
  header('Location: product-list.php');
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
          <h4 class="my-3">編輯產品</h4>
          <form name="form1" onsubmit="sendData(event)" class="needs-validation" novalidate enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">


            <div class="row g-3">

              <div class="mb-3">
                <label for="id" class="form-label">編號</label>
                <input type="text" class="form-control" id="id" name="id" value="<?= $row['id'] ?>" disabled>
              </div>




              <div class="col-sm-6">
                <label for="product_name" class="form-label">Product name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" value="<?= $row['product_name'] ?>" required>
                <div class="invalid-feedback">
                  Valid first name is required.
                </div>
              </div>

              <div class="col-6">
                <label for="picture" class="form-label">原始照片</label><br>
                <img src="../img/products-img/<?= $row['picture'] ?>" alt="原始照片" class="w-50">
              </div>
              <div class="col-6">

                <label for="picture" class="form-label">選擇新照片:</label>
                <input type="file" id="picture" name="picture" accept="image/*" onchange="previewNewPhoto(event)" class="form-control">
                <br>
                <img src="" alt="新照片預覽" id="newPhotoPreview" class="w-50" style="display: none;">
              </div>


              <div class="col-sm-6">
                <label for="price" class="form-label">Price</label>
                <input type="text" class="form-control" id="price" name="price" value="<?= $row['price'] ?>" required>
                <div class="invalid-feedback">
                  Valid last name is required.
                </div>
              </div>

              <div class="col-12">
                <label for="purchase_quantity" class="form-label">Purchase quantity</label>
                <input type="text" class="form-control" id="purchase_quantity" name="purchase_quantity" value="<?= $row['purchase_quantity'] ?>" required>
                <div class="invalid-feedback">
                  address required.
                </div>
              </div>

              <div class="col-12">
                <label for="activitie_id" class="form-label">Activitie id</label>
                <input type="text" class="form-control" id="activitie_id" name="activitie_id" value="<?= $row['activitie_id'] ?>" required>
                <div class="invalid-feedback">
                  address required.
                </div>
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

        <button type="button" class="btn btn-primary" onclick="location.href='product-list-admin.php'">到列表頁</button>
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

        <button type="button" class="btn btn-primary" onclick="location.href='product-list-admin.php'">到列表頁</button>
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







  


  const sendData = e => {
    e.preventDefault(); // 不要讓 form1 以傳統的方式送出


    // TODO: 欄位資料檢查

    let isPass = true; // 表單有沒有通過檢查


    // if (!validateEmail(emailField.value)) {
    //   isPass = false;
    //   emailField.style.border = '1px solid red';
    //   emailField.nextElementSibling.innerText = '請填寫正確的 Email';
    // }
    // 有通過檢查, 才要送表單
    if (isPass) {
      const fd = new FormData(document.form1); // 沒有外觀的表單物件
      fetch('product-edit-api.php', {
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