<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

$title = "修改商品列表";
$pageName = 'products-edit';

$actid = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($actid < 1) {
  header('Location: products-list.php');
  exit;
}

// $sql = "SELECT * FROM products WHERE actid={$actid}";
$sql = "SELECT * FROM `products` WHERE id={$id}";
$row = $pdo->query($sql)->fetch();
if (empty($row)) {
  header('Location: products-list.php');
  exit;
}

// echo json_encode($row);


?>
<?php include __DIR__ . '/part/html-header.php' ?>
<?php include __DIR__ . '/part/navbar-head.php' ?>
<style>
  form .mb-3 .form-text {
    color: red;
    font-weight: 800;
  }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-2 p-0">
      <?php include __DIR__ . "/part/left-bar.php"; ?>
    </div>
    <div class="col-8  mt-3 mx-auto">
      <div class="card my-3">
        <div class="card-body">
          <h4 class="card-title fw-bold">編輯資料</h4>
          <form id="form2" name="form2" onsubmit="sendData(event)">

            <input type="hidden" name="id" value="<?= $row['id'] ?>">

            <div class="mb-3">
              <label for="id" class="form-label">編號</label>
              <input type="text" class="form-control" disabled value="<?= $row['id'] ?>">
            </div>

            <div class="mb-3">
              <label for="product_name" class="form-label">商品名稱</label>
              <input type="text" class="form-control" id="product_name" name="product_name" value="<?= $row['product_name'] ?>" required>
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="picture" class="form-label">商品圖片</label>
              <div class="mb-3">
                <img src="../img/products-img/<?= $row['picture'] ?>" class="img-thumbnail" alt="">
              </div>
              <input type="file" class="form-control" id="picture" name="picture" value="<?= $row['picture'] ?>">
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="purchase_quantity" class="form-label fw-bold">進貨數量</label>
              <input type="text" class="form-control" id="purchase_quantity" name="purchase_quantity" maxlength="6" value="<?= $row['purchase_quantity'] ?>" required>
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
            <label for="activitie_id" class="form-label fw-bold">活動名稱</label>
              <input type="text" class="form-control" id="activitie_id" name="activitie_id" value="<?= $row['activitie_id'] ?>" >
              <div class="form-text"></div>
            </div>
            
            <button type="submit" class="btn btn-primary my-5 px-5" data-bs-toggle="modal" data-bs-target="#staticBackdrop">修改</button>
            <a href="product-list.php" class="btn  btn-info mx-5 px-3">取消</a>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- ModalA -->
<div class="modal fade" id="staticBackdropA" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="staticBackdropLabelA" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabelA">修改成功</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">
          資料修改成功
        </div>
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-primary" onclick="location.href='products-list.php'">到列表頁</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續編輯</button>
      </div>
    </div>
  </div>
</div>

<!-- ModalB -->
<div class="modal fade" id="staticBackdropB" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="staticBackdropLabelB" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabelB">資料沒有修改</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert">
          資料沒有修改
        </div>
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-primary" onclick="location.href='products-list.php'">到列表頁</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續編輯</button>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/part/scripts.php' ?>
<script>
  const nameField = document.form2.product_name;
  const quantityField = document.form2.purchase_quantity;

  const sendData = e => {
    e.preventDefault(); // 不要讓 form_products 以傳統的方式送出

    nameField.style.border = '1px solid #CCC';
    nameField.nextElementSibling.innerHTML = '';
    
    quantityField.style.border = '1px solid #CCC';
    quantityField.nextElementSibling.innerHTML = '';
    // TODO: 欄位資料檢查
    let isPass = true; // 表單有沒有通過檢查

    if (nameField.value.length < 2) {
      isPass = false;
      nameField.style.border = '1px solid red';
      nameField.nextElementSibling.innerText = '請填寫正確的商品名稱';
    }

    // 有通過檢查, 才要送表單
    if (isPass) {
      const fd = new FormData(document.form2); // 沒有外觀的表單物件

      fetch('product-edit-api.php', {
        method: 'POST',
        body: fd, // Content-Type: multipart/form-data
      }).then(r => r.json())
        .then(data => {
          console.log(data);
          if (data.success) {
            myModalA.show();
          } else {
            myModalB.show();
          }
        })
        .catch(ex => console.log(ex))
    }
  };

  const myModalA = new bootstrap.Modal('#staticBackdropA');
  const myModalB = new bootstrap.Modal('#staticBackdropB');

<?php include __DIR__ . '/part/html-footer.php' ?>