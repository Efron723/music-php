<?php
if (!isset($_SESSION)) {
  session_start();
}


$title = '通訊錄列表';
$pageName = 'members-list';

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

// SELECT * FROM `address_book` ORDER BY id DESC LIMIT 0, 20
// SELECT * FROM `address_book` ORDER BY id DESC LIMIT 20, 20
// SELECT * FROM `address_book` ORDER BY id DESC LIMIT 40, 20
// SELECT * FROM `address_book` ORDER BY id DESC LIMIT 60, 20



$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) ? $_GET['order'] : 'desc';
$order = $order === 'asc' ? 'asc' : 'desc';

$sql = sprintf(
  "SELECT * FROM `members`  ORDER BY $sort $order LIMIT %s,%s",
  ($page - 1) * $per_page,
  $per_page
);

// $stmt = $pdo->prepare($sql);
// $stmt->execute($params);
$rows = $pdo->query($sql)->fetchAll();
// $rows = $stmt->fetchAll();

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
      <!-- 頁面選單 Start -->
      <nav aria-label="Page navigation example">
        <ul class="pagination mt-3 d-flex justify-content-center">
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
      <!-- 頁面選單 End -->
      <!-- 按鈕列 Start -->
      <div class="row d-flex">
        <!-- <div class="col-2 mb-4"><button class="bg-warning rounded-2" id="checkall">一鍵全選</button></div> -->
        <div class="col-2 my-auto">
          <a href="members-add.php" class="bg-success rounded-2 border border-1 border-black p-1 text-decoration-none text-black">新增會員</a>
          <button class="bg-warning rounded-2" id="dltAllSelect">刪除所選</button>
        </div>

        <form class="col-12 col-lg-auto mb-lg-0 ms-auto">
          <input type="search" class="form-control form-control-dark my-3" placeholder="Search..." aria-label="Search">
        </form>
      </div>




      <!-- 按鈕列 End -->
      <!--  -->
      <div style="overflow-x: auto;">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th scope="col" class="text-center">
                <input class="form-check-input text-center" type="checkbox" value="" id="checkall">
              </th>
              <!-- <th scope="col" class="text-center">選擇</th> -->
              <!-- <th class="text-center"><i class="fa-solid fa-trash text-center"></i></th> -->
              <th scope="col" class="text-center text-nowrap">#
                <a href="?sort=id&order=desc&page=<?= $page ?>"><i class="fa-solid fa-sort-down"></i></a>
                <a href="?sort=id&order=asc&page=<?= $page ?>"><i class="fa-solid fa-sort-up"></i></a>
              </th>
              <th scope="col" class="text-center text-nowrap">First_name
                <a href="?sort=first_name&order=desc&page=<?= $page ?>"><i class="fa-solid fa-sort-down"></i></a>
                <a href="?sort=first_name&order=asc&page=<?= $page ?>"><i class="fa-solid fa-sort-up"></i></a>
              </th>
              <th scope="col" class="text-center text-nowrap">Last_name
                <a href="?sort=last_name&order=desc&page=<?= $page ?>"><i class="fa-solid fa-sort-down"></i></a>
                <a href="?sort=last_name&order=asc&page=<?= $page ?>"><i class="fa-solid fa-sort-up"></i></a>
              </th>
              <th scope="col" class="text-center text-nowrap">Email
              </th>
              <th scope="col" class="text-center text-nowrap">Passwords</th>
              <th scope="col" class="text-center text-nowrap">Gender
                <a href="?sort=gender&order=desc&page=<?= $page ?>"><i class="fa-solid fa-sort-down"></i></a>
                <a href="?sort=gender&order=asc&page=<?= $page ?>"><i class="fa-solid fa-sort-up"></i></a>
              </th>
              <th scope="col" class="text-center text-nowrap">Phone_Number</th>
              <th scope="col" class="text-center text-nowrap">Birthday
                <a href="?sort=birthday&order=desc&page=<?= $page ?>"><i class="fa-solid fa-sort-down"></i></a>
                <a href="?sort=birthday&order=asc&page=<?= $page ?>"><i class="fa-solid fa-sort-up"></i></a>
              </th>
              <th scope="col" class="text-center text-nowrap">Address</th>
              <th scope="col" class="text-center text-nowrap">Photo</th>


              <th scope="col" class="text-center text-nowrap">Created_at
                <a href="?sort=created_at&order=desc&page=<?= $page ?>"><i class="fa-solid fa-sort-down"></i></a>
                <a href="?sort=created_at&order=asc&page=<?= $page ?>"><i class="fa-solid fa-sort-up"></i></a>
              </th>
              <th class="text-center"><i class="fa-solid fa-pen-to-square"></i></th>

            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r) : ?>
              <tr>
                <td scope="col">
                  <div>
                    <input class="checkboxes form-check-input mx-auto text-center d-flex " type="checkbox" value="<?= $r['id'] ?>" id="flexCheckDefault<?= $r['id'] ?>">
                  </div>
                </td>


                <!-- <td class="text-center"><a href="javascript: deleteOne(<?= $r['id'] ?>)"><i class="fa-solid fa-trash text-danger"></i></a></td> -->
                <td class="text-center"><?= $r['id'] ?></td>
                <td class="text-center text-nowrap"><?= $r['first_name'] ?></td>
                <td class="text-center text-nowrap"><?= $r['last_name'] ?></td>
                <td class="text-center text-nowrap"><?= $r['email'] ?></td>
                <td class="text-center text-nowrap"><?= $r['passwords'] ?></td>
                <td class="text-center text-nowrap"><?= $r['gender'] ?></td>
                <td class="text-center text-nowrap"><?= $r['phone_number'] ?></td>
                <td class="text-center text-nowrap"><?= $r['birthday'] ?></td>
                <td class="text-center text-nowrap"><?= (!empty($r['address'])) ? htmlentities($r['address']) : '未填' ?></td>
                <td class="text-center text-nowrap"><img src="../img/members-img/<?= $r['photo'] ?>" alt="" style="width: 100px;"></td>
                <td class="text-center text-nowrap"><?= $r['created_at'] ?></td>
                <td class="text-center"><a href="members-edit.php?id=<?= $r['id'] ?>"><i class="fa-solid fa-pen-to-square text-warning"></i></a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

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


  // 刪除所選Script
  const dltAllSelect = document.getElementById("dltAllSelect");
  const checkboxes2 = document.querySelectorAll(".checkboxes");

  dltAllSelect.addEventListener('click', function() {
    let selectedIds = []; // 儲存被勾選項目的 ID

    for (let i = 0; i < checkboxes2.length; i++) {
      if (checkboxes2[i].checked) {
        const id = checkboxes2[i].value; // 獲取被勾選項目的 ID
        selectedIds.push(id); // 將 ID 加入到 selectedIds 陣列中
      }
    }

    if (selectedIds.length > 0) {
      if (confirm(`確定要刪除這 ${selectedIds.length} 筆資料嗎?`)) {
        // 執行刪除操作，這裡可以使用 AJAX 或者其他方式向後端發送刪除請求
        // 這裡假設你已經有了一個可以處理刪除的後端接口
        location.href = `members-delete-more.php?ids=${selectedIds.join(',')}`;
      }
    } else {
      alert('請先選擇要刪除的資料');
    }
  });
</script>

<?php include __DIR__ . "/part/html-footer.php" ?>