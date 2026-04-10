<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Form Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f5f7fa;
      font-family: Arial, sans-serif;
    }

    .form-container {
      max-width: 400px;
      margin: 80px auto;
      padding: 25px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .btn-primary {
      width: 100%;
      border-radius: 8px;
    }

    .form-control {
      border-radius: 8px;
    }

    .form-check-label {
      cursor: pointer;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Login</h2>

<form method="POST" action="/login">
    @csrf
  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Password</label>
    <input type="password" name="password" class="form-control" required>
  </div>

  <button type="submit" class="btn btn-primary w-100">
    Login
  </button>
</form>

</body>
</html>