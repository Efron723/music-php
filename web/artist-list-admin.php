<?php
if (!isset($_SESSION)) {
  session_start();
}
require __DIR__ . '/../config/pdo-connect.php';


$title = '藝人列表';
$pageName = 'artist-list';


$per_page = 4; #每頁有幾筆

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

if ($page < 1) {
  header('Location: ?page=1');
  exit;
}

#總筆數
$t_sql = "SELECT COUNT(id) FROM `artist_register`";

$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

# 總頁數
$totalPages = ceil($totalRows / $per_page);
if ($page > $totalPages) {
  header("Location: ?page={$totalPages}");
  exit; # 結束這支程式
}


$sql = sprintf(
  "SELECT * FROM `artist_register` order by id asc LIMIT %s,%s",
  ($page - 1) * $per_page,
  $per_page
);

$rows = $pdo->query($sql)->fetchAll();?>

<?php include __DIR__ . '/part/html-header.php' ?>
<?php include __DIR__ . '/part/navbar-head.php' ?>

<div class="container-fluid">
  <div class="row m-0">
    <div class="col-2 p-0">
      <?php include __DIR__ . "/part/left-bar.php"; ?>
    </div>

    <div class="col-10">    
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

                        <?php for ($i = $page - 5; $i <= $page + 5; $i++):
                            if ($i >= 1 and $i <= $totalPages): ?>
                                <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endif; endfor; ?>
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


 <!-- 表單 -->
  <!-- <div class="row"> -->
    <!-- <div class="col"> -->
      <form name="form1" onsubmit="sendMultiDel(event)">
      <table class="table table-bordered table-striped" data-toggle="table" >
        <thead>
          <tr>
            <th><button type="submit" class="btn btn-danger">刪除所選</button></th>
            <th><i class="fa-solid fa-trash"></i></th>
            <th>編號</th>
            <th>藝名</th>
            <th>Email</th>
            <th>經紀公司</th>
            <th>電話號碼</th>
            <th>出道日期</th>
            <th scope="col" class="text-center">藝人頭貼</th>
            <th><i class="fa-solid fa-file-pen"></i></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r) : ?>
            <tr>
              <td><input type="checkbox" name="ids[]" value="<?= $r['id'] ?>"></td>
              <td>
                <a href="javascript: delete_one(<?= $r['id'] ?>)">
                  <i class="fa-solid fa-trash"></i>
                </a>
              </td>
              <td><?= $r['id'] ?></td>
              <td><?= $r['art_name'] ?></td>
              <td><?= $r['email'] ?></td>
              <td><?= $r['ManagementCompany'] ?></td>
              <td><?= $r['phone_number'] ?></td>
              <td><?= $r['debutDate'] ?></td>
              <td><?= $r['art_picture'] ?></td>
              <td>
                <a href="artist-edit.php?id=<?= $r['id'] ?>">
                  <i class="fa-solid fa-file-pen"></i>
                </a>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
      </form>

</div>
<?php include __DIR__ . '/part/scripts.php' ?>
<script>
  function delete_one(id){
    if(confirm(`是否要刪除編號為 ${id} 的資料?`)){
      location.href = `del.php?sid=${id}`;
    }
  }

  const checkAll = document.getElementById("checkAll");
  const checkboxes = document.getElementsByClassName("checkboxes");

  checkAll.addEventListener('change', function() {
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

  dltAllSelect.addEventListener('click', function() {
    let selectedIds = []; // 儲存被勾選項目的 ID

    for (let i = 0; i < checkboxes2.length; i++) {
      if (checkboxes2[i].checked) {
        const actid = checkboxes2[i].value; // 獲取被勾選項目的 ID
        selectedIds.push(actid); // 將 ID 加入到 selectedIds 陣列中
      }
    }

    if (selectedIds.length > 0) {
      if (confirm(`確定要刪除這 ${selectedIds.length} 筆資料嗎?`)) {
     
        location.href = `artist-delete.php?actid=${selectedIds.join(',')}`;
      }
    } else {
      alert('請先選擇要刪除的資料');
    }
  });
</script>
<?php include __DIR__ . "/part/html-footer.php" ?>