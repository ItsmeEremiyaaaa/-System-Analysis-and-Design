<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Registration</title>
  <style>
    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      background: #f0f2f5;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .register-container {
      width: 500px;
      max-width: 90%;
      background: #fff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 15px 35px rgba(0,0,0,0.15);
      text-align: center;
      padding-bottom: 40px;
      transition: all 0.3s ease;
    }

    .register-container:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 45px rgba(0,0,0,0.2);
    }

    /* Cover photo */
    .cover-photo {
      width: 100%;
      height: 140px;
      background: linear-gradient(135deg, #6c5ce7, #a29bfe);
      position: relative;
    }

    /* Profile photo */
    .profile-photo {
      width: 120px;
      height: 120px;
      background: #eee;
      border-radius: 50%;
      border: 5px solid #fff;
      position: absolute;
      bottom: -15px;
      left: 50%;
      transform: translateX(-50%);
      cursor: pointer;
      overflow: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 14px;
      color: #888;
      font-weight: 500;
      transition: 0.3s;
    }

    .profile-photo:hover {
      transform: translateX(-50%) scale(1.05);
    }

    .profile-photo img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: none; /* Initially hidden */
    }

    .profile-photo.has-image .placeholder-text {
      display: none; /* Hide text when image is present */
    }

    .profile-photo.has-image img {
      display: block; /* Show image when uploaded */
    }

    /* Registration form */
    .register-form {
      padding-top: 80px; /* Increased space for profile photo */
      display: flex;
      flex-direction: column;
      gap: 20px; /* Fixed syntax and increased gap */
      padding: 0 40px;
    }

    .register-form .input-group {
      display: flex;
      flex-direction: column;
      text-align: left;
      margin-bottom: 5px; /* Added consistent bottom margin */
    }

    .register-form label {
      font-size: 14px;
      color: #555;
      font-weight: 500;
      margin-bottom: 8px; /* Added spacing between label and input */
    }

    .register-form input {
      padding: 14px 16px; /* Added horizontal padding */
      border-radius: 10px;
      border: 1px solid #ccc;
      font-size: 13px;
      width: 100%;
      box-sizing: border-box; /* Ensure padding doesn't affect width */
      transition: border 0.3s;
    }

    .register-form input:focus {
      border-color: #6c5ce7;
      outline: none;
    }

    .register-form button {
      padding: 14px;
      border: none;
      border-radius: 10px;
      background: #6c5ce7;
      color: #fff;
      font-size: 17px;
      cursor: pointer;
      transition: 0.3s;
      font-weight: 500;
      margin-top: 10px; /* Added spacing above button */
    }

    .register-form button:hover {
      background: #5a4ad1;
    }

    .register-form button:disabled {
      background: #a29bfe;
      cursor: not-allowed;
    }

    .profile-photo input {
      display: none;
    }

    /* Password strength indicator */
    .password-strength {
      height: 5px;
      margin-top: 8px;
      border-radius: 5px;
      transition: all 0.3s;
    }

    .strength-0 {
      width: 20%;
      background: #e74c3c;
    }

    .strength-1 {
      width: 40%;
      background: #e67e22;
    }

    .strength-2 {
      width: 60%;
      background: #f1c40f;
    }

    .strength-3 {
      width: 80%;
      background: #2ecc71;
    }

    .strength-4 {
      width: 100%;
      background: #27ae60;
    }

    .strength-text {
      font-size: 12px;
      margin-top: 5px;
      text-align: right;
    }

    /* Validation error styles */
    .error {
      color: #e74c3c;
      font-size: 12px;
      margin-top: 5px;
      text-align: left;
    }

    .input-error {
      border-color: #e74c3c !important;
    }

    /* Popup Modal Styles */
    .popup-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }

    .popup-content {
      background: white;
      padding: 40px;
      border-radius: 16px;
      text-align: center;
      box-shadow: 0 20px 45px rgba(0,0,0,0.3);
      max-width: 400px;
      width: 90%;
      animation: popIn 0.5s ease-out;
    }

    @keyframes popIn {
      from {
        opacity: 0;
        transform: scale(0.8) translateY(-20px);
      }
      to {
        opacity: 1;
        transform: scale(1) translateY(0);
      }
    }

    .popup-icon {
      font-size: 60px;
      color: #00b894;
      margin-bottom: 20px;
    }

    .popup-title {
      font-size: 24px;
      color: #2d3436;
      margin-bottom: 15px;
      font-weight: 600;
    }

    .popup-message {
      color: #636e72;
      margin-bottom: 25px;
      line-height: 1.5;
    }

    .popup-button {
      padding: 12px 30px;
      background: #6c5ce7;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
      font-weight: 500;
    }

    .popup-button:hover {
      background: #5a4ad1;
      transform: translateY(-2px);
    }

    /* Success/Error Messages */
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

    .error-message {
      background: #ffeaea;
      color: #e74c3c;
      padding: 12px 16px;
      border-radius: 8px;
      border: 1px solid #f5c6cb;
      margin-bottom: 20px;
      font-size: 14px;
      text-align: center;
    }

    /* Loading state */
    .loading {
      opacity: 0.7;
      pointer-events: none;
    }

    .spinner {
      display: inline-block;
      width: 20px;
      height: 20px;
      border: 3px solid rgba(255,255,255,.3);
      border-radius: 50%;
      border-top-color: #fff;
      animation: spin 1s ease-in-out infinite;
      margin-right: 10px;
      vertical-align: middle;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Responsive adjustments */
    @media (max-width: 600px) {
      .register-container {
        width: 95%;
        margin: 20px auto;
      }

      .register-form {
        padding: 0 20px;
      }

      .profile-photo {
        width: 100px;
        height: 100px;
      }
    }
  </style>
</head>
<body>

<div class="register-container">
  <div class="cover-photo">
    <label class="profile-photo" id="profilePhotoLabel" for="profilePhotoInput" aria-label="Upload profile photo">
      <span class="placeholder-text">Add Photo</span>
      <img id="profileImage" src="" alt="Profile Photo">
      <input type="file" id="profilePhotoInput" name="profile_photo" accept="image/*" form="admin-register-form">
    </label>
  </div>

  <form id="admin-register-form" class="register-form" method="POST" action="{{ route('admin.register.submit') }}" enctype="multipart/form-data" novalidate>
    @csrf

    <!-- Success Message -->
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

    <div class="input-group">
      <label for="full_name">Full Name</label>
      <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required>
      <div class="error" id="name-error"></div>
    </div>

    <div class="input-group">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" placeholder="Enter your username" required>
      <div class="error" id="username-error"></div>
    </div>

    <div class="input-group">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" required>
      <div class="error" id="email-error"></div>
    </div>

    <div class="input-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Enter your password" required>
      <div class="password-strength" id="password-strength"></div>
      <div class="strength-text" id="strength-text"></div>
      <div class="error" id="password-error"></div>
    </div>

    <div class="input-group">
      <label for="password_confirmation">Confirm Password</label>
      <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
      <div class="error" id="password-confirm-error"></div>
    </div>

    <button type="submit" id="submit-button">
      <span class="spinner" id="submit-spinner" style="display: none;"></span>
      <span id="submit-text">Register</span>
    </button>
  </form>
</div>

<!-- Success Popup Modal -->
<div class="popup-modal" id="successPopup" role="dialog" aria-labelledby="popup-title" aria-modal="true">
  <div class="popup-content">
    <div class="popup-icon" aria-hidden="true">✓</div>
    <h2 class="popup-title" id="popup-title">Registration Successful!</h2>
    <p class="popup-message">Admin account has been created successfully. You will be redirected to the login page.</p>
    <button class="popup-button" onclick="redirectToLogin()">Continue to Login</button>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const profilePhotoInput = document.getElementById('profilePhotoInput');
    const profilePhotoLabel = document.getElementById('profilePhotoLabel');
    const profileImage = document.getElementById('profileImage');
    const successPopup = document.getElementById('successPopup');
    const form = document.getElementById('admin-register-form');
    const submitButton = document.getElementById('submit-button');
    const submitSpinner = document.getElementById('submit-spinner');
    const submitText = document.getElementById('submit-text');
    
    // Profile photo upload functionality
    profilePhotoInput.addEventListener('change', function(event) {
      const file = event.target.files[0];
      
      if (file) {
        // Validate file type
        if (!file.type.match('image.*')) {
          showFieldError('profilePhotoInput', 'Please select an image file (JPEG, PNG, etc.)');
          return;
        }
        
        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
          showFieldError('profilePhotoInput', 'Image size should be less than 2MB');
          return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
          profileImage.src = e.target.result;
          profilePhotoLabel.classList.add('has-image');
          clearFieldError('profilePhotoInput');
        };
        
        reader.readAsDataURL(file);
      } else {
        profileImage.src = '';
        profilePhotoLabel.classList.remove('has-image');
      }
    });

    // Form validation
    form.addEventListener('submit', function(event) {
      if (!validateForm()) {
        event.preventDefault();
        return false;
      }
      
      // Show loading state
      submitSpinner.style.display = 'inline-block';
      submitText.textContent = 'Processing...';
      submitButton.disabled = true;
    });

    // Real-time validation
    document.getElementById('full_name').addEventListener('blur', validateName);
    document.getElementById('username').addEventListener('blur', validateUsername);
    document.getElementById('email').addEventListener('blur', validateEmail);
    document.getElementById('password').addEventListener('input', validatePassword);
    document.getElementById('password_confirmation').addEventListener('blur', validatePasswordConfirm);

    // Check if registration was successful and show popup
    @if(session('success'))
      showSuccessPopup();
      // Auto-redirect after 5 seconds
      setTimeout(redirectToLogin, 5000);
    @endif

    // Close popup if clicked outside
    document.addEventListener('click', function(event) {
      const popup = document.getElementById('successPopup');
      if (event.target === popup) {
        redirectToLogin();
      }
    });
  });

  // Form validation functions
  function validateForm() {
    const isNameValid = validateName();
    const isUsernameValid = validateUsername();
    const isEmailValid = validateEmail();
    const isPasswordValid = validatePassword();
    const isPasswordConfirmValid = validatePasswordConfirm();
    
    return isNameValid && isUsernameValid && isEmailValid && isPasswordValid && isPasswordConfirmValid;
  }

  function validateName() {
    const nameInput = document.getElementById('full_name');
    const nameError = document.getElementById('name-error');
    const name = nameInput.value.trim();
    
    if (name === '') {
      showFieldError('full_name', 'Full name is required');
      return false;
    }
    
    if (name.length < 2) {
      showFieldError('full_name', 'Name must be at least 2 characters long');
      return false;
    }
    
    clearFieldError('full_name');
    return true;
  }

  function validateUsername() {
    const usernameInput = document.getElementById('username');
    const usernameError = document.getElementById('username-error');
    const username = usernameInput.value.trim();
    
    if (username === '') {
      showFieldError('username', 'Username is required');
      return false;
    }
    
    if (username.length < 3) {
      showFieldError('username', 'Username must be at least 3 characters long');
      return false;
    }
    
    // Check for valid username (alphanumeric and underscores only)
    const usernameRegex = /^[a-zA-Z0-9_]+$/;
    if (!usernameRegex.test(username)) {
      showFieldError('username', 'Username can only contain letters, numbers, and underscores');
      return false;
    }
    
    clearFieldError('username');
    return true;
  }

  function validateEmail() {
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('email-error');
    const email = emailInput.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email === '') {
      showFieldError('email', 'Email is required');
      return false;
    }
    
    if (!emailRegex.test(email)) {
      showFieldError('email', 'Please enter a valid email address');
      return false;
    }
    
    clearFieldError('email');
    return true;
  }

  function validatePassword() {
    const passwordInput = document.getElementById('password');
    const passwordError = document.getElementById('password-error');
    const password = passwordInput.value;
    const strengthBar = document.getElementById('password-strength');
    const strengthText = document.getElementById('strength-text');
    
    if (password === '') {
      showFieldError('password', 'Password is required');
      strengthBar.className = 'password-strength';
      strengthText.textContent = '';
      return false;
    }
    
    // Password strength calculation
    let strength = 0;
    let feedback = '';
    
    // Length check
    if (password.length >= 8) strength++;
    else feedback = 'Password should be at least 8 characters long. ';
    
    // Lowercase check
    if (/[a-z]/.test(password)) strength++;
    else feedback += 'Include lowercase letters. ';
    
    // Uppercase check
    if (/[A-Z]/.test(password)) strength++;
    else feedback += 'Include uppercase letters. ';
    
    // Number check
    if (/[0-9]/.test(password)) strength++;
    else feedback += 'Include numbers. ';
    
    // Special character check
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    else feedback += 'Include special characters. ';
    
    // Update strength indicator
    strengthBar.className = 'password-strength strength-' + strength;
    
    // Set strength text
    const strengthLabels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
    strengthText.textContent = strengthLabels[strength];
    strengthText.style.color = strength >= 3 ? '#27ae60' : (strength >= 2 ? '#f1c40f' : '#e74c3c');
    
    if (strength < 3) {
      showFieldError('password', feedback || 'Password is too weak');
      return false;
    }
    
    clearFieldError('password');
    return true;
  }

  function validatePasswordConfirm() {
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const passwordConfirmError = document.getElementById('password-confirm-error');
    const password = passwordInput.value;
    const passwordConfirm = passwordConfirmInput.value;
    
    if (passwordConfirm === '') {
      showFieldError('password_confirmation', 'Please confirm your password');
      return false;
    }
    
    if (password !== passwordConfirm) {
      showFieldError('password_confirmation', 'Passwords do not match');
      return false;
    }
    
    clearFieldError('password_confirmation');
    return true;
  }

  function showFieldError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const errorElement = document.getElementById(fieldId + '-error');
    
    field.classList.add('input-error');
    errorElement.textContent = message;
  }

  function clearFieldError(fieldId) {
    const field = document.getElementById(fieldId);
    const errorElement = document.getElementById(fieldId + '-error');
    
    field.classList.remove('input-error');
    errorElement.textContent = '';
  }

  function showSuccessPopup() {
    const popup = document.getElementById('successPopup');
    popup.style.display = 'flex';
    // Focus on the popup for accessibility
    popup.focus();
  }

  function redirectToLogin() {
    window.location.href = "{{ route('admin.login') }}";
  }
</script>

</body>
</html>