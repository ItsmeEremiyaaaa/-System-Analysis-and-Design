<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Teacher Account - CLOT Admin</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background: #f0f2f5;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 0;
      overflow: hidden;
    }

    .fullscreen-container {
      display: flex;
      background: #fff;
      width: 95vw;
      height: 95vh;
      max-height: 95vh;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 20px 50px rgba(0,0,0,0.2);
      transform: scale(0.95);
      opacity: 0;
      animation: scaleIn 0.8s ease-out forwards;
    }

    @keyframes scaleIn {
      from {
        transform: scale(0.95);
        opacity: 0;
      }
      to {
        transform: scale(1);
        opacity: 1;
      }
    }

    /* Left Side - Illustration */
    .illustration-side {
      flex: 1;
      background: linear-gradient(135deg, #6c5ce7, #a29bfe);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 40px;
      color: white;
      text-align: center;
      position: relative;
      min-width: 400px;
    }

    .illustration-side img {
      width: 70%;
      max-width: 300px;
      margin-bottom: 30px;
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-15px); }
    }

    .illustration-side h2 {
      font-size: 28px;
      margin-bottom: 15px;
      font-weight: 600;
    }

    .illustration-side p {
      font-size: 16px;
      opacity: 0.9;
      line-height: 1.5;
    }

    .security-badge {
      position: absolute;
      bottom: 30px;
      left: 30px;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      opacity: 0.8;
    }

    /* Right Side - Form */
    .form-side {
      flex: 1.2;
      padding: 50px;
      display: flex;
      flex-direction: column;
      overflow-y: auto;
      min-width: 500px;
    }

    .form-side::-webkit-scrollbar {
      width: 6px;
    }

    .form-side::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 3px;
    }

    .form-side::-webkit-scrollbar-thumb {
      background: #6c5ce7;
      border-radius: 3px;
    }

    .form-header {
      text-align: center;
      margin-bottom: 40px;
    }

    .form-header h2 {
      color: #333;
      font-size: 28px;
      margin-bottom: 10px;
      font-weight: 600;
    }

    .form-header p {
      color: #666;
      font-size: 16px;
    }

    /* Compact form grid */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 25px;
      margin-bottom: 25px;
    }

    .input-group {
      display: flex;
      flex-direction: column;
      text-align: left;
    }

    .input-group.full-width {
      grid-column: 1 / -1;
    }

    .input-group label {
      font-size: 14px;
      color: #555;
      font-weight: 500;
      margin-bottom: 8px;
    }

    .input-group.required label::after {
      content: " *";
      color: #e74c3c;
    }

    .input-group input, .input-group select {
      padding: 14px 16px;
      border-radius: 10px;
      border: 1px solid #ddd;
      font-size: 15px;
      transition: all 0.3s;
      width: 100%;
      height: 50px;
    }

    .input-group input:focus, .input-group select:focus {
      border-color: #6c5ce7;
      outline: none;
      box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1);
      transform: translateY(-1px);
    }

    .input-group input:valid {
      border-color: #27ae60;
    }

    /* Password strength */
    .password-strength {
      height: 5px;
      margin-top: 8px;
      border-radius: 5px;
      transition: all 0.3s;
    }

    .strength-0 { width: 20%; background: #e74c3c; }
    .strength-1 { width: 40%; background: #e67e22; }
    .strength-2 { width: 60%; background: #f1c40f; }
    .strength-3 { width: 80%; background: #2ecc71; }
    .strength-4 { width: 100%; background: #27ae60; }

    .strength-text {
      font-size: 12px;
      margin-top: 5px;
      text-align: right;
      font-weight: 500;
    }

    /* Buttons */
    .form-actions {
      display: flex;
      gap: 20px;
      margin-top: 30px;
    }

    .btn {
      padding: 16px 30px;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s;
      font-weight: 600;
      flex: 1;
      text-decoration: none;
      text-align: center;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      height: 55px;
    }

    .btn-primary {
      background: #6c5ce7;
      color: white;
    }

    .btn-primary:hover {
      background: #5a4ad1;
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(108, 92, 231, 0.3);
    }

    .btn-primary:disabled {
      background: #a29bfe;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    .btn-secondary {
      background: #f8f9fa;
      color: #333;
      border: 2px solid #ddd;
    }

    .btn-secondary:hover {
      background: #e9ecef;
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    /* Profile Photo */
    .profile-photo-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 30px;
    }

    .profile-photo {
      width: 120px;
      height: 120px;
      background: #eee;
      border-radius: 50%;
      border: 5px solid #fff;
      cursor: pointer;
      overflow: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 14px;
      color: #888;
      font-weight: 500;
      transition: all 0.3s;
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .profile-photo:hover {
      transform: scale(1.1);
      box-shadow: 0 12px 35px rgba(0,0,0,0.2);
    }

    .profile-photo img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: none;
    }

    .profile-photo.has-image .placeholder-text {
      display: none;
    }

    .profile-photo.has-image img {
      display: block;
    }

    .profile-photo input {
      display: none;
    }

    .profile-photo-label {
      font-size: 13px;
      color: #666;
      margin-top: 10px;
      cursor: pointer;
      font-weight: 500;
    }

    /* Messages */
    .success-message {
      background: #e8f5e8;
      color: #27ae60;
      padding: 15px 20px;
      border-radius: 10px;
      border: 1px solid #c3e6cb;
      margin-bottom: 25px;
      font-size: 15px;
      text-align: center;
      font-weight: 500;
    }

    .error-message {
      background: #ffeaea;
      color: #e74c3c;
      padding: 15px 20px;
      border-radius: 10px;
      border: 1px solid #f5c6cb;
      margin-bottom: 25px;
      font-size: 15px;
      text-align: center;
      font-weight: 500;
    }

    .error {
      color: #e74c3c;
      font-size: 12px;
      margin-top: 6px;
      text-align: left;
      font-weight: 500;
    }

    .input-error {
      border-color: #e74c3c !important;
      box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1) !important;
    }

    /* Loading state */
    .spinner {
      display: inline-block;
      width: 18px;
      height: 18px;
      border: 2px solid rgba(255,255,255,.3);
      border-radius: 50%;
      border-top-color: #fff;
      animation: spin 1s ease-in-out infinite;
      margin-right: 10px;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Security features */
    .security-features {
      display: flex;
      justify-content: space-between;
      margin-top: 30px;
      padding-top: 25px;
      border-top: 2px solid #f0f2f5;
      font-size: 13px;
      color: #666;
    }

    .security-feature {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 500;
    }

    .security-feature i {
      color: #27ae60;
      font-size: 16px;
    }

    /* Email domain helper */
    .email-domain {
      color: #666;
      font-size: 12px;
      margin-top: 5px;
      font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 1200px) {
      .fullscreen-container {
        width: 98vw;
        height: 98vh;
        max-height: 98vh;
      }
      
      .form-side {
        padding: 40px;
      }
      
      .illustration-side {
        padding: 30px;
      }
    }

    @media (max-width: 768px) {
      .fullscreen-container {
        flex-direction: column;
        height: auto;
        max-height: none;
        overflow-y: auto;
      }
      
      .form-grid {
        grid-template-columns: 1fr;
      }
      
      .illustration-side {
        min-width: auto;
        padding: 30px 20px;
      }
      
      .form-side {
        min-width: auto;
        padding: 30px 20px;
      }

      .security-features {
        flex-direction: column;
        gap: 15px;
      }
    }
  </style>
</head>
<body>

<div class="fullscreen-container">
  <!-- Left Side - Illustration -->
  <div class="illustration-side">
    <img src="{{ asset('FormDesign.png') }}" alt="Teacher Illustration" onerror="this.style.display='none'">
    <h2>Create Teacher Account</h2>
    <p>Securely add new teachers to your institution<br>with full-screen optimized form</p>
    <div class="security-badge">
      <i class="fas fa-shield-alt"></i>
      <span>Secure Registration</span>
    </div>
  </div>

  <!-- Right Side - Form -->
  <div class="form-side">
    <div class="form-header">
      <h2>Teacher Information</h2>
      <p>Fill in all required teacher details</p>
    </div>

    <!-- Profile Photo -->
    <div class="profile-photo-container">
      <label class="profile-photo" id="profilePhotoLabel" for="profilePhotoInput" aria-label="Upload profile photo">
        <span class="placeholder-text">Add Photo</span>
        <img id="profileImage" src="" alt="Profile Photo">
        <input type="file" id="profilePhotoInput" name="photo" accept="image/*" form="teacher-register-form">
      </label>
      <span class="profile-photo-label">Click to upload profile photo</span>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="success-message">
      {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="error-message">
      @foreach($errors->all() as $error)
        {{ $error }}<br>
      @endforeach
    </div>
    @endif

    <form id="teacher-register-form" method="POST" action="{{ route('manage-teachers.store') }}" enctype="multipart/form-data" novalidate>
      @csrf

      <div class="form-grid">
        <div class="input-group required">
          <label for="full_name">Full Name</label>
          <input type="text" id="full_name" name="full_name" placeholder="Enter full name" value="{{ old('full_name') }}" required minlength="2" maxlength="255">
          <div class="error" id="full_name-error"></div>
        </div>

        <div class="input-group required">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="teacher@smcbi.edu.ph" value="{{ old('email') }}" required pattern=".*@smcbi\.edu\.ph$">
          <div class="email-domain">Must end with @smcbi.edu.ph</div>
          <div class="error" id="email-error"></div>
        </div>

        <div class="input-group required">
          <label for="course">Course</label>
          <input type="text" id="course" name="course" placeholder="Enter course" value="{{ old('course') }}" required>
          <div class="error" id="course-error"></div>
        </div>

        <div class="input-group required">
          <label for="department">Department</label>
          <select id="department" name="department" required>
            <option value="">Select Department</option>
            <option value="BSIT" {{ old('department') == 'BSIT' ? 'selected' : '' }}>BSIT - Information Technology</option>
            <option value="BSBA" {{ old('department') == 'BSBA' ? 'selected' : '' }}>BSBA - Business Administration</option>
            <option value="BEED" {{ old('department') == 'BEED' ? 'selected' : '' }}>BEED - Elementary Education</option>
            <option value="BSHM" {{ old('department') == 'BSHM' ? 'selected' : '' }}>BSHM - Hospitality Management</option>
            <option value="BSED" {{ old('department') == 'BSED' ? 'selected' : '' }}>BSED - Secondary Education</option>
          </select>
          <div class="error" id="department-error"></div>
        </div>

        <div class="input-group">
          <label for="cellphone">Phone Number</label>
          <input type="tel" id="cellphone" name="cellphone" placeholder="09123456789" value="{{ old('cellphone') }}" pattern="[0-9]{11}" maxlength="11" inputmode="numeric">
          <div class="error" id="cellphone-error"></div>
        </div>

        <div class="input-group required">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Create strong password" required minlength="8" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$">
          <div class="password-strength" id="password-strength"></div>
          <div class="strength-text" id="strength-text"></div>
          <div class="error" id="password-error"></div>
        </div>

        <div class="input-group required">
          <label for="password_confirmation">Confirm Password</label>
          <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
          <div class="error" id="password-confirm-error"></div>
        </div>
      </div>

      <div class="security-features">
        <div class="security-feature">
          <i class="fas fa-lock"></i>
          <span>SSL Encrypted Connection</span>
        </div>
        <div class="security-feature">
          <i class="fas fa-database"></i>
          <span>Secure Data Storage</span>
        </div>
        <div class="security-feature">
          <i class="fas fa-shield-alt"></i>
          <span>Real-time Validation</span>
        </div>
      </div>

      <div class="form-actions">
        <a href="{{ route('manage-teachers.index') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
          Cancel
        </a>
        <button type="submit" class="btn btn-primary" id="submit-button">
          <span class="spinner" id="submit-spinner" style="display: none;"></span>
          <i class="fas fa-user-plus"></i>
          <span id="submit-text">Create Teacher Account</span>
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const profilePhotoInput = document.getElementById('profilePhotoInput');
    const profilePhotoLabel = document.getElementById('profilePhotoLabel');
    const profileImage = document.getElementById('profileImage');
    const form = document.getElementById('teacher-register-form');
    const submitButton = document.getElementById('submit-button');
    const submitSpinner = document.getElementById('submit-spinner');
    const submitText = document.getElementById('submit-text');
    
    // Profile photo upload
    profilePhotoInput.addEventListener('change', function(event) {
      const file = event.target.files[0];
      
      if (file) {
        if (!file.type.match('image.*')) {
          showFieldError('profilePhotoInput', 'Please select an image file (JPEG, PNG, GIF)');
          return;
        }
        
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
      }
    });

    // Phone number validation - numbers only
    document.getElementById('cellphone').addEventListener('input', function(e) {
      this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Email domain validation
    document.getElementById('email').addEventListener('blur', function() {
      const email = this.value.trim();
      if (email && !email.endsWith('@smcbi.edu.ph')) {
        showFieldError('email', 'Email must end with @smcbi.edu.ph');
      }
    });

    // Form validation
    form.addEventListener('submit', function(event) {
      if (!validateForm()) {
        event.preventDefault();
        return false;
      }
      
      submitSpinner.style.display = 'inline-block';
      submitText.textContent = 'Creating Secure Account...';
      submitButton.disabled = true;
    });

    // Real-time validation
    document.getElementById('full_name').addEventListener('blur', validateFullName);
    document.getElementById('email').addEventListener('blur', validateEmail);
    document.getElementById('course').addEventListener('blur', validateCourse);
    document.getElementById('department').addEventListener('change', validateDepartment);
    document.getElementById('cellphone').addEventListener('blur', validateCellphone);
    document.getElementById('password').addEventListener('input', validatePassword);
    document.getElementById('password_confirmation').addEventListener('blur', validatePasswordConfirm);
  });

  function validateForm() {
    return validateFullName() && validateEmail() && validateCourse() && 
           validateDepartment() && validateCellphone() && validatePassword() && validatePasswordConfirm();
  }

  function validateFullName() {
    const fullName = document.getElementById('full_name').value.trim();
    if (fullName === '') {
      showFieldError('full_name', 'Full name is required');
      return false;
    }
    if (fullName.length < 2) {
      showFieldError('full_name', 'Name must be at least 2 characters');
      return false;
    }
    clearFieldError('full_name');
    return true;
  }

  function validateEmail() {
    const email = document.getElementById('email').value.trim();
    if (email === '') {
      showFieldError('email', 'Email is required');
      return false;
    }
    if (!email.endsWith('@smcbi.edu.ph')) {
      showFieldError('email', 'Email must end with @smcbi.edu.ph');
      return false;
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      showFieldError('email', 'Please enter a valid email address');
      return false;
    }
    clearFieldError('email');
    return true;
  }

  function validateCourse() {
    const course = document.getElementById('course').value.trim();
    if (course === '') {
      showFieldError('course', 'Course is required');
      return false;
    }
    clearFieldError('course');
    return true;
  }

  function validateDepartment() {
    const department = document.getElementById('department').value;
    const validDepartments = ['BSIT', 'BSBA', 'BEED', 'BSHM', 'BSED'];
    if (department === '') {
      showFieldError('department', 'Please select a department');
      return false;
    }
    if (!validDepartments.includes(department)) {
      showFieldError('department', 'Please select a valid department');
      return false;
    }
    clearFieldError('department');
    return true;
  }

  function validateCellphone() {
    const cellphone = document.getElementById('cellphone').value.trim();
    const cellphoneRegex = /^[0-9]{11}$/;
    if (cellphone !== '' && !cellphoneRegex.test(cellphone)) {
      showFieldError('cellphone', 'Please enter a valid 11-digit phone number');
      return false;
    }
    clearFieldError('cellphone');
    return true;
  }

  function validatePassword() {
    const password = document.getElementById('password').value;
    const strengthBar = document.getElementById('password-strength');
    const strengthText = document.getElementById('strength-text');
    
    if (password === '') {
      showFieldError('password', 'Password is required');
      strengthBar.className = 'password-strength';
      strengthText.textContent = '';
      return false;
    }
    
    if (password.length < 8) {
      showFieldError('password', 'Password must be at least 8 characters');
      return false;
    }
    
    let strength = 0;
    let feedback = '';
    
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    else feedback += 'Lowercase letters. ';
    if (/[A-Z]/.test(password)) strength++;
    else feedback += 'Uppercase letters. ';
    if (/[0-9]/.test(password)) strength++;
    else feedback += 'Numbers. ';
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    else feedback += 'Special characters. ';
    
    strengthBar.className = 'password-strength strength-' + strength;
    const strengthLabels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
    strengthText.textContent = strengthLabels[strength];
    strengthText.style.color = strength >= 3 ? '#27ae60' : (strength >= 2 ? '#f1c40f' : '#e74c3c');
    
    if (strength < 3) {
      showFieldError('password', 'Password must include uppercase, lowercase, numbers, and special characters');
      return false;
    }
    
    clearFieldError('password');
    return true;
  }

  function validatePasswordConfirm() {
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirmation').value;
    if (passwordConfirm === '') {
      showFieldError('password_confirmation', 'Please confirm password');
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
</script>

<!-- Font Awesome for icons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>

</body>
</html>