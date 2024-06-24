<?php
require __DIR__ . '/../config/pdo-connect.php';
$title = '通知列表';
$pageName = 'notification-list';

$perPage = 10;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header('Location: ?page=1');
  exit;
}

$t_sql = "SELECT COUNT(notid) FROM notification";

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
    "SELECT * FROM notification JOIN nclass ON notification.noti_class = nclass.id ORDER BY notification.notid ASC LIMIT %s, %s",
    ($page - 1) * $perPage,
    $perPage
  );
  $rows = $pdo->query($sql)->fetchAll();
}

?>

<?php include __DIR__ . '/part/html-header.php' ?>
<?php include __DIR__ . '/part/navbar-head.php' ?>

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
      <!-- 按鈕群組 -->
      <div class="col-6 mb-3">
        <button class="btn btn-danger" type="submit" id="dltAllSelect">刪除所選</button>
      </div>
      <!-- 搜尋 -->
      <div class="col-6 mb-3">


      </div>
      <!-- 列表 -->
      <div class="col">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th scope="col"><input class="form-check-input" type="checkbox" value="" id="checkAll"> </th>
              <th scope="col">#</th>
              <th scope="col">標題</th>
              <th scope="col">內容</th>
              <th scope="col">發送時間</th>
              <th scope="col">類別</th>
              <th scope="col"><i class="fa-solid fa-trash"></i></th>
              <th scope="col"><i class="fa-solid fa-pen-to-square"></i></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td>
                  <input class="checkboxes form-check-input" type="checkbox" value="<?= $r['notid'] ?>"
                    id="flexCheckDefault<?= $r['notid'] ?>">
                </td>
                <td><?= $r['notid'] ?></td>
                <td><?= $r['title'] ?></td>
                <td><?= $r['content'] ?></td>
                <td><?= $r['sent_time'] ?></td>
                <td><?= $r['class'] ?></td>
                <td>
                  <a href="javascript: deleteOne(<?= $r['notid'] ?>)">
                    <i class="fa-solid fa-trash"></i></a>
                </td>
                <td><a href="notification-edit.php?notid=<?= $r['notid'] ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- 確認刪除 彈出視窗 -->
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
          herf="notification-delete.php?notid=<?php $r['notid'] ?>">確認刪除</button>
      </div>
    </div>
  </div>
</div>
</div>
<?php include __DIR__ . '/part/scripts.php' ?>
<script>
  const deleteOne = (notid) => {
    if (confirm(`確定要刪除${notid}的資料嗎?`)) {
      location.href = `notification-delete.php?notid=${notid}`;
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
        const notid = checkboxes2[i].value; // 獲取被勾選項目的 ID
        selectedIds.push(notid); // 將 ID 加入到 selectedIds 陣列中
      }
    }

    if (selectedIds.length > 0) {
      if (confirm(`確定要刪除這 ${selectedIds.length} 筆資料嗎?`)) {
        // 執行刪除操作，這裡可以使用 AJAX 或者其他方式向後端發送刪除請求
        // 這裡假設你已經有了一個可以處理刪除的後端接口
        location.href = `notification-delete-sel.php?notid=${selectedIds.join(',')}`;
      }
    } else {
      alert('請先選擇要刪除的資料');
    }
  });
</script>
<?php include __DIR__ . '/part/html-footer.php' ?>