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
      transition: border 0.3s;
    }

    .input-box input:focus {
      border-color: #6c5ce7;
      outline: none;
    }

    .input-box input.error {
      border-color: #e74c3c;
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

    .social-login span {
      display: block;
      margin-bottom: 8px;
      color: #555;
    }

    .social-login button {
      margin: 0 5px;
      padding: 10px 16px;
      border: none;
      border-radius: 50%;
      cursor: pointer;
      background: #eee;
      transition: 0.3s;
    }

    .social-login button:hover {
      background: #ddd;
    }

    /* Error Message Styles */
    .error-message {
      background: #ffeaea;
      color: #e74c3c;
      padding: 12px 16px;
      border-radius: 8px;
      border: 1px solid #f5c6cb;
      margin-bottom: 20px;
      font-size: 14px;
      text-align: center;
      animation: shake 0.5s ease-in-out;
    }

    .success-message {
      background: #e8f5e8;
      color: #27ae60;
      padding: 12px 16px;
      border-radius: 8px;
      border: 1px solid #c3e6cb;
      margin-bottom: 20px;
      font-size: 14px;
      text-align: center;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
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
        <div class="success-message">
          {{ session('success') }}
        </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
        <div class="error-message">
          @foreach($errors->all() as $error)
            {{ $error }}
          @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}" id="loginForm">
          @csrf

          <div class="input-box">
            <input type="email" name="email" placeholder="Your Email" value="{{ old('email') }}" required 
                   class="{{ $errors->has('email') ? 'error' : '' }}">
          </div>

          <div class="input-box">
            <input type="password" name="password" placeholder="Password" required
                   class="{{ $errors->has('password') ? 'error' : '' }}">
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
    // Back Navigation Prevention Function
    function preventBackNavigation() {
      // Push a new state to prevent back navigation
      history.pushState(null, null, location.href);
      
      // Listen for popstate event (triggered by back/forward buttons)
      window.onpopstate = function(event) {
        // Push state again to prevent navigation
        history.pushState(null, null, location.href);
        
        // If user is logged in and tries to go back, prevent it
        if (sessionStorage.getItem('loggedIn') === 'true') {
          // Optionally show a message or redirect
          console.log('Back navigation prevented - user is logged in');
        }
      };
    }

    // Initialize back navigation prevention
    preventBackNavigation();

    // Set logged in status when login form is submitted
    document.getElementById('loginForm').addEventListener('submit', function() {
      sessionStorage.setItem('loggedIn', 'true');
    });

    // Clear login status when page loads (in case of logout)
    window.addEventListener('load', function() {
      // Check if we're on the login page (you might want to adjust this condition)
      if (window.location.pathname.includes('login') || 
          document.querySelector('h2').textContent.includes('Welcome Admin')) {
        sessionStorage.removeItem('loggedIn');
      }
    });

    // Auto-hide messages after 5 seconds
    setTimeout(function() {
      const errorMessage = document.querySelector('.error-message');
      const successMessage = document.querySelector('.success-message');
      
      if (errorMessage) {
        errorMessage.style.display = 'none';
      }
      if (successMessage) {
        successMessage.style.display = 'none';
      }
    }, 5000);

    // Clear error styling when user starts typing
    document.querySelectorAll('input').forEach(input => {
      input.addEventListener('input', function() {
        if (this.classList.contains('error')) {
          this.classList.remove('error');
        }
      });
    });
  </script>
</body>
</html>