<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

$title = '編輯購票';
$pageName = 'ticket-edit';

$tid = isset($_GET['tid']) ? intval($_GET['tid']) : 0;
if ($tid < 1) {
    header('Location: ticket-list.php');
    exit;
}

$sql = "SELECT 
*
FROM 
ticket
JOIN 
activities ON ticket.activities_id = activities.actid
JOIN 
aclass ON activities.activity_class = aclass.id
JOIN 
artist ON activities.artist_id = artist.id WHERE tid={$tid}";

$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header('Location: ticket-list.php');
    exit;
}

?>

<?php include __DIR__ . "/part/html-header.php"; ?>
<?php include __DIR__ . "/part/navbar-head.php"; ?>

<style>
    .add {
        border-radius: 30px;
        margin-top: 120px;
        padding: 40px 100px;
    }
</style>

<div class="container-fluid p-0 mb-5">
    <div class="row m-0">
        <div class="col-2 p-0"><?php include __DIR__ . "/part/left-bar.php"; ?></div>
        <div class="col-7 mx-auto">

            <form id="ticketForm" name="ticketForm" class="needs-validation shadow-lg add" novalidate>

                <input type="hidden" name="tid" value="<?= $row['tid'] ?>">

                <h1 class="text-center mb-5 fw-bold">編輯購票</h1>

                <div class="row mb-4 justify-content-evenly">
                    <div class="col-2 form-label">
                        <label for="tid" class="form-label fs-5 fw-bold">編號</label>
                    </div>
                    <div class="col-8">
                        <input id="tid" type="text" class="form-control rounded-3 border-4" disabled
                            value="<?= $row['tid'] ?>">
                    </div>
                </div>

                <div class="row mb-4 justify-content-evenly">
                    <div class="col-2 form-label">
                        <label for="activities_id" class="form-label fs-5 fw-bold">選擇活動</label>
                    </div>
                    <div class="col-8">
                        <select name="activities_id" class="form-select rounded-3 border-4" id="activities_id" required>
                        </select>
                        <div class="invalid-feedback">
                            請選擇活動
                        </div>
                    </div>
                </div>

                <div class="row mb-4 justify-content-evenly">
                    <div class="col-2 form-label">
                        <label for="ticket_area" class="form-label fs-5 fw-bold">選擇區域</label>
                    </div>
                    <div class="col-8">
                        <select name="ticket_area" class="form-select rounded-3 border-4" id="ticket_area" required>
                        </select>
                        <div class="invalid-feedback">
                            請選擇區域
                        </div>
                    </div>
                </div>

                <div class="row mb-4 justify-content-evenly">
                    <div class="col-2 form-label">
                        <label for="counts" class="form-label fs-5 fw-bold">區域票數</label>
                    </div>
                    <div class="col-8">
                        <input type="number" min="1" id="counts" name="counts" class="form-control rounded-3 border-4" required
                            placeholder="請輸入票數" value="<?= $row['counts'] ?>">
                        <div class="invalid-feedback">
                            請輸入票數
                        </div>
                    </div>
                </div>

                <div class="row mb-4 justify-content-evenly">
                    <div class="col-2 form-label">
                        <label for="price" class="form-label fs-5 fw-bold">區域票價</label>
                    </div>
                    <div class="col-8">
                        <input type="number" min="1" id="price" name="price" class="form-control rounded-3 border-4" required
                            placeholder="請輸入票價" value="<?= $row['price'] ?>">
                        <div class="invalid-feedback">
                            請輸入票價
                        </div>
                    </div>
                </div>

                <div class="text-start ms-5">
                    <button type="submit" class="btn btn-primary fs-5 fw-bold">送出</button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- Modal edit-success -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">修改成功</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" role="alert">
                    資料修改成功
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" onclick="location.href='ticket-list.php'">到列表頁</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續新增</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal edit-fail -->
<div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">編輯失敗</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert">
                    資料編輯失敗
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" onclick="location.href='ticket-list.php'">到列表頁</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續新增</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal edit-same -->
<div class="modal fade" id="staticBackdrop3" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">編輯資料相同請重新輸入</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    編輯資料相同請重新輸入
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" onclick="location.href='ticket-list.php'">到列表頁</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續新增</button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/part/scripts.php"; ?>

<script>
    const myModal = new bootstrap.Modal('#staticBackdrop');
    const myModal2 = new bootstrap.Modal('#staticBackdrop2');
    const myModal3 = new bootstrap.Modal('#staticBackdrop3');
    const ticketForm = document.getElementById('ticketForm');

    const sendData = e => {
        e.preventDefault();

        let isPass = true;

        const activities_id = document.getElementById('activities_id').value;
        const ticket_area = document.getElementById('ticket_area').value;
        const counts = document.getElementById('counts').value;
        const price = document.getElementById('price').value;

        if (!activities_id.trim() || !ticket_area.trim() || !counts.trim() || !price.trim()) {
            isPass = false;
        }

        const ticketValuesMatch = activities_id === "<?= $row['activities_id'] ?>" &&
            ticket_area === "<?= $row['ticket_area'] ?>" &&
            counts === "<?= $row['counts'] ?>" &&
            price === "<?= $row['price'] ?>";

        if (ticketValuesMatch) {
            isPass = false;
            myModal3.show();
        }

        if (isPass) {
            const fd = new FormData();
            fd.append('activities_id', activities_id);
            fd.append('ticket_area', ticket_area);
            fd.append('counts', counts);
            fd.append('price', price);
            // edit 這邊要加欄位
            fd.append('tid', document.querySelector('input[name="tid"]').value);

            fetch('ticket-edit-api.php', {
                method: 'POST',
                body: fd,
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.success) {
                        myModal.show();
                    } else {
                        myModal2.show();
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    };


    // 獲取AJAX請求獲取ticket_area資料
    fetch('ticket-allData.php')
        .then(response => response.json())
        .then(data => {
            // 獲取下拉選單元素
            const areaSelect = document.getElementById('ticket_area');

            // 清空下拉選單
            areaSelect.innerHTML = '';

            // 先添加預設的選項
            const areaOp = "<?php echo $row['ticket_area'] ?>";
            const defaultOption = document.createElement('option');
            defaultOption.value = areaOp;
            defaultOption.innerText = areaOp;
            areaSelect.appendChild(defaultOption);

            // 用於追蹤已添加值的數量
            let count = 0;
            // 用於檢查是否已經保留了第一筆傳入的值
            let firstValueReserved = false;

            // 將區域資料動態添加到下拉選單中，保留第一筆傳入的值，並確保不重複
            data.forEach(ticket => {
                if (!firstValueReserved && ticket.ticket_area === parseInt(areaOp)) {
                    // 保留第一筆傳入的值
                    const option = document.createElement('option');
                    option.value = ticket.ticket_area;
                    option.textContent = ticket.ticket_area;
                    areaSelect.appendChild(option);
                    firstValueReserved = true; // 設置標記為 true，表示已經保留了第一筆值
                } else if (count < 5 && ticket.ticket_area !== parseInt(areaOp) && !areaSelect.querySelector(`option[value="${ticket.ticket_area}"]`)) {
                    // 確保不重複，且不是第一筆傳入的值
                    const option = document.createElement('option');
                    option.value = ticket.ticket_area;
                    option.textContent = ticket.ticket_area;
                    areaSelect.appendChild(option);
                    count++;
                }
            });

            // 添加事件監聽器
            areaSelect.addEventListener('change', () => {
                const selectedArea = areaSelect.value;
                console.log(selectedArea);
            });

            // 為表單添加提交事件監聽器
            ticketForm.addEventListener('submit', sendData);
        })

    // 獲取AJAX請求獲取ticket-get-activities-api.php資料
    fetch('ticket-get-activities-api.php')
        .then(response => response.json())
        .then(data => {

            // 獲取下拉選單元素
            const activitiesSelect = document.getElementById('activities_id');
            // 清空下拉選單
            activitiesSelect.innerHTML = '';

            // 先添加預設的選項
            const actidOp = "<?php echo $row['actid'] ?>";
            const acnameOp = "<?php echo $row['activity_name'] ?>";
            const defaultOption = document.createElement('option');
            defaultOption.value = actidOp;
            defaultOption.innerText = acnameOp;
            activitiesSelect.appendChild(defaultOption);

            // 將activities資料動態添加到下拉選單中，排除actidOp
            data.forEach(activities => {
                if (activities.actid !== parseInt(actidOp)) { // 排除 PHP 變數中的值
                    const option = document.createElement('option');
                    option.value = activities.actid;
                    option.textContent = activities.activity_name;
                    activitiesSelect.appendChild(option);
                }
            });

            // 為表單添加提交事件監聽器
            ticketForm.addEventListener('submit', sendData);

            // 在選擇活動 select 欄位變化時執行的函數
            const onActivitySelectChange = () => {
                const selectedActivityId = activitiesSelect.value; // 取得選擇的活動ID
                console.log(selectedActivityId);
                // 找到選擇的活動的對象
                const selectedActivity = data.find(activity => activity.actid === parseInt(selectedActivityId));
                // 在這裡處理從後端獲取的單個活動資訊
                console.log(selectedActivity);
            };

            // 監聽選擇活動 select 欄位的變化事件
            activitiesSelect.addEventListener('change', onActivitySelectChange);
        })
        .catch(error => console.error('Error fetching activities:', error));

</script>

<?php include __DIR__ . "/part/html-footer.php"; ?>