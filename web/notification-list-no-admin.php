<?php
require __DIR__ . '/../config/pdo-connect.php';
$title = '活動列表(未登入)';
$pageName = 'notification-list';

$perPage = 10;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header('Location: ?page=1');
  exit;
}

$t_sql = "SELECT COUNT(actid) FROM notification";

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
              <li class="page-item">
                <a class="page-link" href="?page=<?= $page + 1 ?>">
                  <i class="fa-solid fa-angle-right"></i>
                </a>
              </li>
              <li class="page-item">
                <a class="page-link" href="?page=<?= $totalPages ?>">
                  <i class="fa-solid fa-angles-right"></i>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">標題</th>
            <th scope="col">內容</th>
            <th scope="col">發送時間</th>
            <th scope="col">類別</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= $r['notid'] ?></td>
              <td><?= $r['title'] ?></td>
              <td><?= $r['content'] ?></td>
              <td><?= $r['sent_time'] ?></td>
              <td><?= $r['id'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>


  <?php include __DIR__ . '/part/scripts.php' ?>
  <script>

  </script>
  <?php include __DIR__ . '/part/html-footer.php' ?>