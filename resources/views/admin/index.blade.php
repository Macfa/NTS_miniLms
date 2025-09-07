<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>관리자 로그인</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body { font-family: Arial, sans-serif; background: #f5f5f5; }
    .login-container {
      width: 350px; margin: 100px auto; padding: 30px;
      background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .login-container h2 { text-align: center; margin-bottom: 20px; }
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; }
    input[type="text"], input[type="password"] {
      width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;
    }
    button {
      width: 100%; padding: 10px; background: #007bff; color: #fff;
      border: none; border-radius: 4px; font-size: 16px; cursor: pointer;
    }
    button:hover { background: #0056b3; }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>관리자 로그인</h2>
    <form method="POST" action="{{ route('admin.login') }}">
      @csrf
      <div class="form-group">
        <label for="email">이메일</label>
        <input type="text" id="email" name="email" required autofocus>
      </div>
      <div class="form-group">
        <label for="password">비밀번호</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit">로그인</button>
    </form>
  </div>
</body>
</html>