<?php
require __DIR__ . '/../config/pdo-connect.php';

$title = "會員列表";
$pageName = 'product-search';

$perPage = 20;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'b2c_id';
$order = isset($_GET['order']) ? $_GET['order'] : 'asc';
$order = $order === 'desc' ? 'DESC' : 'ASC';


$validSortColumns = ['b2c_id', 'b2c_name', 'b2c_email', 'b2c_mobile', 'b2c_birth'];
if (!in_array($sort, $validSortColumns)) {
    $sort = 'b2c_id';
}

// 搜尋設定
$searchConditions = [];
$params = [];

if (!empty($_GET['b2c_id'])) {
    $searchConditions[] = 'b2c_id = :b2c_id';
    $params[':b2c_id'] = $_GET['b2c_id'];
}

if (!empty($_GET['b2c_name'])) {
    $searchConditions[] = 'b2c_name LIKE :b2c_name';
    $params[':b2c_name'] = '%' . $_GET['b2c_name'] . '%';
}

if (!empty($_GET['b2c_email'])) {
    $searchConditions[] = 'b2c_email LIKE :b2c_email';
    $params[':b2c_email'] = '%' . $_GET['b2c_email'] . '%';
}

if (!empty($_GET['b2c_mobile'])) {
    $searchConditions[] = 'b2c_mobile LIKE :b2c_mobile';
    $params[':b2c_mobile'] = '%' . $_GET['b2c_mobile'] . '%';
}

$searchSql = '';
if (!empty($searchConditions)) {
    $searchSql = 'WHERE ' . implode(' AND ', $searchConditions);
}

// 計算總筆數
$t_sql = "SELECT COUNT(b2c_id) FROM b2c_members $searchSql";
$stmt = $pdo->prepare($t_sql);
$stmt->execute($params);
$totalRows = $stmt->fetch(PDO::FETCH_NUM)[0];

// 計算總頁數
$totalPages = $totalRows ? ceil($totalRows / $perPage) : 0;
$rows = [];

if ($totalRows) {
    if ($page > $totalPages) {
        header("Location: ?page={$totalPages}");
        exit;
    }

    $offset = ($page - 1) * $perPage;
    $sql = "SELECT b2c.*, county_name, city_name
            FROM b2c_members AS b2c
            JOIN county ON fk_county_id = county_id
            JOIN city ON b2c.fk_city_id = city_id
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

<?php include __DIR__ . '/parts/html-head.php'; ?>
<?php include __DIR__ . '/parts/html-bar.php'; ?>

<div class="container">
    <!-- 分頁功能Start -->
    <div class="row">
        <div class="col">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <!-- First Page Link -->
                    <li class="page-item" style="display: <?= $currentPage == 1 ? 'none' : '' ?>;">
                        <a class="page-link" href="?page=1&order=<?= $order ?>&sort=<?= $sort ?>">First</a>
                    </li>
                    <!-- Previous Page Link -->
                    <li class="page-item<?= $currentPage == 1 ? ' disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $currentPage - 1 ?>&order=<?= $order ?>&sort=<?= $sort ?>">Previous</a>
                    </li>
                    <!-- Ellipsis before the start page -->
                    <?php if ($startPage > 1) : ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                    <!-- Page Number Links -->
                    <?php for ($i = $startPage; $i <= $endPage; $i++) : ?>
                        <li class="page-item<?= $i == $currentPage ? ' active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&order=<?= $order ?>&sort=<?= $sort ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <!-- Ellipsis after the end page -->
                    <?php if ($endPage < $totalPages) : ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                    <!-- Next Page Link -->
                    <li class="page-item<?= $currentPage == $totalPages ? ' disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $currentPage + 1 ?>&order=<?= $order ?>&sort=<?= $sort ?>">Next</a>
                    </li>
                    <!-- Last Page Link -->
                    <li class="page-item" style="display: <?= $currentPage == $totalPages ? 'none' : '' ?>;">
                        <a class="page-link" href="?page=<?= $totalPages ?>&order=<?= $order ?>&sort=<?= $sort ?>">End</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <!-- 分頁功能End -->

    <!-- 搜尋功能Start -->
    <div class="row">
        <form method="get" action="">
            <div class="form text-dark">
                <div class=" col-md-3">
                    <label for="b2c_id">會員編號</label>
                    <input type="text" class="form-control" id="b2c_id" name="b2c_id" value="<?= htmlentities($_GET['b2c_id'] ?? '') ?>">
                </div>
                <div class=" col-md-3">
                    <label for="b2c_name">姓名</label>
                    <input type="text" class="form-control" id="b2c_name" name="b2c_name" value="<?= htmlentities($_GET['b2c_name'] ?? '') ?>">
                </div>
                <div class=" col-md-3">
                    <label for="b2c_email">Email</label>
                    <input type="text" class="form-control" id="b2c_email" name="b2c_email" value="<?= htmlentities($_GET['b2c_email'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label for="b2c_mobile">手機</label>
                    <input type="text" class="form-control" id="b2c_mobile" name="b2c_mobile" value="<?= htmlentities($_GET['b2c_mobile'] ?? '') ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" hidden>搜尋</button>
        </form>
    </div>
    <!-- 搜尋功能End -->

    <!-- 資列陳列Start -->
    <div class="row">
        <div class="col">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col"><i class="fa-solid fa-trash"></i></th>
                        <th scope="col">
                            會員編號
                            <a href="?sort=b2c_id&order=desc&page=<?= $currentPage ?>"><i class="fa-solid fa-sort-down"></i></a>
                            <a href="?sort=b2c_id&order=asc&page=<?= $currentPage ?>"><i class="fa-solid fa-sort-up"></i></a>
                        </th>
                        <th scope="col">
                            姓名
                        </th>
                        <th scope="col">
                            Email
                            <a href="?sort=b2c_email&order=desc&page=<?= $currentPage ?>"><i class="fa-solid fa-sort-down"></i></a>
                            <a href="?sort=b2c_email&order=asc&page=<?= $currentPage ?>"><i class="fa-solid fa-sort-up"></i></a>
                        </th>
                        <th scope="col">手機</th>
                        <th scope="col">
                            生日
                            <a href="?sort=b2c_birth&order=desc&page=<?= $currentPage ?>"><i class="fa-solid fa-sort-down"></i></a>
                            <a href="?sort=b2c_birth&order=asc&page=<?= $currentPage ?>"><i class="fa-solid fa-sort-up"></i></a>
                        </th>

                        <th scope="col">地址</th>
                        <th scope="col"><i class="fa-solid fa-pen-to-square"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $r) : ?>
                        <tr>
                            <td>
                                <a href="javascript: deleteOne(<?= $r['b2c_id'] ?>)">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                            <td><?= $r['b2c_id'] ?></td>
                            <td><?= $r['b2c_name'] ?></td>
                            <td><?= $r['b2c_email'] ?></td>
                            <td><?= $r['b2c_mobile'] ?></td>
                            <td><?= $r['b2c_birth'] ?></td>
                            <td><?= htmlentities($r['county_name'] . $r['city_name'] . $r['b2c_address']) ?></td>
                            <td>
                                <a href="b2c-edit.php?b2c_id=<?= $r['b2c_id'] ?>">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- 資列陳列End -->
</div>

<?php include __DIR__ . '/parts/scripts.php'; ?>
<script>
    const deleteOne = (b2c_id) => {
        if (confirm(`是否要刪除編號為 ${b2c_id} 的資料?`)) {
            location.href = `b2c-delete.php?b2c_id=${b2c_id}`;
        }
    }
</script>
<?php include __DIR__ . '/parts/html-foot.php'; ?>