<?php
require __DIR__ . "/admin-required.php";

if (!isset($_SESSION)) {
  session_start();
}


$title = '會員編輯列表';
$pageName = 'members-edit';

require __DIR__ . '/../config/pdo-connect.php';

$per_page = 20; #每頁有幾筆

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

if ($page < 1) {
  header('Location: ?page=1');
  exit;
}

#總筆數
$t_sql = "SELECT COUNT(id) FROM `members`";

$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

# 總頁數
$totalPages = ceil($totalRows / $per_page);
if ($page > $totalPages) {
  header("Location: ?page={$totalPages}");
  exit; # 結束這支程式
}

$sql = sprintf(
  "SELECT * FROM `members` order by id desc LIMIT %s,%s",
  ($page - 1) * $per_page,
  $per_page
);

$rows = $pdo->query($sql)->fetchAll();

include __DIR__ . "/part/html-header.php";
include __DIR__ . "/part/navbar-head.php";
?>



<div class="container-fluid">
  <div class="row">
    <!--  -->
    <div class="col-2 p-0">
      <?php include __DIR__ . "/part/left-bar.php"; ?>
    </div>

    <div class="col-10">
      <nav aria-label="Page navigation example">
        <ul class="pagination">
          <!-- arrow left start -->
          <li class="page-item ">
            <a class="page-link" href="#">
              <i class="fa-solid fa-angles-left"></i>
            </a>
          </li>
          <li class="page-item ">
            <a class="page-link" href="#">
              <i class="fa-solid fa-angle-left"></i>
            </a>
          </li>
          <!-- arrow left end -->
          <?php for ($i = $page - 5; $i <= $page + 5; $i++) :
            if ($i >= 1 and $i <= $totalPages) : ?>
              <li class="page-item <?php $page == $i ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
              </li>
          <?php endif;
          endfor; ?>
          <!-- arrow right start -->
          <li class="page-item">
            <a class="page-link" href="#">
              <i class="fa-solid fa-angle-right"></i>
            </a>
          </li>
          <li class="page-item">
            <a class="page-link" href="#">
              <i class="fa-solid fa-angles-right"></i>
            </a>
          </li>
          <!-- arrow right end -->
        </ul>
      </nav>

      <!--  -->
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th scope="col" class="text-center">
              <input class="form-check-input" type="checkbox" value="" id="checkall"> 全選
            </th>
            <th scope="col" class="text-center">#</th>
            <th scope="col" class="text-center">First_Name</th>
            <th scope="col" class="text-center">Last_Name</th>
            <th scope="col" class="text-center">Email</th>
            <th scope="col" class="text-center">Passwords</th>
            <th scope="col" class="text-center">Gender</th>
            <th scope="col" class="text-center">Phone_Number</th>
            <th scope="col" class="text-center">Birthday</th>
            <th scope="col" class="text-center">Address</th>
            <th scope="col" class="text-center">Created_at</th>
            <th class="text-center"><i class="fa-solid fa-pen-to-square"></i></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r) : ?>
            <tr>
              <td scope="col" style="text-align: center;">
                <input class="checkboxes form-check-input" type="checkbox" value="" id="flexCheckDefault">
              </td>
              <td class="text-center"><?= $r['id'] ?></td>
              <td class="text-center"><?= $r['first_name'] ?></td>
              <td class="text-center"><?= $r['last_name'] ?></td>
              <td class="text-center"><?= $r['email'] ?></td>
              <td class="text-center"><?= $r['passwords'] ?></td>
              <td class="text-center"><?= $r['gender'] ?></td>
              <td class="text-center"><?= $r['phone_number'] ?></td>
              <td class="text-center"><?= $r['birthday'] ?></td>
              <td><?= (!empty($r['address'])) ? htmlentities($r['address']) : '未填' ?></td>
              <td class="text-center"><?= $r['created_at'] ?></td>
              <td class="text-center"><a href="members-edit.php?id=<?= $r['id'] ?>"><i class="fa-solid fa-pen-to-square"></i></a></td>
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
      location.href = `members-delete.php?id=${id}`;
    }
  }


  const checkall = document.getElementById("checkall");
  const checkboxes = document.getElementsByClassName("checkboxes");

  checkall.addEventListener('change', function() {
    if (checkall.checked === true) {
      for (let i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = true;
      }
    } else {
      for (let i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = false;
      }
    }
  });
</script>

<?php include __DIR__ . "/part/html-footer.php" ?>