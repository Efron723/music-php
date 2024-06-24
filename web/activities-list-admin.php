<?php
require __DIR__ . '/../config/pdo-connect.php';
$title = '活動列表';
$pageName = 'activities-list';

$perPage = 5;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
	header('Location: ?page=1');
	exit;
}

$t_sql = "SELECT COUNT(actid) FROM activities";

#篩選
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'actid';
$order = isset($_GET['order']) ? $_GET['order'] : 'asc';
$order = $order === 'desc' ? 'DESC' : 'ASC';

$validSortColumns = ['actid', 'activity_class', 'a_date', 'art_name'];
if (!in_array($sort, $validSortColumns)) {
	$sort = 'actid';
}

$searchConditions = [];
$params = [];

if (!empty($_GET['actid'])) {
	$searchConditions[] = 'actid = :actid';
	$params[':actid'] = $_GET['actid'];
}

if (!empty($_GET['class'])) {
	$searchConditions[] = 'class LIKE :class';
	$params[':class'] = '%' . $_GET['class'] . '%';
}

if (!empty($_GET['activity_name'])) {
	$searchConditions[] = 'activity_name LIKE :activity_name';
	$params[':activity_name'] = '%' . $_GET['activity_name'] . '%';
}

if (!empty($_GET['a_date'])) {
	$searchConditions[] = 'a_date LIKE :a_date';
	$params[':a_date'] = '%' . $_GET['a_date'] . '%';
}

if (!empty($_GET['descriptions'])) {
	$searchConditions[] = 'descriptions LIKE :descriptions';
	$params[':descriptions'] = '%' . $_GET['descriptions'] . '%';
}

if (!empty($_GET['art_name'])) {
	$searchConditions[] = 'art_name LIKE :art_name';
	$params[':art_name'] = '%' . $_GET['art_name'] . '%';
}

$searchSql = '';
if (!empty($searchConditions)) {
	$searchSql = 'WHERE ' . implode(' AND ', $searchConditions);
}


// 計算總筆數
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

// 計算總頁數
$totalPages = $totalRows ? ceil($totalRows / $perPage) : 0;
$rows = [];

if ($totalRows) {
	if ($page > $totalPages) {
		header("Location: ?page={$totalPages}");
		exit; # 結束這支程式
	}

	$offset = ($page - 1) * $perPage;
	$sql = "SELECT * 
          FROM activities
          JOIN aclass ON activities.activity_class = aclass.id 
          JOIN artist ON artist_id = artist.id
            $searchSql
            ORDER BY $sort $order
            LIMIT $offset, $perPage";
	$stmt = $pdo->prepare($sql);
	$stmt->execute($params);
	$rows = $stmt->fetchAll();
}

// 分頁邏輯保持不變
$currentPage = max($page, 1);
$range = 5;
$startPage = $currentPage - $range;
$endPage = $currentPage + $range;

if ($startPage < 1) {
	$endPage += 1 - $startPage;
	$startPage = 1;
}

if ($endPage > $totalPages) {
	$startPage -= $endPage - $totalPages;
	$endPage = $totalPages;

	if ($startPage < 1) {
		$startPage = 1;
	}
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
			<!-- 分頁功能Start -->
			<div class="row">
				<div class="col d-flex justify-content-center my-4">
					<nav aria-label="Page navigation example">
						<ul class="pagination">
							<!-- First Page Link -->
							<li class="page-item<?= $currentPage == 1 ? 'disabled' : '' ?>">
								<a class="page-link" href="?page=1&order=<?= $order ?>&sort=<?= $sort ?>"><i
										class="fa-solid fa-angles-left"></i></a>
							</li>
							<!-- Previous Page Link -->
							<li class="page-item">
								<a class="page-link" href="?page=<?= $currentPage - 1 ?>&order=<?= $order ?>&sort=<?= $sort ?>"><i
										class="fa-solid fa-angle-left"></i></a>
							</li>
							<!-- Ellipsis before the start page -->
							<?php if ($startPage > 1): ?>
								<li class="page-item disabled"><span class="page-link">...</span></li>
							<?php endif; ?>
							<!-- Page Number Links -->
							<?php for ($i = $startPage; $i <= $endPage; $i++): ?>
								<li class="page-item<?= $i == $currentPage ? ' active' : '' ?>">
									<a class="page-link" href="?page=<?= $i ?>&order=<?= $order ?>&sort=<?= $sort ?>"><?= $i ?></a>
								</li>
							<?php endfor; ?>
							<!-- Ellipsis after the end page -->
							<?php if ($endPage < $totalPages): ?>
								<li class="page-item disabled"><span class="page-link">...</span></li>
							<?php endif; ?>
							<!-- Next Page Link -->
							<li class="page-item">
								<a class="page-link" href="?page=<?= $currentPage + 1 ?>&order=<?= $order ?>&sort=<?= $sort ?>"><i
										class="fa-solid fa-angle-right"></i></a>
							</li>
							<!-- Last Page Link -->
							<li class="page-item<?= $currentPage == $totalPages ? 'disabled' : '' ?>">
								<a class="page-link" href="?page=<?= $totalPages ?>&order=<?= $order ?>&sort=<?= $sort ?>"><i
										class="fa-solid fa-angles-right"></i></a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
			<!-- 分頁功能End -->

			<!-- 搜尋功能Start -->
			<div class="row m-1">
				<div class="col mb-3 d-flex justify-content-start">
					<form method="get" action="" id="searching" onsubmit="sendData(event)">
						<div class="form text-dark">
							<div class="row">
								<div class="col-md-3">
									<label for="b2c_id">編號</label>
									<input type="text" class="form-control" id="actid" name="actid"
										value="<?= htmlentities($_GET['actid'] ?? '') ?>">
								</div>
								<div class="col-md-3">
									<label for="class">類別</label>
									<input type="text" class="form-control" id="class" name="class"
										value="<?= htmlentities($_GET['class'] ?? '') ?>">
								</div>
								<div class="col-md-3">
									<label for="activity_name">活動名稱</label>
									<input type="text" class="form-control" id="activity_name" name="activity_name"
										value="<?= htmlentities($_GET['activity_name'] ?? '') ?>">
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<label for="a_date">日期</label>
									<input type="text" class="form-control" id="a_date" name="a_date"
										value="<?= htmlentities($_GET['a_date'] ?? '') ?>">
								</div>
								<div class="col-md-3">
									<label for="descriptions">活動內容</label>
									<input type="text" class="form-control" id="descriptions" name="descriptions"
										value="<?= htmlentities($_GET['descriptions'] ?? '') ?>">
								</div>
								<div class="col-md-3">
									<label for="art_name">表演者</label>
									<input type="text" class="form-control" id="art_name" name="art_name"
										value="<?= htmlentities($_GET['art_name'] ?? '') ?>">
								</div>
								<div class="col-md-3">
									<button type="submit" class="btn btn-primary" id="keepAdd"><i
											class="bi bi-search text-white"></i></button>
								</div>
							</div>
						</div>

					</form>
				</div>
			</div>
			<!-- 搜尋功能End -->
			<div class="row">
				<!-- 按鈕群組 -->
				<div class="col-6 mb-3">
					<button class="btn btn-danger mx-2" type="submit" id="dltAllSelect">刪除所選</button>
					<a href="activities-add.php"><button class="btn btn-primary mx-2" type="button">新增活動</button></a>
				</div>

				<!-- 排序按鈕群組 -->
				<div class="col-6 mb-3 d-flex justify-content-center">
					<div class="btn-group mx-2" role="group" aria-label="Basic outlined example">
						<button type="button" class="btn btn-primary text-nowrap" disabled>編號</button>
						<button type="button" class="btn btn-outline-primary"><a
								href="?sort=actid&order=asc&page=<?= $currentPage ?>"><i class="bi bi-caret-up-fill"></i></a></button>
						<button type="button" class="btn btn-outline-primary"><a
								href="?sort=actid&order=desc&page=<?= $currentPage ?>"><i
									class="bi bi-caret-down-fill"></i></a></button>
					</div>
					<div class="btn-group mx-2" role="group" aria-label="Basic outlined example">
						<button type="button" class="btn btn-primary text-nowrap" disabled>類別</button>
						<button type="button" class="btn btn-outline-primary"><a
								href="?sort=class&order=asc&page=<?= $currentPage ?>"><i class="bi bi-caret-up-fill"></i></a></button>
						<button type="button" class="btn btn-outline-primary"><a
								href="?sort=class&order=desc&page=<?= $currentPage ?>"><i
									class="bi bi-caret-down-fill"></i></a></button>
					</div>
					<div class="btn-group mx-2" role="group" aria-label="Basic outlined example">
						<button type="button" class="btn btn-primary text-nowrap" disabled>日期</button>
						<button type="button" class="btn btn-outline-primary"><a
								href="?sort=a_date&order=asc&page=<?= $currentPage ?>"><i class="bi bi-caret-up-fill"></i></a></button>
						<button type="button" class="btn btn-outline-primary"><a
								href="?sort=a_date&order=desc&page=<?= $currentPage ?>"><i
									class="bi bi-caret-down-fill"></i></a></button>
					</div>
					<div class="btn-group mx-2" role="group" aria-label="Basic outlined example">
						<button type="button" class="btn btn-primary text-nowrap" disabled>表演者</button>
						<button type="button" class="btn btn-outline-primary"><a
								href="?sort=art_name&order=asc&page=<?= $currentPage ?>"><i
									class="bi bi-caret-up-fill"></i></a></button>
						<button type="button" class="btn btn-outline-primary"><a
								href="?sort=art_name&order=desc&page=<?= $currentPage ?>"><i
									class="bi bi-caret-down-fill"></i></a></button>
					</div>
				</div>
			</div>
			<!-- 搜尋 -->
			<div class="col-6 mb-3">


			</div>
			<!-- 列表 -->
			<div class="col" style="overflow-x: auto;">
				<table class="table table-bordered table-striped">
					<thead>
						<tr class="text-nowrap">
							<th scope="col"><input class="form-check-input" type="checkbox" value="" id="checkAll"> </th>
							<th scope="col">#</th>
							<th scope="col">類別</th>
							<th scope="col">活動名稱</th>
							<th scope="col">日期</th>
							<th scope="col">時間</th>
							<th scope="col">地點</th>
							<th scope="col">地址</th>
							<th scope="col">活動內容</th>
							<th scope="col">主辦單位</th>
							<th scope="col">表演者</th>
							<th scope="col">圖片</th>
							<th scope="col"><i class="fa-solid fa-trash"></i></th>
							<th scope="col"><i class="fa-solid fa-pen-to-square"></i></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($rows as $r): ?>
							<tr>
								<td>
									<input class="checkboxes form-check-input" type="checkbox" value="<?= $r['actid'] ?>"
										id="flexCheckDefault<?= $r['actid'] ?>">
								</td>
								<td><?= $r['actid'] ?></td>
								<td><?= $r['class'] ?></td>
								<td><?= $r['activity_name'] ?></td>
								<td><?= $r['a_date'] ?></td>
								<td><?= $r['a_time'] ?></td>
								<td><?= $r['location'] ?></td>
								<td><?= $r['address'] ?></td>
								<td class="d-inline-block" style="width: 300px;"><?= $r['descriptions'] ?></td>
								<td><?= $r['organizer'] ?></td>
								<td><?= $r['art_name'] ?></td>
								<td><img src="../img/activities-img/<?= $r['picture'] ?>" alt="activities_picture"
										class="image img-thumbnail"></td>
								<td><a href="javascript: deleteOne(<?= $r['actid'] ?>)">
										<button type="button" class="btn btn-danger"><i class="fa-solid fa-trash text-white"></i></a></td>
								</button>
								<td><a href="activities-edit.php?actid=<?= $r['actid'] ?>">
										<button type="button" class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></button></a>
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
					herf="activities-delete.php?actid=<?php $r['actid'] ?>">確認刪除</button>
			</div>
		</div>
	</div>
</div>
</div>
<?php include __DIR__ . '/part/scripts.php' ?>
<script>
	const keepAdd = document.getElementById('keepAdd');
	const formControls = document.querySelectorAll('.form-control');

	// const sendData = e => {
	// 	e.preventDefault(); // 不要讓 form_activities 以傳統的方式送出

	// 	formControls.forEach(function (control) {
	// 		control.value = '';
	// 	});

	// };
	// // 表單提交事件處理程序
	// document.getElementById('searching').addEventListener('submit', function(event) {
	// 	// 阻止表單的默認提交行為
	// 	event.preventDefault();

	// 	// 在這裡處理表單的提交，可以是向服務器發送請求或其他操作

	// 	// 清空表單控件的值
	// 	formControls.forEach(function(control) {
	// 		control.value = '';
	// 	});
	// });


	const deleteOne = (actid) => {
		if (confirm(`確定要刪除${actid}的資料嗎?`)) {
			location.href = `activities-delete.php?actid=${actid}`;
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
				location.href = `activities-delete-sel.php?actid=${selectedIds.join(',')}`;
			}
		} else {
			alert('請先選擇要刪除的資料');
		}
	});
</script>
<?php include __DIR__ . '/part/html-footer.php' ?>