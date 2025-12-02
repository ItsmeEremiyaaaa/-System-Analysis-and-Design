<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #1abc9c;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
            --border-color: #e1e5eb;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: var(--dark-text);
            margin: 0;
            padding: 0;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: var(--secondary-color);
            color: white;
            padding: 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background-color: rgba(0,0,0,0.2);
        }
        
        .sidebar-header h3 {
            margin: 0;
            font-weight: 600;
            font-size: 1.4rem;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            padding-left: 25px;
        }
        
        .sidebar-menu a.active {
            background-color: var(--primary-color);
            color: white;
            border-left: 4px solid var(--accent-color);
        }
        
        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Logout Button */
        .logout-btn {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .logout-btn:hover {
            background-color: rgba(231, 76, 60, 0.2);
            color: white;
            padding-left: 25px;
        }
        
        .logout-btn i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .header h1 {
            color: var(--secondary-color);
            font-weight: 600;
            margin: 0;
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            margin-right: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-color);
            color: white;
            font-weight: bold;
            font-size: 18px;
            overflow: hidden;
        }
        
        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid var(--border-color);
            padding: 15px 20px;
            border-radius: 10px 10px 0 0 !important;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header .btn {
            font-size: 0.85rem;
            padding: 6px 12px;
        }
        
        .table-responsive {
            border-radius: 0 0 10px 10px;
        }
        
        .table th {
            background-color: var(--light-bg);
            border-top: none;
            font-weight: 600;
            color: var(--dark-text);
        }
        
        .btn-action {
            padding: 5px 10px;
            font-size: 0.85rem;
            border-radius: 4px;
            margin-right: 5px;
        }
        
        .btn-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }
        
        .btn-warning {
            background-color: #f39c12;
            border-color: #f39c12;
            color: white;
        }
        
        .btn-success {
            background-color: #27ae60;
            border-color: #27ae60;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.5rem;
        }
        
        .stat-info h3 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
        }
        
        .stat-info p {
            margin: 0;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        /* Page Content */
        .page-content {
            display: none;
        }
        
        .page-content.active {
            display: block;
        }
        
        /* Modal Styles */
        .modal-content {
            border-radius: 10px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-bottom: none;
            border-radius: 10px 10px 0 0;
        }
        
        .modal-header .btn-close {
            filter: invert(1);
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 0.5rem;
        }
        
        /* Empty State Styles */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #dee2e6;
        }
        
        .empty-state h4 {
            margin-bottom: 10px;
            color: #495057;
        }
        
        .empty-state p {
            margin-bottom: 20px;
            font-size: 1.1rem;
        }
        
        .empty-state .btn {
            margin-top: 10px;
        }
        
        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1055;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            .sidebar-menu {
                display: flex;
                overflow-x: auto;
            }
            
            .sidebar-menu li {
                flex: 1;
                min-width: 120px;
                border-bottom: none;
                border-right: 1px solid rgba(255,255,255,0.1);
            }
            
            .sidebar-menu a {
                justify-content: center;
                flex-direction: column;
                padding: 10px 5px;
                text-align: center;
                font-size: 0.8rem;
            }
            
            .sidebar-menu i {
                margin-right: 0;
                margin-bottom: 5px;
                font-size: 1.2rem;
            }
            
            .logout-btn {
                display: none;
            }
            
            .stats-cards {
                grid-template-columns: 1fr;
            }
            
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .card-header .btn {
                align-self: flex-end;
            }
        }
    </style>
</head>
<body>
    <!-- Toast Notifications -->
    <div class="toast-container">
        <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="successToastMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="errorToastMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-graduation-cap"></i> Admin Portal</h3>
            </div>
            <ul class="sidebar-menu">
                <li><a href="#" class="nav-link active" data-page="dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="#" class="nav-link" data-page="manage-staff"><i class="fas fa-users"></i> Manage Staff</a></li>
                <li><a href="#" class="nav-link" data-page="manage-students"><i class="fas fa-user-graduate"></i> Manage Students</a></li>
                <li><a href="#" class="nav-link" data-page="notifications"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="#" class="nav-link" data-page="upcoming-events"><i class="fas fa-calendar-check"></i> Upcoming Events</a></li>
                <li><a href="#" class="nav-link" data-page="courses"><i class="fas fa-book"></i> Courses</a></li>
                <li><a href="#" class="nav-link" data-page="reports"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li><a href="#" class="nav-link" data-page="settings"><i class="fas fa-cogs"></i> Settings</a></li>
                <!-- Logout Button -->
                <li>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <button class="logout-btn" id="logoutBtn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Dashboard Page -->
            <div id="dashboard" class="page-content active">
                <div class="header">
                    <h1>Admin Dashboard</h1>
                    <div class="user-info">
                        <div class="user-avatar">
                            <span>A</span>
                        </div>
                        <span>Administrator</span>
                    </div>
                </div>
                
                <!-- Stats Cards -->
                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: rgba(52, 152, 219, 0.2); color: #3498db;">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="totalStaff">0</h3>
                            <p>Total Staff</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: rgba(46, 204, 113, 0.2); color: #2ecc71;">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="totalStudents">0</h3>
                            <p>Total Students</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: rgba(155, 89, 182, 0.2); color: #9b59b6;">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="totalCourses">0</h3>
                            <p>Active Courses</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: rgba(241, 196, 15, 0.2); color: #f1c40f;">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="successRate">96%</h3>
                            <p>Success Rate</p>
                        </div>
                    </div>
                </div>
                
                <!-- Welcome Message with User's Name -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-user-circle"></i> Welcome, Administrator!</h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">Welcome back to your dashboard. Here's an overview of your system's performance and recent activities.</p>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-history"></i> Recent Activity</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush" id="recentActivityList">
                            <!-- Recent activities will be populated here -->
                        </ul>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <button class="btn btn-primary w-100 py-3" onclick="navigateToPage('manage-staff')">
                                    <i class="fas fa-users fa-2x mb-2"></i><br>
                                    Manage Staff
                                </button>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button class="btn btn-success w-100 py-3" onclick="navigateToPage('manage-students')">
                                    <i class="fas fa-user-graduate fa-2x mb-2"></i><br>
                                    Manage Students
                                </button>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button class="btn btn-info w-100 py-3" onclick="navigateToPage('notifications')">
                                    <i class="fas fa-bell fa-2x mb-2"></i><br>
                                    Notifications
                                </button>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button class="btn btn-warning w-100 py-3" onclick="navigateToPage('upcoming-events')">
                                    <i class="fas fa-calendar-check fa-2x mb-2"></i><br>
                                    Events
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Manage Staff Page -->
            <div id="manage-staff" class="page-content">
                <div class="header">
                    <h1>Manage Staff</h1>
                    <div class="user-info">
                        <div class="user-avatar">
                            <span>A</span>
                        </div>
                        <span>Administrator</span>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-users"></i> Staff Members</h4>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                            <i class="fas fa-plus"></i> Add Staff
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Department</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="staffTableBody">
                                <!-- Staff will be added here dynamically -->
                            </tbody>
                        </table>
                    </div>
                    <div id="noStaffMessage" class="empty-state">
                        <i class="fas fa-users"></i>
                        <h4>No Staff Members</h4>
                        <p>Get started by adding your first staff member to the system.</p>
                        <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                            <i class="fas fa-plus"></i> Add First Staff
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Manage Students Page -->
            <div id="manage-students" class="page-content">
                <div class="header">
                    <h1>Manage Students</h1>
                    <div class="user-info">
                        <div class="user-avatar">
                            <span>A</span>
                        </div>
                        <span>Administrator</span>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-user-graduate"></i> Student Records</h4>
                        <button class="btn btn-success" id="addStudentBtn">
                            <i class="fas fa-plus"></i> Add Student
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Course</th>
                                    <th>Section</th>
                                    <th>Year Level</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="studentTableBody">
                                <!-- Students will be added here dynamically -->
                            </tbody>
                        </table>
                    </div>
                    <div id="noStudentMessage" class="empty-state">
                        <i class="fas fa-user-graduate"></i>
                        <h4>No Students</h4>
                        <p>Start managing students by adding the first student record.</p>
                        <button class="btn btn-primary mt-2" id="addFirstStudentBtn">
                            <i class="fas fa-plus"></i> Add First Student
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Other pages... -->
        </div>
    </div>

    <!-- Add Student Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentModalLabel"><i class="fas fa-user-graduate"></i> Add New Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addStudentForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="studentId" class="form-label">Student ID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="studentId" placeholder="Enter student ID" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="studentName" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="studentName" placeholder="Enter full name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="studentEmail" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="studentEmail" placeholder="Enter email address" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="studentCourse" class="form-label">Course <span class="text-danger">*</span></label>
                                    <select class="form-select" id="studentCourse" required>
                                        <option value="" selected disabled>Select Course</option>
                                        <option value="BSIT">Bachelor of Science in Information Technology (BSIT)</option>
                                        <option value="BSBA">Bachelor of Science in Business Administration (BSBA)</option>
                                        <option value="BSHM">Bachelor of Science in Hospitality Management (BSHM)</option>
                                        <option value="BEED">Bachelor of Elementary Education (BEED)</option>
                                        <option value="BSED">Bachelor of Secondary Education (BSED)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="studentSection" class="form-label">Section <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="studentSection" placeholder="Enter section" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="studentYearLevel" class="form-label">Year Level <span class="text-danger">*</span></label>
                                    <select class="form-select" id="studentYearLevel" required>
                                        <option value="" selected disabled>Select Year Level</option>
                                        <option value="1st Year">1st Year</option>
                                        <option value="2nd Year">2nd Year</option>
                                        <option value="3rd Year">3rd Year</option>
                                        <option value="4th Year">4th Year</option>
                                        <option value="5th Year">5th Year</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="studentPhone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="studentPhone" placeholder="Enter phone number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="studentAddress" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="studentAddress" placeholder="Enter address">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveStudentBtn">Save Student</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Staff Modal -->
    <div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStaffModalLabel"><i class="fas fa-user-plus"></i> Add New Staff Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addStaffForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="staffName" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="staffName" placeholder="Enter full name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="staffEmail" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="staffEmail" placeholder="Enter email address" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="staffDepartment" class="form-label">Department <span class="text-danger">*</span></label>
                                    <select class="form-select" id="staffDepartment" required>
                                        <option value="" selected disabled>Select Department</option>
                                        <option value="BSIT">Bachelor of Science in Information Technology (BSIT)</option>
                                        <option value="BSBA">Bachelor of Science in Business Administration (BSBA)</option>
                                        <option value="BSHM">Bachelor of Science in Hospitality Management (BSHM)</option>
                                        <option value="BEED">Bachelor of Elementary Education (BEED)</option>
                                        <option value="BSED">Bachelor of Secondary Education (BSED)</option>
                                        <option value="Admin">Administration and Personnel</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="staffPhone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="staffPhone" placeholder="Enter phone number">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="staffAddress" class="form-label">Address</label>
                            <textarea class="form-control" id="staffAddress" rows="3" placeholder="Enter full address"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveStaffBtn">Save Staff Member</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Database Simulation using localStorage
        const Database = {
            // Staff operations
            getStaff: function() {
                return JSON.parse(localStorage.getItem('staff') || '[]');
            },

            getStudents: function() {
                return JSON.parse(localStorage.getItem('students') || '[]');
            },

            // Initialize with sample data if empty
            init: function() {
                if (!localStorage.getItem('staff') || JSON.parse(localStorage.getItem('staff')).length === 0) {
                    const initialStaff = [
                        {
                            id: 1,
                            full_name: "John Doe",
                            email: "john.doe@school.edu",
                            department: "BSIT",
                            phone: "123-456-7890",
                            address: "123 Main St",
                            created_at: new Date().toISOString()
                        }
                    ];
                    localStorage.setItem('staff', JSON.stringify(initialStaff));
                }
                
                if (!localStorage.getItem('students')) {
                    localStorage.setItem('students', JSON.stringify([]));
                }
                
                console.log('Database initialized');
            }
        };

        // Toast Notification Functions
        function showToast(message, type = 'success') {
            const toastElement = type === 'success' ? 
                document.getElementById('successToast') : 
                document.getElementById('errorToast');
            
            const toastMessage = type === 'success' ?
                document.getElementById('successToastMessage') :
                document.getElementById('errorToastMessage');
            
            toastMessage.textContent = message;
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
        }

        // Test function to check all routes
        function testAllRoutes() {
            console.log('=== TESTING ROUTES ===');
            
            // Test API route
            fetch('/admin/admin/api/manage-staff')
                .then(response => {
                    console.log('API Route - Status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('API Route - Data:', data);
                })
                .catch(error => {
                    console.error('API Route - Error:', error);
                });
            
            // Test if we can reach the store route
            fetch('{{ route("manage-staff.store") }}', {
                method: 'GET', // Just testing if route exists
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            })
            .then(response => {
                console.log('Store Route - Status:', response.status);
            })
            .catch(error => {
                console.error('Store Route - Error:', error);
            });
        }

        // Navigation functionality
        function navigateToPage(pageId) {
            document.querySelectorAll('.page-content').forEach(page => {
                page.classList.remove('active');
            });
            document.getElementById(pageId).classList.add('active');
            
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            document.querySelector(`.nav-link[data-page="${pageId}"]`).classList.add('active');
            
            // Load data when navigating to specific pages
            if (pageId === 'manage-staff') {
                loadStaffDataFromServer();
            } else if (pageId === 'manage-students') {
                loadStudentDataFromServer();
            } else if (pageId === 'dashboard') {
                loadDashboardStats();
                loadRecentActivities();
            }
        }
        
        // Add event listeners to navigation links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const pageId = this.getAttribute('data-page');
                navigateToPage(pageId);
            });
        });

        // Load dashboard statistics - UPDATED FOR STUDENTS
        function loadDashboardStats() {
            // Try to load from Laravel API first, then fallback to localStorage
            fetch('/admin/admin/api/manage-staff')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(staffData => {
                    if (staffData.success) {
                        // Use API data for staff
                        const staffCount = staffData.data.length;
                        
                        // Now load student data
                        fetch('/admin/admin/api/manage-students')
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(studentData => {
                                if (studentData.success) {
                                    const studentCount = studentData.data.length;
                                    const courseCount = new Set(studentData.data.map(s => s.course)).size;
                                    
                                    // Update dashboard stats
                                    document.getElementById('totalStaff').textContent = staffCount;
                                    document.getElementById('totalStudents').textContent = studentCount;
                                    document.getElementById('totalCourses').textContent = courseCount;
                                    
                                    console.log('Dashboard stats updated from API:', {
                                        staff: staffCount,
                                        students: studentCount,
                                        courses: courseCount
                                    });
                                }
                            })
                            .catch(studentError => {
                                console.error('Error loading student data from API:', studentError);
                                // Fallback to localStorage for students
                                const students = Database.getStudents();
                                const studentCount = students.length;
                                const courseCount = new Set(students.map(s => s.course)).size;
                                
                                document.getElementById('totalStaff').textContent = staffCount;
                                document.getElementById('totalStudents').textContent = studentCount;
                                document.getElementById('totalCourses').textContent = courseCount;
                            });
                    }
                })
                .catch(error => {
                    console.error('Error loading staff data from API:', error);
                    console.log('Falling back to localStorage data...');
                    
                    // Fallback to localStorage for both
                    const staff = Database.getStaff();
                    const students = Database.getStudents();
                    const courseCount = new Set(students.map(s => s.course)).size;
                    
                    // Update dashboard stats
                    document.getElementById('totalStaff').textContent = staff.length;
                    document.getElementById('totalStudents').textContent = students.length;
                    document.getElementById('totalCourses').textContent = courseCount;
                });
        }

        // Update Dashboard Stats
        function updateDashboardStats() {
            const staffCount = Database.getStaff().length;
            const studentCount = Database.getStudents().length;
            const courseCount = new Set(Database.getStudents().map(s => s.course)).size;
            
            // Update stats cards
            document.getElementById('totalStaff').textContent = staffCount;
            document.getElementById('totalStudents').textContent = studentCount;
            document.getElementById('totalCourses').textContent = courseCount;
            
            console.log('Stats updated - Staff:', staffCount, 'Students:', studentCount, 'Courses:', courseCount);
        }

        // Load recent activities
        function loadRecentActivities() {
            const activities = JSON.parse(localStorage.getItem('recentActivities') || '[]');
            const activityList = document.getElementById('recentActivityList');
        
            activityList.innerHTML = '';
        
            if (activities.length === 0) {
                activityList.innerHTML = '<li class="list-group-item text-center text-muted">No recent activities</li>';
                return;
            }
        
            activities.slice(0, 5).forEach(activity => {
                const listItem = document.createElement('li');
                listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                listItem.innerHTML = `
                    <div>
                        <h6 class="mb-1">${activity.title}</h6>
                        <p class="mb-0 text-muted">${activity.description}</p>
                    </div>
                    <span class="text-muted">${new Date(activity.timestamp).toLocaleString()}</span>
                `;
                activityList.appendChild(listItem);
            });
        }

        // Add recent activity
        function addRecentActivity(title, description) {
            const activities = JSON.parse(localStorage.getItem('recentActivities') || '[]');
            const newActivity = {
                id: Date.now(),
                title: title,
                description: description,
                timestamp: new Date().toISOString(),
                type: 'staff'
            };
            
            activities.unshift(newActivity);
            // Keep only last 10 activities
            if (activities.length > 10) {
                activities.splice(10);
            }
            
            localStorage.setItem('recentActivities', JSON.stringify(activities));
            loadRecentActivities();
        }

        // Staff Management Functions - DEBUG VERSION
        function addNewStaff() {
            const name = document.getElementById('staffName').value;
            const email = document.getElementById('staffEmail').value;
            const department = document.getElementById('staffDepartment').value;
            const phone = document.getElementById('staffPhone').value;
            const address = document.getElementById('staffAddress').value;
            
            // Validation
            if (!name || !email || !department) {
                showToast('Please fill all required fields!', 'error');
                return;
            }

            console.log('=== DEBUG START ===');
            console.log('Form data:', { name, email, department, phone, address });

            // Show loading state
            const saveBtn = document.getElementById('saveStaffBtn');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            saveBtn.disabled = true;

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            console.log('CSRF Token:', csrfToken);

            // Create form data
            const formData = new FormData();
            formData.append('full_name', name);
            formData.append('email', email);
            formData.append('department', department);
            formData.append('phone', phone);
            formData.append('address', address);
            formData.append('_token', csrfToken);

            console.log('Sending to:', '{{ route("manage-staff.store") }}');

            // Send to Laravel backend
            fetch('{{ route("manage-staff.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response OK:', response.ok);
                
                if (!response.ok) {
                    return response.text().then(text => {
                        console.log('Error response text:', text);
                        throw new Error(`HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Success response:', data);
                
                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addStaffModal'));
                    modal.hide();
                    
                    // Reset form
                    document.getElementById('addStaffForm').reset();
                    
                    // Show success message
                    showToast(data.message || 'Staff member added successfully!', 'success');
                    
                    // Add to recent activity
                    addRecentActivity('New Staff Added', `Added ${name} to the staff directory`);
                    
                    // Reload staff data and update dashboard
                    loadStaffDataFromServer();
                    loadDashboardStats();
                } else {
                    throw new Error(data.message || 'Failed to add staff member');
                }
            })
            .catch(error => {
                console.error('Full error details:', error);
                let errorMessage = 'Failed to add staff member';
                
                if (error.message) {
                    errorMessage = error.message;
                }
                
                showToast(errorMessage, 'error');
                
                // Fallback to localStorage
                console.log('Falling back to localStorage...');
                const staff = JSON.parse(localStorage.getItem('staff') || '[]');
                const newStaff = {
                    id: Date.now(),
                    full_name: name,
                    email: email,
                    department: department,
                    phone: phone,
                    address: address,
                    created_at: new Date().toISOString()
                };
                staff.push(newStaff);
                localStorage.setItem('staff', JSON.stringify(staff));
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addStaffModal'));
                modal.hide();
                
                // Reset form
                document.getElementById('addStaffForm').reset();
                
                // Add to recent activity
                addRecentActivity('New Staff Added', `Added ${name} to the staff directory`);
                
                // Reload data
                loadStaffDataFromServer();
                loadDashboardStats();
                
                showToast('Staff member added successfully! (Saved locally)', 'success');
            })
            .finally(() => {
                // Restore button state
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
                console.log('=== DEBUG END ===');
            });
        }

        // Load staff data from Laravel backend with fallback to localStorage - FIXED URL
        function loadStaffDataFromServer() {
            fetch('/admin/admin/api/manage-staff')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const staff = data.data;
                    const tableBody = document.getElementById('staffTableBody');
                    const emptyState = document.getElementById('noStaffMessage');
                    
                    tableBody.innerHTML = '';
                    
                    if (staff.length === 0) {
                        emptyState.style.display = 'block';
                        return;
                    }
                    
                    emptyState.style.display = 'none';
                    
                    staff.forEach((staffMember, index) => {
                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${staffMember.full_name}</td>
                            <td>${staffMember.email}</td>
                            <td>${staffMember.department}</td>
                            <td>${staffMember.phone || 'N/A'}</td>
                            <td>${staffMember.address || 'N/A'}</td>
                            <td>
                                <button class="btn btn-danger btn-action delete-staff" data-id="${staffMember.id}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        `;
                        tableBody.appendChild(newRow);
                    });
                    
                    // Update staff count on dashboard
                    document.getElementById('totalStaff').textContent = staff.length;
                }
            })
            .catch(error => {
                console.error('Error loading staff data from API:', error);
                console.log('Falling back to localStorage data...');
                
                // Fallback to localStorage
                const staff = JSON.parse(localStorage.getItem('staff') || '[]');
                const tableBody = document.getElementById('staffTableBody');
                const emptyState = document.getElementById('noStaffMessage');
                
                tableBody.innerHTML = '';
                
                if (staff.length === 0) {
                    emptyState.style.display = 'block';
                    return;
                }
                
                emptyState.style.display = 'none';
                
                staff.forEach((staffMember, index) => {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${staffMember.full_name}</td>
                        <td>${staffMember.email}</td>
                        <td>${staffMember.department}</td>
                        <td>${staffMember.phone || 'N/A'}</td>
                        <td>${staffMember.address || 'N/A'}</td>
                        <td>
                            <button class="btn btn-danger btn-action delete-staff" data-id="${staffMember.id}">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(newRow);
                });
                
                // Update staff count on dashboard
                document.getElementById('totalStaff').textContent = staff.length;
            });
        }

        // Delete staff member from Laravel backend with fallback to localStorage
        function deleteStaff(id) {
            if (confirm('Are you sure you want to delete this staff member?')) {
                fetch(`/admin/manage-staff/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Staff member deleted successfully!', 'success');
                        loadStaffDataFromServer();
                        loadDashboardStats();
                        addRecentActivity('Staff Removed', `Removed staff member with ID: ${id}`);
                    } else {
                        throw new Error(data.message || 'Failed to delete staff member');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Fallback: Delete from localStorage
                    const staff = JSON.parse(localStorage.getItem('staff') || '[]');
                    const updatedStaff = staff.filter(staffMember => staffMember.id !== id);
                    localStorage.setItem('staff', JSON.stringify(updatedStaff));
                    
                    showToast('Staff member deleted successfully! (Removed locally)', 'success');
                    loadStaffDataFromServer();
                    loadDashboardStats();
                    addRecentActivity('Staff Removed', `Removed staff member with ID: ${id}`);
                });
            }
        }

        // Initialize the application
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize database
            Database.init();
            
            // Test routes
            testAllRoutes();
            
            // Initialize localStorage with sample data if empty
            if (!localStorage.getItem('recentActivities')) {
                const initialActivities = [
                    {
                        id: 1,
                        title: "System Ready",
                        description: "Admin dashboard initialized successfully",
                        timestamp: new Date().toISOString(),
                        type: "system"
                    }
                ];
                localStorage.setItem('recentActivities', JSON.stringify(initialActivities));
            }
            
            // Set dashboard as active by default
            navigateToPage('dashboard');
            
            // Set up logout button
            document.getElementById('logoutBtn').addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to logout?')) {
                    document.getElementById('logout-form').submit();
                }
            });
            
            // Initialize management system
            initializeManagementSystem();
            
            // Load initial data
            loadDashboardStats();
            loadRecentActivities();
            updateDashboardStats();
            loadStaffDataFromServer();
            loadStudentDataFromServer();
        });

        function initializeManagementSystem() {
            // Staff Management
            document.getElementById('saveStaffBtn').addEventListener('click', function(e) {
                e.preventDefault();
                addNewStaff();
            });
            
            // Student Management
            document.getElementById('saveStudentBtn').addEventListener('click', function(e) {
                e.preventDefault();
                addNewStudent();
            });
            
            // Student modal buttons
            document.getElementById('addStudentBtn').addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('addStudentModal'));
                modal.show();
            });
            
            document.getElementById('addFirstStudentBtn').addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('addStudentModal'));
                modal.show();
            });
            
            // Delete functionality
            document.addEventListener('click', function(e) {
                if (e.target.closest('.delete-staff')) {
                    const id = parseInt(e.target.closest('.delete-staff').getAttribute('data-id'));
                    deleteStaff(id);
                }
                
                if (e.target.closest('.delete-student')) {
                    const id = parseInt(e.target.closest('.delete-student').getAttribute('data-id'));
                    deleteStudent(id);
                }
            });
            
            // Prevent form submission on enter key
            document.getElementById('addStudentForm').addEventListener('submit', function(e) {
                e.preventDefault();
            });
        }

        // Student Management Functions
        function addNewStudent() {
            const studentId = document.getElementById('studentId').value;
            const name = document.getElementById('studentName').value;
            const email = document.getElementById('studentEmail').value;
            const course = document.getElementById('studentCourse').value;
            const section = document.getElementById('studentSection').value;
            const yearLevel = document.getElementById('studentYearLevel').value;
            const phone = document.getElementById('studentPhone').value;
            const address = document.getElementById('studentAddress').value;
            
            // Validation
            if (!studentId || !name || !email || !course || !section || !yearLevel) {
                showToast('Please fill all required fields!', 'error');
                return;
            }

            console.log('=== STUDENT DEBUG START ===');
            console.log('Form data:', { studentId, name, email, course, section, yearLevel, phone, address });

            // Show loading state
            const saveBtn = document.getElementById('saveStudentBtn');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            saveBtn.disabled = true;

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Create form data
            const formData = new FormData();
            formData.append('student_id', studentId);
            formData.append('full_name', name);
            formData.append('email', email);
            formData.append('course', course);
            formData.append('section', section);
            formData.append('year_level', yearLevel);
            formData.append('phone', phone);
            formData.append('address', address);
            formData.append('_token', csrfToken);

            console.log('Sending to:', '{{ route("manage-students.store") }}');

            // Send to Laravel backend
            fetch('{{ route("manage-students.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response OK:', response.ok);
                
                if (!response.ok) {
                    return response.text().then(text => {
                        console.log('Error response text:', text);
                        throw new Error(`HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Success response:', data);
                
                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addStudentModal'));
                    modal.hide();
                    
                    // Reset form
                    document.getElementById('addStudentForm').reset();
                    
                    // Show success message
                    showToast(data.message || 'Student added successfully!', 'success');
                    
                    // Add to recent activity
                    addRecentActivity('New Student Added', `Added ${name} (${studentId}) to student records`);
                    
                    // Reload student data and update dashboard
                    loadStudentDataFromServer();
                    loadDashboardStats();
                } else {
                    throw new Error(data.message || 'Failed to add student');
                }
            })
            .catch(error => {
                console.error('Full error details:', error);
                let errorMessage = 'Failed to add student';
                
                if (error.message) {
                    errorMessage = error.message;
                }
                
                showToast(errorMessage, 'error');
                
                // Fallback to localStorage
                console.log('Falling back to localStorage...');
                const students = JSON.parse(localStorage.getItem('students') || '[]');
                const newStudent = {
                    id: Date.now(),
                    student_id: studentId,
                    full_name: name,
                    email: email,
                    course: course,
                    section: section,
                    year_level: yearLevel,
                    phone: phone,
                    address: address,
                    created_at: new Date().toISOString()
                };
                students.push(newStudent);
                localStorage.setItem('students', JSON.stringify(students));
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addStudentModal'));
                modal.hide();
                
                // Reset form
                document.getElementById('addStudentForm').reset();
                
                // Add to recent activity
                addRecentActivity('New Student Added', `Added ${name} (${studentId}) to student records`);
                
                // Reload data
                loadStudentDataFromServer();
                loadDashboardStats();
                
                showToast('Student added successfully! (Saved locally)', 'success');
            })
            .finally(() => {
                // Restore button state
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
                console.log('=== STUDENT DEBUG END ===');
            });
        }

        // Load student data from Laravel backend with fallback to localStorage
        function loadStudentDataFromServer() {
            fetch('/admin/admin/api/manage-students')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const students = data.data;
                    const tableBody = document.getElementById('studentTableBody');
                    const emptyState = document.getElementById('noStudentMessage');
                    
                    tableBody.innerHTML = '';
                    
                    if (students.length === 0) {
                        emptyState.style.display = 'block';
                        return;
                    }
                    
                    emptyState.style.display = 'none';
                    
                    students.forEach((student, index) => {
                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${student.student_id}</td>
                            <td>${student.full_name}</td>
                            <td>${student.email}</td>
                            <td>${student.course}</td>
                            <td>${student.section}</td>
                            <td>${student.year_level}</td>
                            <td>
                                <button class="btn btn-danger btn-action delete-student" data-id="${student.id}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        `;
                        tableBody.appendChild(newRow);
                    });
                    
                    // Update student count on dashboard
                    document.getElementById('totalStudents').textContent = students.length;
                }
            })
            .catch(error => {
                console.error('Error loading student data from API:', error);
                console.log('Falling back to localStorage data...');
                
                // Fallback to localStorage
                const students = JSON.parse(localStorage.getItem('students') || '[]');
                const tableBody = document.getElementById('studentTableBody');
                const emptyState = document.getElementById('noStudentMessage');
                
                tableBody.innerHTML = '';
                
                if (students.length === 0) {
                    emptyState.style.display = 'block';
                    return;
                }
                
                emptyState.style.display = 'none';
                
                students.forEach((student, index) => {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${student.student_id}</td>
                        <td>${student.full_name}</td>
                        <td>${student.email}</td>
                        <td>${student.course}</td>
                        <td>${student.section}</td>
                        <td>${student.year_level}</td>
                        <td>
                            <button class="btn btn-danger btn-action delete-student" data-id="${student.id}">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(newRow);
                });
                
                // Update student count on dashboard
                document.getElementById('totalStudents').textContent = students.length;
            });
        }

        // Delete student member from Laravel backend with fallback to localStorage
        function deleteStudent(id) {
            if (confirm('Are you sure you want to delete this student?')) {
                fetch(`/admin/manage-students/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Student deleted successfully!', 'success');
                        loadStudentDataFromServer();
                        loadDashboardStats();
                        addRecentActivity('Student Removed', `Removed student with ID: ${id}`);
                    } else {
                        throw new Error(data.message || 'Failed to delete student');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Fallback: Delete from localStorage
                    const students = JSON.parse(localStorage.getItem('students') || '[]');
                    const updatedStudents = students.filter(student => student.id !== id);
                    localStorage.setItem('students', JSON.stringify(updatedStudents));
                    
                    showToast('Student deleted successfully! (Removed locally)', 'success');
                    loadStudentDataFromServer();
                    loadDashboardStats();
                    addRecentActivity('Student Removed', `Removed student with ID: ${id}`);
                });
            }
        }
    </script>
</body>
</html>