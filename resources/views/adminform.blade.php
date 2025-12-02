<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CLOT Admin</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background: #6c5ce7;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .container {
      display: flex;
      flex-direction: column;
      background: #fff;
      width: 900px;
      max-width: 95%;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
      transform: translateY(100px);
      opacity: 0;
      animation: slideUp 0.8s ease-out forwards;
    }

    @keyframes slideUp {
      from {
        transform: translateY(100px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
         opacity: 1;
      }
    }

    /* Top bar with Register button */
    .top-bar {
      display: flex;
      justify-content: flex-end;
      padding: 15px 20px;
      background: #ffffff;
    }

    .register-btn {
      padding: 8px 20px;
      border: none;
      border-radius: 20px;
      background: #eee;
      color: #444;
      font-size: 14px;
      cursor: pointer;
      transition: 0.3s;
      text-decoration: none;
      display: inline-block;
    }

    .register-btn:hover {
      background: #ddd;
    }

    /* Main content: form + illustration */
    .main-content {
      display: flex;
      padding: 30px;
    }

    .form-box {
      flex: 1;
      padding: 30px;
    }

    .form-box h2 {
      margin-bottom: 20px;
      font-size: 24px;
      color: #333;
      opacity: 0;
      transform: translateY(40px);
      animation: fadeUp 0.6s ease forwards;
      animation-delay: 0.2s;
    }

    .input-box {
      margin-bottom: 20px;
      opacity: 0;
      transform: translateY(40px);
      animation: fadeUp 0.6s ease forwards;
    }

    .input-box:nth-child(1) { animation-delay: 0.4s; }
    .input-box:nth-child(2) { animation-delay: 0.6s; }

    .input-box input {
      width: 100%;
      padding: 12px 14px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 14px;
    }

    .login-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 15px 0;
      font-size: 13px;
      opacity: 0;
      transform: translateY(40px);
      animation: fadeUp 0.6s ease forwards;
      animation-delay: 0.7s;
    }

    .login-options a {
      text-decoration: none;
      color: #6c5ce7;
    }

    .btn {
      width: 100%;
      padding: 12px;
      background: #6c5ce7;
      border: none;
      border-radius: 8px;
      color: #fff;
      font-size: 15px;
      cursor: pointer;
      transition: 0.3s;
      opacity: 0;
      transform: translateY(40px);
      animation: fadeUp 0.6s ease forwards;
      animation-delay: 0.9s;
    }

    .btn:hover {
      background: #5a4ad1;
    }

    .social-login {
      margin-top: 20px;
      text-align: center;
      opacity: 0;
      transform: translateY(40px);
      animation: fadeUp 0.6s ease forwards;
      animation-delay: 1.1s;
    }

    .error-message {
      color: #e74c3c;
      font-size: 14px;
      margin-top: 10px;
      text-align: center;
      display: none;
    }

    .success-message {
      color: #27ae60;
      font-size: 14px;
      margin-top: 10px;
      text-align: center;
      display: none;
    }

    @keyframes fadeUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .illustration {
      flex: 1.3;
      background: #ffffff;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .illustration img {
      width: 60%;
      max-width: 450px;
      animation: zoomIn 1s ease forwards;
      opacity: 0;
      transform: scale(0.8);
      animation-delay: 0.5s;
    }

    @keyframes zoomIn {
      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    /* Footer */
    .footer {
      background: #ffffff;
      padding: 15px 20px;
      font-size: 13px;
      display: flex;
      justify-content: space-between;
      color: #666;
    }

    .footer a {
      margin-left: 15px;
      color: #6c5ce7;
      text-decoration: none;
      transition: 0.3s;
    }

    .footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    
    <!-- Top Bar -->
    <div class="top-bar">
      <a href="{{ route('admin.register') }}" class="register-btn">Register</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <div class="form-box">
        <h2>Welcome Admin</h2>
        
        <!-- Success Message (after registration) -->
        @if(session('success'))
        <div class="success-message" style="display: block;">
          {{ session('success') }}
        </div>
        @endif

        <!-- Error Message -->
        @if($errors->any())
        <div class="error-message" style="display: block;">
          {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
          @csrf

          <div class="input-box">
            <input type="email" name="email" placeholder="Your Email" value="{{ old('email') }}" required>
          </div>

          <div class="input-box">
            <input type="password" name="password" placeholder="Password" required>
          </div>

          <div class="login-options">
            <a href="#">Forgot password?</a>
          </div>

          <button type="submit" class="btn">Log in</button>
        </form>

        <div class="social-login">
          <!-- Social login buttons can go here -->
        </div>
      </div>

      <div class="illustration">
        <img src="{{ asset('FormDesign.png') }}" alt="Illustration">
      </div>
    </div>

    <!-- Footer -->
    <div class="footer">
      <span>© CLOT Admin 2025</span>
      <div>
        <a href="#">Terms & Conditions</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Help</a>
      </div>
    </div>
  </div>

  <script>
    // Simple client-side validation
    document.querySelector('form').addEventListener('submit', function(e) {
      const email = document.querySelector('input[name="email"]').value;
      const password = document.querySelector('input[name="password"]').value;
      
      if (!email || !password) {
        e.preventDefault();
        alert('Please fill in all fields');
      }
    });
  </script>
</body>
</html>