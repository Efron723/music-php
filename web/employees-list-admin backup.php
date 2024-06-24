<?php
if (!isset($_SESSION)) {
  session_start();
}


$title = '員工列表(已登入)';
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



$sql = sprintf(
  "SELECT * FROM `employees` order by id desc LIMIT %s,%s",
  ($page - 1) * $per_page,
  $per_page
);

$rows = $pdo->query($sql)->fetchAll();

// echo json_encode([
//     'totalRows' => $totalRows,
//     'totalPages' => $totalPages,
//     'rows' => $rows,
// ]);


include __DIR__ . "/part/html-header.php";
include __DIR__ . "/part/navbar-head.php";

?>

<style>
  /* NEW */

  /* 第一欄和第二欄固定在最左側 */
  td:first-child,
  th:first-child {
    position: sticky;
    left: 0;
    z-index: 2;
    background-color: lightpink;
  }

  td:nth-child(2),
  th:nth-child(2) {
    position: sticky;
    left: 0;
    z-index: 2;
    background-color: lightpink;
  }



  /* 最後一欄固定在最右側 */
  td:last-child,
  th:last-child {
    position: sticky;
    right: 0;
    z-index: 1;
    background-color: blue;
  }

  /* 表頭固定在最上方 */
  thead tr th {
    position: sticky;
    top: 0;
    background-color: lightblue;
  }

  /* 其他樣式設定 */
  td,
  th {
    border: 1px solid gray;
  }
</style>

<div class="container-fluid">
  <div class="row">
    <!--  -->
    <div class="col-2 p-0">
      <?php include __DIR__ . "/part/left-bar.php"; ?>
    </div>

    <div class="col-10">
      <!-- 頁面選單 Start -->
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
      <!-- 頁面選單 End -->
      <!-- 按鈕列 Start -->
      <div class="row d-flex justify-content-between">
        <!-- <div class="col-2 mb-4"><button class="bg-warning rounded-2" id="checkall">一鍵全選</button></div> -->
        <div class="col-2 my-auto"><button class="bg-warning rounded-2" id="dltAllSelect">刪除所選</button></div>
        <form class="col-12 col-lg-auto mb-lg-0">
          <input type="search" class="form-control form-control-dark my-3" placeholder="Search..." aria-label="Search">
        </form>
      </div>

      <!--  -->
      <div style="overflow-x: auto;">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th scope="col" class="text-center">
                <input class="form-check-input" type="checkbox" id="checkall">
              </th>
              <!-- <th><i class="fa-solid fa-trash text-center"></i></th> -->

              <th scope="col" class="text-center text-nowrap">#</th>

              <th scope="col" class="text-center text-nowrap">First name</th>
              <th scope="col" class="text-center text-nowrap">Last name</th>
              <th scope="col" class="text-center text-nowrap">Email</th>
              <th scope="col" class="text-center text-nowrap">Passwords</th>
              <th scope="col" class="text-center text-nowrap">Gender</th>
              <th scope="col" class="text-center text-nowrap">Phone number</th>
              <th scope="col" class="text-center">Created_at</th>
              <th><i class="fa-solid fa-pen-to-square"></i></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r) : ?>
              <tr>
                <td scope="col" style="text-align: center;">
                  <input class="checkboxes form-check-input" type="checkbox" value="<?= $r['id'] ?>" id="flexCheckDefault<?= $r['id'] ?>">
                </td>
                <!-- <td><a href="javascript: deleteOne(<?= $r['id'] ?>)"><i class="fa-solid fa-trash text-danger"></i></a></td> -->

                <td class="text-center"><?= $r['id'] ?></td>
                <td class="text-center"><?= $r['first_name'] ?></td>
                <td class="text-center"><?= $r['last_name'] ?></td>
                <td class="text-center"><?= $r['email'] ?></td>
                <td class="text-center"><?= $r['passwords'] ?></td>
                <td class="text-center"><?= $r['gender'] ?></td>
                <td class="text-center"><?= $r['phone_number'] ?></td>
                <td class="text-center text-nowrap"><?= $r['created_at'] ?></td>
                <td><a href="employees-edit.php?id=<?= $r['id'] ?>"><i class="fa-solid fa-pen-to-square text-warning"></i></a></td>

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
      location.href = `employees-delete.php?id=${id}`;
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
        location.href = `employees-delete-more.php?ids=${selectedIds.join(',')}`;
      }
    } else {
      alert('請先選擇要刪除的資料');
    }
  });
</script>

<?php include __DIR__ . "/part/html-footer.php" ?>