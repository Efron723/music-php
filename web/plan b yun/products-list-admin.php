<?php
require __DIR__ . '../config/pdo-connect.php';
$title = '商品列表';
$pageName = 'products-list';

$perPage = 5;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header('Location: ?page=1');
  exit;
}

$t_sql = "SELECT COUNT(id) FROM products";

#篩選
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) ? $_GET['order'] : 'asc';
$order = $order === 'desc' ? 'DESC' : 'ASC';

$validSortColumns = ['id', 'product_name', 'picture', 'price'];
if (!in_array($sort, $validSortColumns)) {
  $sort = 'id';
}

$searchConditions = [];
$params = [];

if (!empty($_GET['id'])) {
  $searchConditions[] = 'id = :id';
  $params[':id'] = $_GET['id'];
}

// $searchSql = '';
// if (!empty($searchConditions)) {
//   $searchSql = 'WHERE ' . implode(' AND ', $searchConditions);
// }


# 總筆數
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

# 預設值
$totalPages = 0;
$rows = [];

if ($totalRows) {
  # 總頁數
  $totalPages = ceil($totalRows / $perPage);
  if ($page > $totalPages) {
    header("Location: ?page={$totalPages}");
    exit; # 結束這支程式
  }

  # 取得分頁資料
  $sql = sprintf(
    "SELECT * FROM products  ORDER BY products.id ASC LIMIT %s, %s",
    ($page - 1) * $perPage,
    $perPage
  );
  $rows = $pdo->query($sql)->fetchAll();
}

?>

<?php include __DIR__ . '/part/html-header.php' ?>
<?php include __DIR__ . '/part/navbar-head.php' ?>

<!-- 搜尋功能Start -->
<div class="row">
  <form method="get" action="">
    <div class="form text-dark">
      <div class=" col-md-3">
        <label for="id">編號</label>
        <input type="text" class="form-control" id="id" name="id"
          value="<?= htmlentities($_GET['id'] ?? '') ?>">
      </div>
      <div class=" col-md-3">
        <label for="product_name">商品名稱</label>
        <input type="text" class="form-control" id="product_name" name="product_name"
          value="<?= htmlentities($_GET['product_name'] ?? '') ?>">
      </div>
      <div class=" col-md-3">
        <label for="price">定價</label>
        <input type="text" class="form-control" id="price" name="price"
          value="<?= htmlentities($_GET['price'] ?? '') ?>">
      </div>
      <div class="col-md-3">
        <label for="activitie_id">活動名稱</label>
        <input type="text" class="form-control" id="activitie_id" name="activitie_id"
          value="<?= htmlentities($_GET['activitie_id'] ?? '') ?>">
      </div>
    </div>
    <button type="submit" class="btn btn-primary" hidden>搜尋</button>
  </form>
</div>
<!-- 搜尋功能End -->

<!-- 列表 -->
<div class="container-fluid">
  <div class="row">
    <div class="col-2 p-0">
      <?php include __DIR__ . "/part/left-bar.php"; ?>
    </div>
    <div class="col-10">
      <!-- 頁碼 -->
      <div class="row">
        <div class="col d-flex justify-content-center my-4">
          <nav aria-label="Page Navigation Example">
            <ul class="pagination">
              <li class="page-item">
                <a class="page-link" href="?page=1">
                  <i class="fa-solid fa-angles-left"></i>
                </a>
              </li>
              <li class="page-item ">
                <a class="page-link" href="?page=<?= $page - 1 ?>">
                  <i class="fa-solid fa-angle-left"></i>
                </a>
              </li>
              <?php for ($i = $page - 5; $i <= $page + 5; $i++):
                if ($i >= 1 and $i <= $totalPages): ?>
                  <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                  </li>
                <?php endif;
              endfor; ?>
              <li class="page-item ">
                <a class="page-link" href="?page=<?= $page + 1 ?>">
                  <i class="fa-solid fa-angle-right"></i>
                </a>
              </li>
              <li class="page-item ">
                <a class="page-link" href="?page=<?= $totalPages ?>">
                  <i class="fa-solid fa-angles-right"></i>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- 列表 -->
      <div class="col">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th scope="col" class="text-center">
                <i class="fa-solid fa-file-pen"></i>編輯
              </th>
              <th scope="col" class="text-center">編號</th>
              <th scope="col" class="text-center">商品名稱</th>
              <th scope="col" class="text-center">商品圖片</th>
              <th scope="col" class="text-center">定價</th>
              <th scope="col" class="text-center">進貨數量</th>
              <th scope="col" class="text-center">活動名稱</th>
              <th scope="col" class="text-center">
                  <i class="fa-solid fa-trash"></i>刪除
              </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
              <tr>
              <td class="text-center">
              <a href="product-edit.php?id=<?= $r['id'] ?>" class="btn btn-success">
                  <i class="fa-solid fa-file-pen"></i>編輯
                </a>
              </td>
              <td><?= $r['id'] ?></td>
              <td><?= $r['product_name'] ?></td>
              <td>
              <img src="../img/products-img/<?= $r['picture'] ?>" style="width:100px;" alt="">
                
              </td>
              <td><?= $r['price'] ?></td>
              <td><?= $r['purchase_quantity'] ?></td>
              <td><?= $r['activitie_id'] ?></td>
              <td>
                <a href="javascript: delete_one(<?= $r['id'] ?>)"  class="btn btn-danger">
                  <i class="fa-solid fa-trash"></i>刪除 
                </a>
              </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- 確認刪除 彈出視窗 start-->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">確定刪除此筆資料？</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">保留</button>
        <!-- 這裡有問題 -->
        <button type="button" class="btn btn-danger"
          herf="products-delete.php?actid=<?php $r['actid'] ?>">確認刪除</button>
      </div>
    </div>
  </div>
</div>
<!-- 確認刪除 彈出視窗 end-->

<?php include __DIR__ . '/part/scripts.php' ?>
<script>
  const deleteOne = (actid) => {
    if (confirm(`確定要刪除${actid}的資料嗎?`)) {
      location.href = `product-delete.php?actid=${actid}`;
    }
  }

  const checkAll = document.getElementById("checkAll");
  const checkboxes = document.getElementsByClassName("checkboxes");

  checkAll.addEventListener('change', function () {
    if (checkAll.checked === true) {
      for (let i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = true;
      }
    } else {
      for (let i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = false;
      }
    }
  });

  // 刪除所選Script
  const dltAllSelect = document.getElementById("dltAllSelect");
  const checkboxes2 = document.querySelectorAll(".checkboxes");

  dltAllSelect.addEventListener('click', function () {
    let selectedIds = []; // 儲存被勾選項目的 ID

    for (let i = 0; i < checkboxes2.length; i++) {
      if (checkboxes2[i].checked) {
        const actid = checkboxes2[i].value; // 獲取被勾選項目的 ID
        selectedIds.push(actid); // 將 ID 加入到 selectedIds 陣列中
      }
    }

    if (selectedIds.length > 0) {
      if (confirm(`確定要刪除這 ${selectedIds.length} 筆資料嗎?`)) {
        // 執行刪除操作，這裡可以使用 AJAX 或者其他方式向後端發送刪除請求
        // 這裡假設你已經有了一個可以處理刪除的後端接口
        location.href = `products-delete-sel.php?actid=${selectedIds.join(',')}`;
      }
    } else {
      alert('請先選擇要刪除的資料');
    }
  });
</script>
<?php include __DIR__ . '/part/html-footer.php' ?>