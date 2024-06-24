<?php
require __DIR__ . '/admin-required.php';

if (!isset($_SESSION)) {
  session_start();
}

$title = '新增通知列表';
$pageName = 'activities-add';
?>


<?php include __DIR__ . "/part/html-header.php"; ?>
<?php include __DIR__ . "/part/navbar-head.php"; ?>

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
    <div class="col-8 mt-3 mx-auto">
      <div class="card my-3">
        <div class="card-body">
          <h4 class="card-title fw-bold">新增通知</h4>
          <form name="form_notification" onsubmit="sendData(event)">
            <div class="mb-3">
              <label for="title" class="form-label">標題</label>
              <input type="text" class="form-control" id="title" name="title">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="content" class="form-label">內容</label>
              <input type="text" class="form-control" id="content" name="content">
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="sent_time" class="form-label">發送時間</label>
              <input type="datetime-local" class="form-control" id="sent_time" name="sent_time">
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="noti_class" class="form-label">類別</label>
              <select class="form-select" aria-label="Default select example" id="noti_class" name="noti_class">
                <option selected>--</option>
                <option value="1">artist</option>
                <option value="2">activity</option>
              </select>
            </div>

            <button type="submit" class="btn btn-primary">新增</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Start-->
<div class="modal fade" id="staticBackdropA" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="staticBackdropLabelA" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabelA">新增成功</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">
          資料新增成功
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="location.href='notification-list.php'">到列表頁</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續新增</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal End-->

<?php include __DIR__ . "/part/scripts.php"; ?>
<script>
  // const notification_class = document.form_notification.notification_class;
  const notification_nameField = document.form_notification.title;

  const sendData = e => {
    e.preventDefault(); // 不要讓 form_notification 以傳統的方式送出

    // notification_class.style.border = '1px solid #CCCCCC';
    // notification_class.nextElementSibling.innerText = '';
    notification_nameField.style.border = '1px solid #CCCCCC';
    notification_nameField.nextElementSibling.innerText = '';

    // TODO: 欄位資料檢查
    let isPass = true; // 表單有沒有通過檢查

    // if ((notification_class.value < 1) || (notification_class.value > 2)) {
    // 	isPass = false;
    // 	notification_nameField.style.border = '1px solid red';
    // 	notification_nameField.nextElementSibling.innerText = '請選擇類別';
    // }

    if (notification_nameField.value.length < 2) {
      isPass = false;
      notification_nameField.style.border = '1px solid red';
      notification_nameField.nextElementSibling.innerText = '請填寫正確的通知名稱';
    }

    // 有通過檢查, 才要送表單
    if (isPass) {
      const fd = new FormData(document.form_notification); // 沒有外觀的表單物件

      fetch('notification-add-api.php', {
        method: 'POST',
        body: fd, // Content-Type: multipart/form-data
      }).then(r => r.json())
        .then(data => {
          console.log(data);
          if (data.success) {
            myModalA.show();
          } else { }
        })
        .catch(ex => console.log(ex))
    }
  };

  const myModalA = new bootstrap.Modal('#staticBackdropA')
</script>
<?php include __DIR__ . "/part/html-footer.php"; ?>