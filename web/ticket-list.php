<?php

require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

$title = '上架購票清單';
$pageName = 'ticket-list';

$perPage = 10; # 每一頁最多有幾筆

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    header('Location: ?page=1');
    exit; # 結束這支程式
}

// 篩選器排序
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'tid';
$order = isset($_GET['order']) ? $_GET['order'] : 'desc';
$order = $order === 'desc' ? 'desc' : 'asc';

// 獲取篩選條件（例如，這裡是 "music festival"）
$filterFestival = isset($_GET['filterFestival']) ? $_GET['filterFestival'] : '';

// 獲取 ticket_area 篩選條件
$filterTicketArea = isset($_GET['filterTicketArea']) ? $_GET['filterTicketArea'] : '';

// 篩選條件
$filterCondition = '';
if (!empty($filterFestival)) {
    $filterCondition = "WHERE aclass.class = '$filterFestival'";
}

// 添加 ticket_area 篩選條件
if (!empty($filterTicketArea)) {
    $filterCondition .= !empty($filterCondition) ? " AND ticket.ticket_area = '$filterTicketArea'" : " WHERE ticket.ticket_area = '$filterTicketArea'";
}

// 價格範圍
$minPrice = isset($_GET['min_price']) ? $_GET['min_price'] : '';
$maxPrice = isset($_GET['max_price']) ? $_GET['max_price'] : '';

// 添加價格範圍篩選條件
if (!empty($minPrice) && !empty($maxPrice)) {
    $priceCondition = "ticket.price BETWEEN $minPrice AND $maxPrice";
    $filterCondition .= !empty($filterCondition) ? " AND $priceCondition" : " WHERE $priceCondition";
}

// 模糊搜索
$searchKeyword = isset($_GET['search']) ? urldecode($_GET['search']) : '';
$searchCondition = '';
if (!empty($searchKeyword)) {
    $searchCondition = "AND (
        activities.activity_name LIKE '%$searchKeyword%' 
        OR artist.art_name LIKE '%$searchKeyword%'
        OR activities.location LIKE '%$searchKeyword%'
        OR activities.descriptions LIKE '%$searchKeyword%'
    )";
}

// 模糊搜索
$searchKeyword = isset($_GET['search']) ? urldecode($_GET['search']) : '';

// 在分頁連結中包含所有篩選條件
function buildQueryStringWithFilters()
{
    $queryString = '';

    // 筛选器排序
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'tid';
    $order = isset($_GET['order']) ? $_GET['order'] : 'desc';
    $order = $order === 'desc' ? 'desc' : 'asc';

    // 獲取篩選條件（例如，這裡是 "music festival"）
    $filterFestival = isset($_GET['filterFestival']) ? $_GET['filterFestival'] : '';

    // 價格範圍
    $minPrice = isset($_GET['min_price']) ? $_GET['min_price'] : '';
    $maxPrice = isset($_GET['max_price']) ? $_GET['max_price'] : '';

    // 模糊搜索
    $searchKeyword = isset($_GET['search']) ? urldecode($_GET['search']) : '';

    // 獲取 ticket_area 篩選條件
    $filterTicketArea = isset($_GET['filterTicketArea']) ? $_GET['filterTicketArea'] : '';

    // 構建包含所有篩選條件的查詢字符串
    $queryString .= '&sort=' . $sort . '&order=' . $order;
    if (!empty($filterFestival)) {
        $queryString .= '&filterFestival=' . urlencode($filterFestival);
    }
    if (!empty($minPrice)) {
        $queryString .= '&min_price=' . $minPrice;
    }
    if (!empty($maxPrice)) {
        $queryString .= '&max_price=' . $maxPrice;
    }
    if (!empty($searchKeyword)) {
        $queryString .= '&search=' . urlencode($searchKeyword);
    }
    // 添加 ticket_area 到查询字符串
    if (!empty($filterTicketArea)) {
        $queryString .= '&filterTicketArea=' . urlencode($filterTicketArea);
    }

    return $queryString;
}

$t_sql = "SELECT COUNT(tid) FROM ticket;";

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

    $sql = sprintf(
        "SELECT
*
FROM
ticket
JOIN
activities ON ticket.activities_id = activities.actid
JOIN
aclass ON activities.activity_class = aclass.id
JOIN
artist ON activities.artist_id = artist.id
%s
%s
ORDER BY %s %s
LIMIT %d, %d",
        $filterCondition,
        $searchCondition,
        $sort,
        $order,
        ($page - 1) * $perPage,
        $perPage
    );

    $rows = $pdo->query($sql)->fetchAll();
}

?>

<?php include __DIR__ . "/part/html-header.php"; ?>
<?php include __DIR__ . "/part/navbar-head.php"; ?>

<style>
    .image {
        max-width: 300px;
    }

    .model-a {
        text-decoration: none;
        color: lightgray;
    }

    .model-a:hover {
        color: white;
    }

    .tb .description {
        max-width: 400px;
        /* 设置最大宽度为400px */
        overflow: hidden;
        /* 当文字溢出时隐藏 */
        text-overflow: ellipsis;
        /* 当文字溢出时以省略号显示 */
        white-space: nowrap;
        /* 禁止文字换行 */
    }

    .spacing {
        letter-spacing: 2px;
    }

    #Modal .modal-body .filter .filterBtn.modelStyle {
        border-radius: 20px;
        background-color: teal;
    }
</style>

<div class="container-fluid p-0">
    <div class="row m-0">

        <div class="col-2 p-0"><?php include __DIR__ . "/part/left-bar.php"; ?></div>

        <div class="col-10 p-4">

            <h1 class="col-12 mt-5 mb-4 fw-bold">上架購票管理</h1>

            <!-- #region 文字搜尋 -->

            <div class="col-12 mb-3 d-flex justify-content-end">

                <div class="input-group mb-3 d-flex justify-content-end">
                    <form id="search_form" method="get" class="d-flex">
                        <input id="search_input" type="search" class="textSearch form-control border-5 me-3"
                            name="search" placeholder="請輸入要搜尋的內容..." style="width: 400px;"
                            value="<?= isset($searchKeyword) ? htmlspecialchars($searchKeyword) : '' ?>">
                        <!-- 其他筛选参数的输入框 -->
                        <button id="search_button" type="submit" class="btn btn-secondary fw-bold">搜尋</button>
                    </form>
                </div>

            </div>

            <!-- #endregion 文字搜尋 -->

            <!-- #region 區間價格篩選 -->

            <form id="search_form" action="?page=1" method="GET">
                <div class="col-12 mb-3 d-flex justify-content-end">
                    <div class="d-flex mb-3">
                        <input id="min_price_input" type="number" class="form-control border-5" name="min_price" min="1"
                            placeholder="最小價格" value="<?= $minPrice ?>">
                        <span class="mx-4 d-flex align-items-center fw-bold">一</span>
                        <input id="max_price_input" type="number" class="form-control border-5 me-4" name="max_price"
                            min="1" placeholder="最大價格" value="<?= $maxPrice ?>">
                        <button type="submit"
                            class="btn btn-secondary fw-bold text-nowrap d-flex align-items-center">搜尋</button>
                    </div>
                </div>
            </form>

            <!-- #endregion 區間價格篩選 -->

            <!-- #region 篩選活動座位 -->

            <div class="col-12 mb-3 d-flex justify-content-end">

                <div class="d-flex align-items-center fs-5 me-4 fw-bold bg-secondary text-white rounded-pill px-4">
                    篩選活動座位</div>

                <button id="btncheck1" data-value="A" type="button" class="btn btn-secondary fw-bold me-2 fs-5"
                    onclick="window.location.href = `?page=1&sort=<?= $sort ?>&order=<?= $order ?>&filterTicketArea=A`;">A</button>
                <button id="btncheck2" data-value="B" type="button" class="btn btn-secondary fw-bold me-2 fs-5"
                    onclick="window.location.href = `?page=1&sort=<?= $sort ?>&order=<?= $order ?>&filterTicketArea=B`;">B</button>
                <button id="btncheck3" data-value="C" type="button" class="btn btn-secondary fw-bold me-2 fs-5"
                    onclick="window.location.href = `?page=1&sort=<?= $sort ?>&order=<?= $order ?>&filterTicketArea=C`;">C</button>
                <button id="btncheck4" data-value="D" type="button" class="btn btn-secondary fw-bold me-2 fs-5"
                    onclick="window.location.href = `?page=1&sort=<?= $sort ?>&order=<?= $order ?>&filterTicketArea=D`;">D</button>
                <button id="btncheck5" data-value="E" type="button" class="btn btn-secondary fw-bold me-2 fs-5"
                    onclick="window.location.href = `?page=1&sort=<?= $sort ?>&order=<?= $order ?>&filterTicketArea=E`;">E</button>

            </div>

            <!-- #endregion 篩選活動座位 -->

            <!-- #region 新增購票、刪除所選、music festival、concert、顯示全部、篩選器 -->

            <div class="d-flex justify-content-between col-12 mb-4">
                <div>
                    <a class="btn btn-secondary fw-bold" href="ticket-add.php">新增購票</a>
                    <a id="dltAllSelect" class="btn btn-secondary fw-bold">刪除所選</a>
                    <a class="btn btn-secondary fw-bold"
                        href="?page=1&sort=<?= $sort ?>&order=<?= $order ?>&filterFestival=music festival">music
                        festival</a>
                    <a class="btn btn-secondary fw-bold"
                        href="?page=1&sort=<?= $sort ?>&order=<?= $order ?>&filterFestival=concert">concert</a>
                    <a class="reload btn btn-secondary fw-bold" href="ticket-list.php">顯示全部</a>
                </div>

                <div data-bs-toggle="modal" data-bs-target="#Modal">
                    <a class="btn btn-secondary fw-bold" href="javascript:;">篩選器&nbsp;&nbsp;<i
                            class="bi bi-filter"></i></a>
                </div>
            </div>

            <!-- #endregion 新增購票、刪除所選、music festival、concert、顯示全部、篩選器 -->

            <!-- #region Table -->

            <div class="col-12 mb-4 text-nowrap" style="overflow:auto">
                <table
                    class="table table-hover table-striped table-bordered caption-top text-center align-middle fw-bold">
                    <thead class="table-light">
                        <tr class="align-middle">
                            <th scope="col">全選<br><input type="checkbox" class="cb_all"></th>
                            <th scope="col">刪除資料</th>
                            <th scope="col">購票編號</th>
                            <th scope="col">活動圖片</th>
                            <th scope="col">活動名稱</th>
                            <th scope="col">演出藝人</th>
                            <th scope="col">活動地點</th>
                            <th scope="col">活動描述</th>
                            <th scope="col">活動時間</th>
                            <th scope="col">可購票數</th>
                            <th scope="col">購票價格</th>
                            <th scope="col">活動分類</th>
                            <th scope="col">活動座位</th>
                            <th scope="col">主辦單位</th>
                            <th scope="col">建立時間</th>
                            <th scope="col">修改時間</th>
                            <th scope="col">修改資料</th>
                        </tr>
                    </thead>
                    <tbody class="tb">
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td>
                                    <div>
                                        <input class="checkboxes form-check-input mx-auto text-center d-flex "
                                            type="checkbox" value="<?= $r['tid'] ?>" id="flexCheckDefault<?= $r['tid'] ?>">
                                    </div>
                                </td>
                                <td><a class="btn btn-danger" href="javascript: deleteOne(<?= $r['tid'] ?>)">
                                        <i class="bi bi-trash3"></i>
                                    </a></td>
                                <td><?= $r['tid'] ?></td>
                                <td data-bs-toggle="modal" data-bs-target="#modelImage" data-actid="<?= $r['actid'] ?>">
                                    <img src="../img/activities-img/<?= $r['picture'] ?>" class="image img-thumbnail"
                                        alt="activities_picture">
                                </td>
                                <td><?= $r['activity_name'] ?></td>
                                <td><?= $r['art_name'] ?></td>
                                <td><?= $r['location'] ?></td>
                                <td class="description" data-bs-toggle="modal" data-bs-target="#description"
                                    data-actid="<?= $r['actid'] ?>" onclick="getDescription(this)">
                                    <?= $r['descriptions'] ?>
                                </td>
                                <td><?= $r['a_date'] . '<br><br>' . $r['a_time'] ?></td>
                                <td><?= $r['counts'] ?></td>
                                <td><?= $r['price'] ?></td>
                                <td><?= $r['class'] ?></td>
                                <td><?= $r['ticket_area'] ?></td>
                                <td><?= $r['organizer'] ?></td>
                                <td><?= $r['created_at'] ?></td>
                                <td><?= $r['editTime'] ?></td>
                                <td>
                                    <a class="btn btn-warning" href="ticket-edit.php?tid=<?= $r['tid'] ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- #endregion Table -->

            <!-- #region pagination -->

            <div class="col-12 d-flex justify-content-center">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=1<?= buildQueryStringWithFilters() ?>">
                                <i class="fa-solid fa-angles-left"></i>
                            </a>
                        </li>
                        <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                            <a class="page-link"
                                href="?page=<?= max(1, $page - 1) ?><?= buildQueryStringWithFilters() ?>">
                                <i class="fa-solid fa-angle-left"></i>
                            </a>
                        </li>

                        <?php
                        // Calculate the range of page numbers to display
                        $start = max(1, min($page - 2, $totalPages - 4));
                        $end = min($totalPages, $start + 4);

                        // Display the page numbers within the range
                        for ($i = $start; $i <= $end; $i++):
                            ?>
                            <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                                <a class="page-link"
                                    href="?page=<?= $i ?><?= buildQueryStringWithFilters() ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link"
                                href="?page=<?= min($totalPages, $page + 1) ?><?= buildQueryStringWithFilters() ?>">
                                <i class="fa-solid fa-angle-right"></i>
                            </a>
                        </li>

                        <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $totalPages ?><?= buildQueryStringWithFilters() ?>">
                                <i class="fa-solid fa-angles-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- #endregion pagination -->

        </div>

    </div>

</div>

<!-- #region modal 篩選器 Filter -->

<div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="Modal" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark position-relative">

            <div class="position-absolute top-0 end-0">
                <button type="button" class="btn-close bg-white fs-4" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="d-flex justify-content-center my-4 mb-3">
                <div>
                    <h4 class="fw-bold text-white fs-3">搜尋篩選器</h4>
                </div>
            </div>

            <div class="modal-body fs-3 fw-bold text-white p-5 lh-lg spacing">
                <div class="container-fluid">
                    <table class="table table-borderless table-dark">
                        <thead>
                            <tr>
                                <th>購票編號</th>
                                <th>可購票數</th>
                                <th>購票價格</th>
                                <th>活動時間</th>
                                <th>建立時間</th>
                                <th>修改時間</th>
                            </tr>
                        </thead>
                        <tbody class="filter">
                            <tr>
                                <td class="filterBtn"><a class="model-a" href="?page=1&sort=tid&order=desc"
                                        onclick="preserveFilters(event, 'sort=tid&order=desc')">由大到小</a></td>
                                <td class="filterBtn"><a class="model-a" href="?page=1&sort=counts&order=desc"
                                        onclick="preserveFilters(event, 'sort=counts&order=desc')">由多到少</a></td>
                                <td class="filterBtn"><a class="model-a" href="?page=1&sort=price&order=desc"
                                        onclick="preserveFilters(event, 'sort=price&order=desc')">由高到低</a></td>
                                <td class="filterBtn"><a class="model-a" href="?page=1&sort=a_date&a_time&order=desc"
                                        onclick="preserveFilters(event, 'sort=a_date&a_time&order=desc')">最遠活動時間</a>
                                </td>
                                <td class="filterBtn"><a class="model-a" href="?page=1&sort=created_at&order=desc"
                                        onclick="preserveFilters(event, 'sort=created_at&order=desc')">最新建立時間</a></td>
                                <td class="filterBtn"><a class="model-a" href="?page=1&sort=editTime&order=desc"
                                        onclick="preserveFilters(event, 'sort=editTime&order=desc')">最新修改時間</a></td>
                            </tr>
                            <tr>
                                <td class="filterBtn"><a class="model-a" href="?page=1&sort=tid&order=asc"
                                        onclick="preserveFilters(event, 'sort=tid&order=asc')">由小到大</a></td>
                                <td class="filterBtn"><a class="model-a" href="?page=1&sort=counts&order=asc"
                                        onclick="preserveFilters(event, 'sort=counts&order=asc')">由少到多</a></td>
                                <td class="filterBtn"><a class="model-a" href="?page=1&sort=price&order=asc"
                                        onclick="preserveFilters(event, 'sort=price&order=asc')">由低到高</a></td>
                                <td class="filterBtn"><a class="model-a" href="?page=1&sort=a_date&a_time&order=asc"
                                        onclick="preserveFilters(event, 'sort=a_date&a_time&order=asc')">最近活動時間</a></td>
                                <td class="filterBtn"><a class="model-a" href="?page=1&sort=created_at&order=asc"
                                        onclick="preserveFilters(event, 'sort=created_at&order=asc')">最舊建立時間</a></td>
                                <td class="filterBtn"><a class="model-a" href="?page=1&sort=editTime&order=asc"
                                        onclick="preserveFilters(event, 'sort=editTime&order=asc')">最舊修改時間</a></td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- #endregion modal 篩選器 Filter -->

<!-- #region modal description -->

<div class="modal fade " id="description" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content bg-dark position-relative">
            <div class="position-absolute top-0 end-0">
                <button type="button" class="btn-close bg-white fs-4" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="d-flex justify-content-center my-4 mb-3">
                <div>
                    <h4 class="fw-bold text-white fs-2">活動描述</h4>
                </div>
            </div>
            <div class="modal-body fs-2 fw-bold text-white p-5 lh-lg spacing" id="descriptionContent"></div>
        </div>
    </div>
</div>

<!-- #endregion modal description -->

<!-- #region modal image -->

<div class="modal fade" id="modelImage" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark position-relative">
            <div class="position-absolute top-0 end-0">
                <button type="button" class="btn-close bg-white fs-4" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="d-flex justify-content-center my-4 mb-3">
                <div>
                    <h4 class="fw-bold text-white fs-2">活動圖片</h4>
                </div>
            </div>
            <div class="modal-body d-flex justify-content-center align-items-center">
                <img id="modalImage" src="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- #endregion modal image -->

<?php include __DIR__ . "/part/scripts.php"; ?>

<script>

    // 模糊搜尋
    document.getElementById('search_form').addEventListener('submit', function (event) {
        // 防止預設的表單提交行為
        event.preventDefault();

        // 手動提交表單
        this.submit();
    });

    // 保留篩選條件並跳轉頁面
    function preserveFilters(event, filter) {
        event.preventDefault(); // 防止點擊分頁連結默認跳轉
        var pageLink = event.target.href; // 獲取點擊的分頁連結
        var baseUrl = pageLink.split('?')[0]; // 獲取連結的基本部分
        var params = new URLSearchParams(window.location.search); // 解析當前 URL 中的參數

        // 清除原始的分頁參數，並將其設置為 1
        params.set('page', '1');

        // 設置新的排序和篩選條件
        params.set('sort', filter.split('&')[0].split('=')[1]); // 設置新的排序條件
        params.set('order', filter.split('&')[1].split('=')[1]); // 設置新的排序方式

        // 在基本部分後添加所有參數並跳轉
        window.location.href = baseUrl + '?' + params.toString();
    }

    // 價格區間篩選
    document.getElementById("search_button").addEventListener("click", function () {
        var minPrice = document.getElementById("min_price_input").value;
        var maxPrice = document.getElementById("max_price_input").value;

        // 構建 URL
        var url = "http://localhost/music/web/ticket-list.php?";
        if (minPrice !== "") {
            url += "min_price=" + encodeURIComponent(minPrice) + "&";
        }
        if (maxPrice !== "") {
            url += "max_price=" + encodeURIComponent(maxPrice);
        }

        // 更新搜尋按鈕的 href 屬性
        document.getElementById("search_button").href = url;
    });

    // 保留篩選條件和排序方式
    function preserveFiltersAndSort(event, filter) {
        event.preventDefault(); // 防止點擊分頁連結默認跳轉
        var pageLink = event.target.href; // 獲取點擊的分頁連結
        var baseUrl = pageLink.split('?')[0]; // 獲取連結的基本部分
        var params = new URLSearchParams(window.location.search); // 解析當前 URL 中的參數

        // 清除原始的分頁參數，並將其設置為 1
        params.set('page', '1');

        // 設置新的排序和篩選條件
        params.set('sort', filter.split('&')[0].split('=')[1]); // 設置新的排序條件
        params.set('order', filter.split('&')[1].split('=')[1]); // 設置新的排序方式

        // 將輸入框的值添加到 URL 參數中
        var minPrice = document.getElementById("min_price_input").value;
        var maxPrice = document.getElementById("max_price_input").value;
        if (minPrice !== "") {
            params.set('min_price', minPrice);
        }
        if (maxPrice !== "") {
            params.set('max_price', maxPrice);
        }

        // 在基本部分後添加所有參數並跳轉
        window.location.href = baseUrl + '?' + params.toString();
    }

    // 購票價格排序
    var searchForm = document.getElementById('search_form');
    searchForm.addEventListener('submit', function (event) {
        event.preventDefault(); // 阻止表單的默認提交行為
        var searchQuery = document.getElementById('search_input').value;
        // 在這裡可以根據搜索內容執行相應的搜索操作，例如向服務器發送Ajax請求
        // 這裡只是簡單地打印搜索內容到控制台
        console.log('搜索內容：', searchQuery);
    });

    // 單個刪除
    const deleteOne = (tid) => {
        // 確認是否刪除特定編號的資料
        if (confirm(`是否要刪除編號為 ${tid} 的資料?`)) {
            location.href = `ticket-list-delete.php?tid=${tid}`; // 跳轉至刪除頁面
        }
    }

    // 全選與反選
    let cb_all = document.querySelector('.cb_all');
    let tbs = document.querySelector('.tb').querySelectorAll('input');

    cb_all.addEventListener('click', function () {
        for (let i = 0; i < tbs.length; i++) {
            tbs[i].checked = this.checked; // 將所有單選框的選中狀態設置為全選框的選中狀態
        }
    });

    for (let i = 0; i < tbs.length; i++) {
        tbs[i].onclick = function () {
            let flag = true;
            for (let i = 0; i < tbs.length; i++) {
                if (!tbs[i].checked) {
                    flag = false;
                    break;
                }
            }
            cb_all.checked = flag; // 若有單選框未選中，則全選框也取消選中
        }
    }

    // 刪除所選Script
    const dltAllSelect = document.getElementById("dltAllSelect");
    const checkboxes2 = document.querySelectorAll(".checkboxes");

    dltAllSelect.addEventListener('click', function () {
        let selectedIds = []; // 儲存被選中項目的 ID

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
                location.href = `ticket-delete-more.php?tids=${selectedIds.join(',')}`;
            }
        } else {
            alert('請先選擇要刪除的資料');
        }
    });

    // 篩選器 filter 背景顏色變化

    // 取得所有帶有 "model-a" 類別的元素
    const links = document.querySelectorAll('#Modal .modal-body .filter .filterBtn');

    // 從本地存儲中獲取活動連結
    const activeLink = localStorage.getItem('activeLink');

    if (activeLink) {
        for (let i = 0; i < links.length; i++) {
            if (links[i].textContent === activeLink) {
                links[i].classList.add('modelStyle');
                break;
            }
        }
    }

    // 點擊連結時設置LocalStorage並更新樣式
    for (let i = 0; i < links.length; i++) {
        links[i].addEventListener('click', function () {
            // 先清除所有連結的背景色
            for (let j = 0; j < links.length; j++) {
                links[j].classList.remove('modelStyle');
            }
            // 然後設定被點擊的連結的背景色
            this.classList.add('modelStyle');

            // 將被點擊的連結的內容保存到LocalStorage中
            localStorage.setItem('activeLink', this.textContent);
        });
    }

    // 價格範圍限制最小最大價格

    // 取得最小價格輸入框和最大價格輸入框
    const minPriceInput = document.querySelector('input[name="min_price"]');
    const maxPriceInput = document.querySelector('input[name="max_price"]');

    // 當最小價格輸入框內容發生變化時觸發
    minPriceInput.addEventListener('input', function () {
        // 更新最大價格輸入框的最小值為最小價格輸入框的值
        maxPriceInput.min = this.value;
        // 如果最大價格輸入框的值小於最小價格輸入框的值，則將其設置為最小價格輸入框的值
        if (parseInt(maxPriceInput.value) < parseInt(this.value)) {
            maxPriceInput.value = this.value;
        }
    });

    // model描述

    function getDescription(element) {
        var actid = element.getAttribute('data-actid');

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('descriptionContent').innerHTML = this.responseText;
            }
        };
        xhr.open("GET", "ticket-get-description.php?actid=" + actid, true);
        xhr.send();
    }

    // model圖片

    function displayImage(event) {
        var imageSrc = event.target.src;
        var modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc;
    }

    // Add event listener to each image
    var images = document.querySelectorAll('.image');
    images.forEach(function (image) {
        image.addEventListener('click', displayImage);
    });

</script>

<?php include __DIR__ . "/part/html-footer.php"; ?>