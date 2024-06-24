<?php
require __DIR__ . '/admin-required.php';
require __DIR__ . '/../config/pdo-connect.php';

$title = "修改活動列表";
$pageName = 'activities-edit';

$actid = isset($_GET['actid']) ? intval($_GET['actid']) : 0;
if ($actid < 1) {
  header('Location: activities-list.php');
  exit;
}

// $sql = "SELECT * FROM activities WHERE actid={$actid}";
$sql = "SELECT * 
FROM activities 
JOIN artist ON artist_id = artist.id 
JOIN aclass ON activities.activity_class = aclass.id 
WHERE actid={$actid}";

$row = $pdo->query($sql)->fetch();
if (empty($row)) {
  header('Location: activities-list.php');
  exit;
}

// echo json_encode($row);


?>
<?php include __DIR__ . '/part/html-header.php' ?>
<?php include __DIR__ . '/part/navbar-head.php' ?>
<style>
  form .mb-3 .form-text {
    color: red;
    font-weight: 800;
  }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-2 p-0">
      <?php include __DIR__ . "/part/left-bar.php"; ?>
    </div>
    <div class="col-8  mt-3 mx-auto">
      <div class="card my-3">
        <div class="card-body">
          <h4 class="card-title fw-bold">編輯資料</h4>
          <form id="form_activities" name="form_activities" onsubmit="sendData(event)">

            <input type="hidden" name="actid" value="<?= $row['actid'] ?>">

            <div class="mb-3">
              <label for="actid" class="form-label">編號</label>
              <input type="text" class="form-control" disabled value="<?= $row['actid'] ?>">
            </div>

            <div class="mb-3">
              <label for="activity_class" class="form-label">類別</label>
              <select class="form-select" aria-label="Default select example" id="activity_class" name="activity_class">
                <option value="<?= $row['activity_class'] ?>" selected><?= $row['class'] ?></option>
                <option value="1">concert</option>
                <option value="2">music festival</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="activity_name" class="form-label">活動名稱</label>
              <input type="text" class="form-control" id="activity_name" name="activity_name"
                value="<?= $row['activity_name'] ?>">
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="a_date" class="form-label">日期</label>
              <input type="date" class="form-control" id="a_date" name="a_date" value="<?= $row['a_date'] ?>">
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="a_time" class="form-label">時間</label>
              <input type="time" class="form-control" id="a_time" name="a_time" value="<?= $row['a_time'] ?>">
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="location" class="form-label">地點</label>
              <input type="text" class="form-control" id="location" name="location" value="<?= $row['location'] ?>">
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="address" class="form-label">地址</label>
              <input type="text" class="form-control" id="address" name="address" value="<?= $row['address'] ?>">
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="descriptions" class="form-label">活動內容</label>
              <textarea class="form-control" id="descriptions" name="descriptions" cols="30"
                rows="3"><?= $row['descriptions'] ?></textarea>
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="organizer" class="form-label">主辦單位</label>
              <input type="text" class="form-control" id="organizer" name="organizer" value="<?= $row['organizer'] ?>">
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="artist_id" class="form-label">表演者</label>
              <select class="form-select" id="artist_id" name="artist_id">
              </select>
              <div class="form-text">
              </div>
            </div>

            <div class="mb-3">
              <label for="picture" class="form-label">圖片</label>
              <div class="mb-3">
                <img src="../img/activities-img/<?= $row['picture'] ?>" class="img-thumbnail" alt="">
              </div>
              <input type="file" class="form-control" id="picture" name="picture">
              <div class="form-text"></div>
            </div>

            <button type="submit" class="btn btn-primary">修改</button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>
<!-- ModalA 資料修改成功 -->
<div class="modal fade" id="staticBackdropA" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="staticBackdropLabelA" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabelA">修改成功</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">
          資料修改成功
        </div>
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-primary" onclick="location.href='activities-list.php'">到列表頁</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續編輯</button>
      </div>
    </div>
  </div>
</div>

<!-- ModalB 資料沒有修改 -->
<div class="modal fade" id="staticBackdropB" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="staticBackdropLabelB" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabelB">資料沒有修改</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert">
          資料沒有修改
        </div>
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-primary" onclick="location.href='activities-list.php'">到列表頁</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續編輯</button>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/part/scripts.php' ?>
<script>
  // const activity_class = document.form_activities.activity_class;
  const activity_nameField = document.form_activities.activity_name;

  const sendData = e => {
    e.preventDefault(); // 不要讓 form_activities 以傳統的方式送出

    // activity_class.style.border = '1px solid #CCCCCC';
    // activity_class.nextElementSibling.innerText = '';
    activity_nameField.style.border = '1px solid #CCCCCC';
    activity_nameField.nextElementSibling.innerText = '';

    // TODO: 欄位資料檢查
    let isPass = true; // 表單有沒有通過檢查

    // if ((activity_class.value < 1) || (activity_class.value > 2)) {
    // 	isPass = false;
    // 	activity_nameField.style.border = '1px solid red';
    // 	activity_nameField.nextElementSibling.innerText = '請選擇類別';
    // }

    if (activity_nameField.value.length < 2) {
      isPass = false;
      activity_nameField.style.border = '1px solid red';
      activity_nameField.nextElementSibling.innerText = '請填寫正確的活動名稱';
    }

    // 有通過檢查, 才要送表單
    if (isPass) {
      const fd = new FormData(document.form_activities); // 沒有外觀的表單物件

      fetch('activities-edit-api.php', {
        method: 'POST',
        body: fd, // Content-Type: multipart/form-data
      }).then(r => r.json())
        .then(data => {
          console.log(data);
          if (data.success) {
            myModalA.show();
          } else {
            myModalB.show();
          }
        })
        .catch(ex => console.log(ex))
    }
  };

  const myModalA = new bootstrap.Modal('#staticBackdropA');
  const myModalB = new bootstrap.Modal('#staticBackdropB');

  // 發送AJAX請求獲取activities資料
  fetch('activities-get-artist-api.php')
    .then(response => response.json())
    .then(data => {

      // 獲取下拉選單元素
      const artistSelect = document.getElementById('artist_id');
      // 清空下拉選單
      artistSelect.innerHTML = '';

      // 先添加預設的選項
      const idOp = "<?php echo $row['id'] ?>";
      const nameOp = "<?php echo $row['art_name'] ?>";
      const defaultOption = document.createElement('option');
      defaultOption.value = idOp;
      defaultOption.innerText = nameOp;
      // defaultOption.value = ""; // 給定一個空的 value
      artistSelect.appendChild(defaultOption);
      // 將artist資料動態添加到下拉選單中
      data.forEach(artist => {
        const optionA = document.createElement('option');
        optionA.value = artist.id;
        optionA.textContent = artist.art_name;
        artistSelect.appendChild(optionA);
      });

      // 為表單添加提交事件監聽器
      const activitiesForm = document.getElementById('form_activities');
      //activitiesForm.addEventListener('submit', sendData);

      // 在選擇活動 select 欄位變化時執行的函數
      const onArtistSelectChange = () => {
        const selectedArtistId = artistSelect.value; // 取得選擇的活動ID
        console.log(selectedArtistId);
        // 找到選擇的活動的對象
        const selectedArtist = data.find(artist => artist.id === parseInt(selectedArtistId));
        // 在這裡處理從後端獲取的單個活動資訊
        console.log(selectedArtist);
      };

      // 監聽選擇活動 select 欄位的變化事件
      artistSelect.addEventListener('change', onArtistSelectChange);
    })
    .catch(error => console.error('Error fetching artist:', error));
</script>
<?php include __DIR__ . '/part/html-footer.php' ?>