<?php
$output = [
  'success' => false,
  'code' => 0,
];

# 做檔案的篩選, 決定副檔名
$exts = [
  'image/jpeg' => '.jpg',
  'image/png' => '.png',
  'image/webp' => '.webp'
];

$f = sha1(uniqid() . rand()); # 隨機的主檔名 

# 先確定有上傳的欄位
if (!empty($_FILES) and !empty($_FILES['photo'])) {
  $output['code'] = 100;
  # 再確定上傳過程沒有出錯
  if ($_FILES['photo']['error'] === 0) {
    $output['code'] = 200;
    # 判斷類型是不是符合我們的條件
    if (!empty($exts[$_FILES['photo']['type']])) {
      $output['code'] = 300;
      # 依照 mime-type 決定副檔名
      $ext = $exts[$_FILES['photo']['type']]; # 副檔名

      $filename = __DIR__ . '/../img/members-img/' . $f . $ext;
      $result = move_uploaded_file($_FILES['photo']['tmp_name'], $filename);
      $output['success'] = $result;
      $output['filename'] = $f . $ext;
    }
  }
}



header('Content-Type: application/json');

echo json_encode($output);
