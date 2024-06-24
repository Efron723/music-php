<?php
if (!isset($_SESSION)) {
  session_start();
}
if (isset($_SESSION['admin'])) {
  header('Location: index_R3.php');
  exit;
}

$title = '登入';
$pageName = 'login';
?>

<?php include __DIR__ . "/part/html-header.php"; ?>

<style>
  html,
  body {
    height: 100%;
  }

  body {
    text-align: center;
    display: flex;
    align-items: center;
    padding-top: 40px;
    padding-bottom: 40px;
    background-color: #f5f5f5;
  }

  .form-signin {
    width: 100%;
    max-width: 330px;
    padding: 15px;
    margin: auto;
  }

  .form-signin .checkbox {
    font-weight: 400;
  }

  .form-signin .form-floating:focus-within {
    z-index: 2;
  }

  .form-signin input[type="email"] {
    margin-bottom: -1px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }

  .form-signin input[type="password"] {
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
  }


  .bd-placeholder-img {
    font-size: 1.125rem;
    text-anchor: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
  }

  @media (min-width: 768px) {
    .bd-placeholder-img-lg {
      font-size: 3.5rem;
    }
  }

  .bxl-apple {
    font-size: 80px;
    margin-bottom: 30px;
  }
</style>


<main class="form-signin">
  <form name="form1" onsubmit="sendData(event)">
    <div><i class='bx bxl-apple'></i></div>
    <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

    <div class="mb-3 form-floating">
      <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email">
      <label for="floatingInput">Email address</label>
    </div>
    <div class="mb-3 form-floating">
      <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="passwords">
      <label for="floatingPassword">Password</label>
    </div>
    <div class="d-flex">
      <button class="me-3 w-50 btn btn-md btn-primary" type="submit">Sign in</button>
      <a class="me-3 w-50 btn btn-md btn-primary" type="submit" href="index_R3.php">Home</a>
      <a class="w-50 btn btn-md btn-primary" type="submit" href="quick-login.php">Quick</a>
    </div>
    <p class="mt-5 mb-3 text-muted">&copy; music</p>
  </form>
</main>

<?php include __DIR__ . "/part/scripts.php"; ?>
<script>
  const nameField = document.form1.name;
  const emailField = document.form1.email;

  function validateEmail(email) {
    const re =
      /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }


  const sendData = e => {
    e.preventDefault(); // 不要讓 form1 以傳統的方式送出

    // nameField.style.border = '1px solid #CCCCCC';
    // nameField.nextElementSibling.innerText = '';
    // emailField.style.border = '1px solid #CCCCCC';
    // emailField.nextElementSibling.innerText = '';
    // TODO: 欄位資料檢查

    let isPass = true; // 表單有沒有通過檢查
    if (!validateEmail(emailField.value)) {
      isPass = false;
      emailField.style.border = '1px solid red';
      emailField.nextElementSibling.innerText = '請填寫正確的 Email';
    }
    // 有通過檢查, 才要送表單
    if (isPass) {
      const fd = new FormData(document.form1); // 沒有外觀的表單物件
      fetch('login-api.php', {
        method: 'POST',
        body: fd, // Content-Type: multipart/form-data
      }).then(r => r.json())
        .then(data => {
          console.log(data);
          if (data.success) {
            location.href = 'index_R3.php'; //登入成功後跳轉頁面
          } else {
            myModal.show();
          }
        })
        .catch(ex => console.log(ex))
    }
  };
  const myModal = new bootstrap.Modal('#staticBackdrop')
</script>