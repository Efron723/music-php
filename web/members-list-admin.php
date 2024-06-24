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

// 搜尋設定
$searchTerm = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : ''; // 添加通配符
$searchSql = '';

if (!empty($searchTerm)) {
  $searchSql = "WHERE `id` LIKE :searchTerm 
                  OR `name` LIKE :searchTerm
                  OR `email` LIKE :searchTerm 
                  OR `phone_number` LIKE :searchTerm
                  OR `address` LIKE :searchTerm";
}

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) ? $_GET['order'] : 'desc';
$order = $order === 'asc' ? 'asc' : 'desc';

$sql = sprintf(
  "SELECT * FROM `members` %s ORDER BY $sort $order LIMIT :start, :per_page",
  $searchSql
);

$stmt = $pdo->prepare($sql);

// Bind search term if applicable
if (!empty($searchTerm)) {
  $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
}

// Bind pagination parameters
$start = ($page - 1) * $per_page;
$stmt->bindParam(':start', $start, PDO::PARAM_INT);
$stmt->bindParam(':per_page', $per_page, PDO::PARAM_INT);

$stmt->execute();
$rows = $stmt->fetchAll();


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
      <div class="row d-flex mb-3">
        <!-- <div class="col-2 mb-4"><button class="bg-warning rounded-2" id="checkall">一鍵全選</button></div> -->
        <div class="col-2 my-auto">
          <a href="members-add.php" class="bg-success rounded-2 border border-1 border-black p-1 text-decoration-none text-black">新增會員</a>
          <button class="bg-warning rounded-2" id="dltAllSelect">刪除所選</button>
        </div>

        <form class="ms-auto d-flex" id="searchForm" style="width: 350px;">
          <input type="text" class="form-control px-auto" id="search" name="search" value="<?= htmlentities($_GET['search'] ?? '') ?>" placeholder="請輸入姓名/電話/信箱進行搜尋">
          <input type="hidden" id="isSearched" name="isSearched" value="<?= isset($_GET['search']) ? 'true' : 'false' ?>">
          <button type="submit" class="btn btn-primary ms-1"><i class="bi bi-search text-white"></i></button>

        </form>
      </div>

      <!-- 搜尋功能Start -->
      <!-- <div class="row">
        <form method="get" action="">
          <div class="form text-dark row">
            <div class="col-md-12">
              <label for="search">搜尋</label>
              <input type="text" class="form-control" id="search" name="search" value="<?= htmlentities($_GET['search'] ?? '') ?>">
            </div>
          </div>
          <button type="submit" class="btn btn-primary">搜尋</button>
        </form>
      </div> -->
      <!-- 搜尋功能End -->



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
              <th scope="col" class="text-center text-nowrap">Name
                <a href="?sort=name&order=desc&page=<?= $page ?>"><i class="fa-solid fa-sort-down"></i></a>
                <a href="?sort=name&order=asc&page=<?= $page ?>"><i class="fa-solid fa-sort-up"></i></a>
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
              <!-- <th scope="col" class="text-center text-nowrap">County</th>
              <th scope="col" class="text-center text-nowrap">District</th>
              <th scope="col" class="text-center text-nowrap">restAddress</th> -->
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
                <td class="text-center text-nowrap"><?= $r['name'] ?></td>
                <td class="text-center text-nowrap"><?= $r['email'] ?></td>
                <td class="text-center text-nowrap"><?= $r['passwords'] ?></td>
                <td class="text-center text-nowrap"><?= $r['gender'] ?></td>
                <td class="text-center text-nowrap"><?= $r['phone_number'] ?></td>
                <td class="text-center text-nowrap"><?= $r['birthday'] ?></td>
                <!-- <td class="text-center text-nowrap"><?= (!empty($r['county'])) ? htmlentities($r['county']) : '未填' ?></td>
                <td class="text-center text-nowrap"><?= (!empty($r['district'])) ? htmlentities($r['district']) : '未填' ?></td>
                <td class="text-center text-nowrap"><?= (!empty($r['restAddress'])) ? htmlentities($r['restAddress']) : '未填' ?></td> -->
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
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const isSearchedInput = document.getElementById('isSearched');
    const searchButton = document.getElementById('searchButton');

    // 初始化按鈕文字和狀態
    updateSearchButton();

    // 點擊按鈕時的事件處理程序
    searchButton.addEventListener('click', function(e) {
      e.preventDefault();
      toggleSearch();
      updateSearchButton();
    });

    // 切換搜尋狀態
    function toggleSearch() {
      const isSearched = isSearchedInput.value === 'true';
      isSearchedInput.value = isSearched ? 'false' : 'true';
    }

    // 更新按鈕文本
    function updateSearchButton() {
      const isSearched = isSearchedInput.value === 'true';
      if (isSearched) {
        searchButton.textContent = '清除搜尋';
      } else {
        searchButton.textContent = '搜尋';
      }
      searchInput.placeholder = isSearched ? '清除搜尋文字' : '請輸入 Name / Email / Phone_number進行搜尋';
      searchInput.value = isSearched ? '' : searchInput.value;
    }
  });








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