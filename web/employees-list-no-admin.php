<?php
if (!isset($_SESSION)) {
  session_start();
}


$title = '員工列表(未登入)';
$pageName = 'employees-list';

require __DIR__ . './../config/pdo-connect.php';

$per_page = 20; #每頁有幾筆

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

if ($page < 1) {
  header('Location: ?page=1');
  exit;
}

#總筆數
$t_sql = "SELECT COUNT(id) FROM employees";

$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

# 總頁數
$totalPages = ceil($totalRows / $per_page);
if ($page > $totalPages) {
  header("Location: ?page={$totalPages}");
  exit; # 結束這支程式
}


// SELECT * FROM `address_book` ORDER BY id DESC LIMIT 0, 20
// SELECT * FROM `address_book` ORDER BY id DESC LIMIT 20, 20
// SELECT * FROM `address_book` ORDER BY id DESC LIMIT 40, 20
// SELECT * FROM `address_book` ORDER BY id DESC LIMIT 60, 20


$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) ? $_GET['order'] : 'asc';
$order = $order === 'desc' ? 'DESC' : 'ASC';

$sql = sprintf(
  "SELECT * FROM `employees` ORDER BY $sort $order LIMIT %s,%s",
  ($page - 1) * $per_page,
  $per_page
);



// $sql = sprintf(
//   "SELECT * FROM employees order by id DESC LIMIT %s,%s",
//   ($page - 1) * $per_page,
//   $per_page
// );

$rows = $pdo->query($sql)->fetchAll();

// echo json_encode([
//     'totalRows' => $totalRows,
//     'totalPages' => $totalPages,
//     'rows' => $rows,
// ]);


include __DIR__ . "/part/html-header.php";
include __DIR__ . "/part/navbar-head.php";
// include __DIR__ . "/part/left-bar.php";
?>



<div class="container-fluid">
  <div class="row">
    <!--  -->
    <div class="col-2 p-0">
      <?php include __DIR__ . "/part/left-bar.php"; ?>
    </div>

    <div class="col-10" style="overflow-x: auto;">
      <nav aria-label="Page navigation example">
        <ul class="pagination">
          <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=1">
              <i class="fa-solid fa-angles-left"></i>
            </a>
          </li>
          <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= max(1, $page - 1) ?>">
              <i class="fa-solid fa-angle-left"></i>
            </a>
          </li>

          <?php for ($i = $page - 5; $i <= $page + 5; $i++) :
            if ($i >= 1 and $i <= $totalPages) : ?>
              <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
              </li>
          <?php endif;
          endfor; ?>
          <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= min($totalPages, $page + 1) ?>">
              <i class="fa-solid fa-angle-right"></i>
            </a>
          </li>

          <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $totalPages ?>">
              <i class="fa-solid fa-angles-right"></i>
            </a>
          </li>
        </ul>
      </nav>

      <!--  -->
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th scope="col" class="text-center">#
              <a href="?sort=id&order=desc&page=<?= $page ?>"><i class="fa-solid fa-sort-down"></i></a>
              <a href="?sort=id&order=asc&page=<?= $page ?>"><i class="fa-solid fa-sort-up"></i></a>
            </th>
            <th scope="col" class="text-center">First name
              <a href="?sort=first_name&order=desc&page=<?= $page ?>"><i class="fa-solid fa-sort-down"></i></a>
              <a href="?sort=first_name&order=asc&page=<?= $page ?>"><i class="fa-solid fa-sort-up"></i></a>
            </th>
            <th scope="col" class="text-center">Last name
              <a href="?sort=last_name&order=desc&page=<?= $page ?>"><i class="fa-solid fa-sort-down"></i></a>
              <a href="?sort=last_name&order=asc&page=<?= $page ?>"><i class="fa-solid fa-sort-up"></i></a>
            </th>
            <th scope="col" class="text-center">Email</th>
            <th scope="col" class="text-center">Gender
            </th>
            <th scope="col" class="text-center">Phone number</th>

          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r) : ?>
            <tr>
              <td class="text-center"><?= $r['id'] ?></td>
              <td class="text-center"><?= $r['first_name'] ?></td>
              <td class="text-center"><?= $r['last_name'] ?></td>
              <td class="text-center"><?= $r['email'] ?></td>
              <td class="text-center"><?= $r['gender'] ?></td>
              <td class="text-center"><?= $r['phone_number'] ?></td>

            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . "/part/scripts.php" ?>
<script>
  const deleteOne = (id) => {
    if (confirm(`確定要刪除${id}的資料嗎?`)) {
      location.href = `delete.php?id=${id}`;
    }
  }
</script>

<?php include __DIR__ . "/part/html-footer.php" ?>