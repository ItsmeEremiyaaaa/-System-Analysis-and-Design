<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <?php
    // DYNAMIC USER RETRIEVAL - BASED ON LOGGED IN TEACHER
    session_start(); // Add session start if not already in your application
    
    // Simulate user data - replace with your actual authentication logic
    $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
    $displayName = 'Teacher';
    $avatarLetter = 'T';

    if ($user) {
        // Priority 1: Use full_name from database
        if (!empty($user->full_name)) {
            $displayName = $user->full_name;
            
            // Format to "Last Name, First Name" if space exists
            $nameParts = explode(' ', trim($displayName));
            if (count($nameParts) >= 2) {
                $lastName = array_pop($nameParts);
                $firstName = implode(' ', $nameParts);
                $displayName = $lastName . ', ' . $firstName;
            }
        }
        // Priority 2: Use name field
        elseif (!empty($user->name)) {
            $displayName = $user->name;
            
            // Format to "Last Name, First Name" if space exists
            $nameParts = explode(' ', trim($displayName));
            if (count($nameParts) >= 2) {
                $lastName = array_pop($nameParts);
                $firstName = implode(' ', $nameParts);
                $displayName = $lastName . ', ' . $firstName;
            }
        }
        // Priority 3: Extract from email as fallback
        else {
            $email = $user->email;
            $username = explode('@', $email)[0];
            
            // Convert email username to readable format
            $nameParts = preg_split('/[\._]/', $username);
            if (count($nameParts) >= 2) {
                $lastName = ucfirst($nameParts[count($nameParts) - 1]);
                $firstName = ucfirst($nameParts[0]);
                $displayName = $lastName . ', ' . $firstName;
            } else {
                $displayName = ucfirst($username);
            }
        }
    }

    // Get first letter for avatar
    $avatarLetter = strtoupper(substr($displayName, 0, 1));
    ?>
    
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #4895ef;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --dark-text: #212529;
            --border-color: #e9ecef;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
            --sidebar-width: 260px;
            --sidebar-width-collapsed: 70px;
            --transition-speed: 0.3s;
            --success-color: #4ade80;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            color: var(--dark-text);
            overflow-x: hidden;
            min-height: 100vh;
            font-size: 0.875rem;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            position: relative;
        }
        
        /* Enhanced Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            color: var(--gray-700);
            padding: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            transition: all var(--transition-speed) ease;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            overflow-y: auto;
            border-right: 1px solid var(--border-color);
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-width-collapsed);
        }
        
        .sidebar.collapsed .sidebar-header h3 span,
        .sidebar.collapsed .sidebar-menu a span,
        .sidebar.collapsed .logout-btn span {
            display: none;
        }
        
        .sidebar.collapsed .sidebar-menu a {
            justify-content: center;
            padding: 12px;
        }
        
        .sidebar.collapsed .sidebar-menu i {
            margin-right: 0;
            font-size: 1.1rem;
        }
        
        .sidebar.collapsed .logout-btn {
            justify-content: center;
            padding: 12px;
        }
        
        .sidebar-header {
            padding: 20px 15px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
            background-color: white;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .sidebar-header h3 {
            margin: 0;
            font-weight: 700;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--primary-color);
        }
        
        .toggle-sidebar {
            background: none;
            border: none;
            color: var(--gray-600);
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            padding: 5px;
            border-radius: 4px;
        }
        
        .toggle-sidebar:hover {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 15px 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--gray-600);
            text-decoration: none;
            transition: all 0.2s;
            position: relative;
            z-index: 1;
            font-weight: 500;
            border-radius: 0;
            margin: 0 10px;
            border-radius: 8px;
        }
        
        .sidebar-menu a:hover {
            color: var(--primary-color);
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .sidebar-menu a.active {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.2);
        }
        
        .sidebar-menu i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }
        
        /* Enhanced Logout Button */
        .logout-btn {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--gray-600);
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            background: none;
            width: calc(100% - 20px);
            text-align: left;
            margin: 10px;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .logout-btn:hover {
            background-color: rgba(239, 68, 68, 0.05);
            color: var(--danger-color);
        }
        
        .logout-btn i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }
        
        /* Enhanced Main Content Styles */
        .main-content {
            flex: 1;
            padding: 20px;
            margin-left: var(--sidebar-width);
            transition: all var(--transition-speed) ease;
            background-color: #f5f7fb;
        }
        
        .main-content.expanded {
            margin-left: var(--sidebar-width-collapsed);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding: 15px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            animation: slideInDown 0.6s ease;
        }
        
        .header h1 {
            color: var(--gray-800);
            font-weight: 700;
            margin: 0;
            font-size: 1.5rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.2);
        }
        
        /* Enhanced Card Styles */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 20px;
            transition: all 0.3s ease;
            overflow: hidden;
            background: white;
            animation: fadeInUp 0.6s ease;
        }
        
        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 16px 20px;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header h4 {
            margin: 0;
            color: var(--gray-800);
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        .card-header .btn {
            font-size: 0.8rem;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .table-responsive {
            border-radius: 0 0 12px 12px;
        }
        
        .table th {
            background-color: var(--gray-100);
            border-top: none;
            font-weight: 600;
            color: var(--gray-700);
            padding: 12px;
            font-size: 0.8rem;
        }
        
        .table td {
            padding: 12px;
            vertical-align: middle;
            font-size: 0.8rem;
        }
        
        .btn-action {
            padding: 4px 8px;
            font-size: 0.75rem;
            border-radius: 4px;
            margin-right: 4px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
            color: white;
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        
        .btn-info {
            background-color: var(--info-color);
            border-color: var(--info-color);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        /* Enhanced Stats Cards */
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border-left: 4px solid var(--primary-color);
        }
        
        .stat-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.4rem;
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }
        
        .stat-info h3 {
            margin: 0;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--gray-800);
        }
        
        .stat-info p {
            margin: 5px 0 0;
            color: var(--gray-600);
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        /* Page Content */
        .page-content {
            display: none;
            animation: fadeIn 0.5s ease;
        }
        
        .page-content.active {
            display: block;
        }
        
        /* Enhanced Modal Styles */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            animation: modalSlideIn 0.4s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .modal-header {
            background: white;
            color: var(--gray-800);
            border-bottom: 1px solid var(--border-color);
            border-radius: 12px 12px 0 0;
            padding: 20px 25px;
            position: relative;
        }
        
        .modal-header .btn-close {
            opacity: 0.7;
            transition: all 0.3s ease;
        }
        
        .modal-header .btn-close:hover {
            opacity: 1;
        }
        
        .modal-body {
            padding: 25px;
            background: white;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }
        
        .welcome-banner::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }
        
        .welcome-banner h2 {
            font-weight: 700;
            margin-bottom: 8px;
            font-size: 1.4rem;
        }
        
        .welcome-banner p {
            opacity: 0.9;
            margin-bottom: 0;
            font-size: 0.9rem;
        }
        
        /* Quick Actions Grid */
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .quick-action-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            box-shadow: var(--card-shadow);
            cursor: pointer;
        }

        .quick-action-card:hover {
            transform: translateY(-3px);
            border-color: var(--primary-color);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .quick-action-card i {
            font-size: 2rem;
            margin-bottom: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .quick-action-card h5 {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--gray-800);
            font-size: 0.9rem;
        }

        .quick-action-card p {
            color: var(--gray-600);
            font-size: 0.8rem;
            margin-bottom: 0;
        }
        
        /* Enhanced Calendar Styles */
        .calendar-container {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            background-color: white;
            border-bottom: 1px solid var(--border-color);
        }
        
        .calendar-nav {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .calendar-nav button {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 6px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .calendar-nav button:hover {
            background: var(--primary-light);
        }
        
        .calendar-title {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--gray-800);
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background-color: var(--border-color);
        }
        
        .calendar-day-header {
            background-color: var(--gray-100);
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.8rem;
        }
        
        .calendar-day {
            background-color: white;
            min-height: 120px;
            padding: 8px;
            position: relative;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .calendar-day:hover {
            background-color: #f8fafc;
        }
        
        .calendar-day.other-month {
            background-color: var(--gray-100);
            color: var(--gray-500);
        }
        
        .calendar-day.today {
            background-color: rgba(67, 97, 238, 0.08);
            border: 2px solid var(--primary-color);
            box-shadow: 0 2px 5px rgba(67, 97, 238, 0.2);
        }
        
        .day-number {
            font-weight: 600;
            margin-bottom: 6px;
            font-size: 0.9rem;
        }
        
        .event-item {
            background-color: var(--primary-color);
            color: white;
            padding: 4px 6px;
            border-radius: 4px;
            font-size: 0.7rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            margin-bottom: 2px;
        }
        
        .event-item:hover {
            opacity: 0.9;
        }
        
        /* Status Badges */
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .reservation-status-pending {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }
        
        .reservation-status-approved {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }
        
        .reservation-status-rejected {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }
        
        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .toast {
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
            animation: toastSlideIn 0.3s ease;
        }

        @keyframes toastSlideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { 
                opacity: 0;
                transform: translateY(15px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInUp {
            from { 
                opacity: 0;
                transform: translateY(20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                width: var(--sidebar-width-collapsed);
                overflow: visible;
            }
            
            .sidebar-header h3 span {
                display: none;
            }
            
            .sidebar-menu a span {
                display: none;
            }
            
            .sidebar-menu a {
                justify-content: center;
                padding: 12px;
            }
            
            .sidebar-menu i {
                margin-right: 0;
                font-size: 1.1rem;
            }
            
            .logout-btn span {
                display: none;
            }
            
            .logout-btn {
                justify-content: center;
                padding: 12px;
            }
            
            .main-content {
                margin-left: var(--sidebar-width-collapsed);
            }
        }
        
        @media (max-width: 768px) {
            .stats-cards {
                grid-template-columns: 1fr;
            }
            
            .quick-actions-grid {
                grid-template-columns: 1fr;
            }
            
            .calendar-day {
                min-height: 70px;
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
        <!-- Enhanced Teacher Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-chalkboard-teacher"></i> <span>Teacher Portal</span></h3>
                <button class="toggle-sidebar" id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <ul class="sidebar-menu">
                <li><a href="#" class="nav-link active" data-page="dashboard"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="#" class="nav-link" data-page="calendar"><i class="fas fa-calendar-alt"></i> <span>Calendar</span></a></li>
                <li><a href="#" class="nav-link" data-page="reservation"><i class="fas fa-desktop"></i> <span>Reservations</span></a></li>
                <li>
                    <form id="logout-form" action="logout.php" method="POST" style="display: none;">
                        <!-- Add your logout logic here -->
                    </form>
                    <button class="logout-btn" id="logoutBtn">
                        <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                    </button>
                </li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Dashboard Page -->
            <div id="dashboard" class="page-content active">
                <!-- Welcome Banner -->
                <div class="welcome-banner">
                    <h2>Welcome back, <?php echo htmlspecialchars($displayName); ?>!</h2>
                    <p>Here's your teaching overview and quick access to important features.</p>
                </div>
                
                <!-- Stats Cards -->
                <div class="stats-cards">
                    <div class="stat-card stagger-item">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="upcomingClasses">0</h3>
                            <p>Upcoming Classes</p>
                        </div>
                    </div>
                    <div class="stat-card stagger-item">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="pendingReservations">0</h3>
                            <p>Pending Reservations</p>
                        </div>
                    </div>
                    <div class="stat-card stagger-item">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="approvedReservations">0</h3>
                            <p>Approved Reservations</p>
                        </div>
                    </div>
                    <div class="stat-card stagger-item">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="totalStudents">0</h3>
                            <p>Total Students</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="quick-actions-grid">
                            <div class="quick-action-card" onclick="TeacherDashboard.navigateToPage('calendar')">
                                <i class="fas fa-calendar-plus"></i>
                                <h5>Schedule Class</h5>
                                <p>Add new class to calendar</p>
                            </div>
                            <div class="quick-action-card" onclick="TeacherDashboard.navigateToPage('reservation')">
                                <i class="fas fa-desktop"></i>
                                <h5>Request Lab</h5>
                                <p>Reserve computer laboratory</p>
                            </div>
                            <div class="quick-action-card" onclick="TeacherDashboard.viewTodaySchedule()">
                                <i class="fas fa-list"></i>
                                <h5>Today's Schedule</h5>
                                <p>View today's classes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Classes -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-book"></i> Upcoming Classes</h4>
                    </div>
                    <div class="card-body">
                        <div id="upcomingClassesList">
                            <!-- Classes will be populated here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar Page -->
            <div id="calendar" class="page-content">
                <div class="header">
                    <h1>Teaching Calendar</h1>
                    <div class="user-info">
                        <div class="user-avatar">
                            <span><?php echo htmlspecialchars($avatarLetter); ?></span>
                        </div>
                        <span><?php echo htmlspecialchars($displayName); ?></span>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-calendar-alt"></i> My Teaching Schedule</h4>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addClassModal">
                            <i class="fas fa-plus"></i> Add Class
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="calendar-container">
                            <div class="calendar-header">
                                <div class="calendar-nav">
                                    <button id="prevMonth"><i class="fas fa-chevron-left"></i></button>
                                    <button id="todayBtn" class="btn btn-outline-primary">Today</button>
                                    <button id="nextMonth"><i class="fas fa-chevron-right"></i></button>
                                </div>
                                <div class="calendar-title" id="calendarTitle">Month Year</div>
                            </div>
                            <div class="calendar-grid" id="calendarGrid">
                                <!-- Calendar will be populated here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reservation Page -->
            <div id="reservation" class="page-content">
                <div class="header">
                    <h1>Laboratory Reservations</h1>
                    <div class="user-info">
                        <div class="user-avatar">
                            <span><?php echo htmlspecialchars($avatarLetter); ?></span>
                        </div>
                        <span><?php echo htmlspecialchars($displayName); ?></span>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-desktop"></i> My Reservation Requests</h4>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addReservationModal">
                            <i class="fas fa-plus"></i> New Request
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Laboratory</th>
                                        <th>Date & Time</th>
                                        <th>Purpose</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="reservationTableBody">
                                    <!-- Reservations will be populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Class Modal -->
    <div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClassModalLabel">Add New Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addClassForm">
                        <div class="mb-3">
                            <label class="form-label">Class Title</label>
                            <input type="text" class="form-control" placeholder="Enter class title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Start Time</label>
                                    <input type="time" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">End Time</label>
                                    <input type="time" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" rows="3" placeholder="Class description..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveClassBtn">Save Class</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Reservation Modal -->
    <div class="modal fade" id="addReservationModal" tabindex="-1" aria-labelledby="addReservationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addReservationModalLabel">New Laboratory Reservation Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addReservationForm">
                        <div class="mb-3">
                            <label class="form-label">Reservation Title</label>
                            <input type="text" class="form-control" placeholder="Enter reservation title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Computer Laboratory</label>
                            <select class="form-select" required>
                                <option value="">Select Laboratory</option>
                                <option value="Computer Laboratory 1">Computer Laboratory 1</option>
                                <option value="Computer Laboratory 2">Computer Laboratory 2</option>
                                <option value="Computer Laboratory Netlab">Computer Laboratory Netlab</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Purpose</label>
                            <textarea class="form-control" rows="3" placeholder="Describe the purpose..." required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Expected Participants</label>
                                    <input type="number" class="form-control" placeholder="Number of students" min="1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Start Time</label>
                                    <input type="time" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">End Time</label>
                                    <input type="time" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveReservationBtn">Submit Request</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enhanced Teacher Dashboard Framework
        const TeacherDashboard = {
            // Database Simulation using localStorage
            Database: {
                getReservations: function() {
                    return JSON.parse(localStorage.getItem('teacher_reservations') || '[]');
                },
                getClasses: function() {
                    return JSON.parse(localStorage.getItem('teacher_classes') || '[]');
                },
                init: function() {
                    if (!localStorage.getItem('teacher_reservations')) {
                        localStorage.setItem('teacher_reservations', JSON.stringify([]));
                    }
                    if (!localStorage.getItem('teacher_classes')) {
                        localStorage.setItem('teacher_classes', JSON.stringify([]));
                    }
                    console.log('Teacher Database initialized');
                }
            },

            // Toast Notification Functions
            showToast: function(message, type = 'success') {
                const toastElement = type === 'success' ? 
                    document.getElementById('successToast') : 
                    document.getElementById('errorToast');
                
                const toastMessage = type === 'success' ?
                    document.getElementById('successToastMessage') :
                    document.getElementById('errorToastMessage');
                
                toastMessage.textContent = message;
                const toast = new bootstrap.Toast(toastElement);
                toast.show();
            },

            // Navigation
            navigateToPage: function(pageId) {
                document.querySelectorAll('.page-content').forEach(page => {
                    page.classList.remove('active');
                });
                
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.classList.remove('active');
                });
                
                document.getElementById(pageId).classList.add('active');
                document.querySelector(`.nav-link[data-page="${pageId}"]`).classList.add('active');
            },

            // Initialize dashboard
            init: function() {
                this.Database.init();
                this.initializeTeacherDashboard();
                this.initializeCalendar();
                this.initializeEventListeners();
                this.loadTeacherData();
            },

            // Load teacher data
            loadTeacherData: function() {
                const reservations = this.Database.getReservations();
                const classes = this.Database.getClasses();
                
                // Update stats
                const upcomingClasses = classes.filter(cls => new Date(cls.date) >= new Date()).length;
                const pendingReservations = reservations.filter(res => res.status === 'pending').length;
                const approvedReservations = reservations.filter(res => res.status === 'approved').length;
                
                document.getElementById('upcomingClasses').textContent = upcomingClasses;
                document.getElementById('pendingReservations').textContent = pendingReservations;
                document.getElementById('approvedReservations').textContent = approvedReservations;
                document.getElementById('totalStudents').textContent = 45; // Static for demo
                
                this.renderReservationsTable();
                this.renderUpcomingClasses();
            },

            // Render reservations table
            renderReservationsTable: function() {
                const tableBody = document.getElementById('reservationTableBody');
                const reservations = this.Database.getReservations();
                
                tableBody.innerHTML = '';

                reservations.forEach(reservation => {
                    const row = document.createElement('tr');
                    const statusClass = `reservation-status-${reservation.status}`;
                    const statusText = reservation.status.charAt(0).toUpperCase() + reservation.status.slice(1);
                    
                    row.innerHTML = `
                        <td>${reservation.title}</td>
                        <td>${reservation.lab}</td>
                        <td>
                            <div>${new Date(reservation.date).toLocaleDateString()}</div>
                            <small class="text-muted">${reservation.startTime} - ${reservation.endTime}</small>
                        </td>
                        <td>${reservation.purpose}</td>
                        <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-info btn-action" onclick="TeacherDashboard.editReservation(${reservation.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-action" onclick="TeacherDashboard.cancelReservation(${reservation.id})">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            },

            // Render upcoming classes
            renderUpcomingClasses: function() {
                const container = document.getElementById('upcomingClassesList');
                const classes = this.Database.getClasses();
                const upcomingClasses = classes.filter(cls => new Date(cls.date) >= new Date())
                    .sort((a, b) => new Date(a.date) - new Date(b.date))
                    .slice(0, 5);
                
                container.innerHTML = '';

                if (upcomingClasses.length === 0) {
                    container.innerHTML = '<div class="text-center text-muted py-3">No upcoming classes scheduled</div>';
                    return;
                }

                upcomingClasses.forEach(cls => {
                    const classDate = new Date(cls.date);
                    const today = new Date();
                    const tomorrow = new Date(today);
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    
                    let dateDisplay = classDate.toLocaleDateString();
                    if (classDate.toDateString() === today.toDateString()) {
                        dateDisplay = 'Today';
                    } else if (classDate.toDateString() === tomorrow.toDateString()) {
                        dateDisplay = 'Tomorrow';
                    }
                    
                    const classItem = document.createElement('div');
                    classItem.className = 'd-flex justify-content-between align-items-center p-3 border-bottom';
                    classItem.innerHTML = `
                        <div>
                            <h6 class="mb-1">${cls.title}</h6>
                            <small class="text-muted"><i class="fas fa-calendar"></i> ${dateDisplay} • <i class="fas fa-clock"></i> ${cls.startTime} - ${cls.endTime}</small>
                            ${cls.description ? `<small class="text-muted d-block mt-1">${cls.description}</small>` : ''}
                        </div>
                        <div>
                            <span class="badge bg-light text-dark">${cls.lab || 'Classroom'}</span>
                        </div>
                    `;
                    container.appendChild(classItem);
                });
            },

            // Calendar functionality
            currentDate: new Date(),
            currentMonth: new Date().getMonth(),
            currentYear: new Date().getFullYear(),

            initializeCalendar: function() {
                this.renderCalendar();
                
                // Calendar navigation
                document.getElementById('prevMonth').addEventListener('click', () => {
                    this.currentMonth--;
                    if (this.currentMonth < 0) {
                        this.currentMonth = 11;
                        this.currentYear--;
                    }
                    this.renderCalendar();
                });

                document.getElementById('nextMonth').addEventListener('click', () => {
                    this.currentMonth++;
                    if (this.currentMonth > 11) {
                        this.currentMonth = 0;
                        this.currentYear++;
                    }
                    this.renderCalendar();
                });

                document.getElementById('todayBtn').addEventListener('click', () => {
                    this.currentDate = new Date();
                    this.currentMonth = this.currentDate.getMonth();
                    this.currentYear = this.currentDate.getFullYear();
                    this.renderCalendar();
                });

                // Save class
                document.getElementById('saveClassBtn').addEventListener('click', () => {
                    this.saveClass();
                });

                // Save reservation
                document.getElementById('saveReservationBtn').addEventListener('click', () => {
                    this.saveReservation();
                });
            },

            renderCalendar: function() {
                const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                document.getElementById('calendarTitle').textContent = `${monthNames[this.currentMonth]} ${this.currentYear}`;
                
                const calendarGrid = document.getElementById('calendarGrid');
                calendarGrid.innerHTML = '';
                
                // Add day headers
                const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                dayNames.forEach(day => {
                    const dayHeader = document.createElement('div');
                    dayHeader.className = 'calendar-day-header';
                    dayHeader.textContent = day;
                    calendarGrid.appendChild(dayHeader);
                });
                
                // Get calendar data
                const daysInMonth = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
                const firstDay = new Date(this.currentYear, this.currentMonth, 1).getDay();
                const classes = this.Database.getClasses();
                const today = new Date();
                
                // Add empty days
                for (let i = 0; i < firstDay; i++) {
                    const emptyDay = document.createElement('div');
                    emptyDay.className = 'calendar-day other-month';
                    calendarGrid.appendChild(emptyDay);
                }
                
                // Add days
                for (let i = 1; i <= daysInMonth; i++) {
                    const day = document.createElement('div');
                    const date = new Date(this.currentYear, this.currentMonth, i);
                    
                    // Check if today
                    if (date.getDate() === today.getDate() && 
                        date.getMonth() === today.getMonth() && 
                        date.getFullYear() === today.getFullYear()) {
                        day.className = 'calendar-day today';
                    } else {
                        day.className = 'calendar-day';
                    }
                    
                    day.innerHTML = `<div class="day-number">${i}</div>`;
                    
                    // Add classes for this day
                    const dayClasses = classes.filter(cls => {
                        const classDate = new Date(cls.date);
                        return classDate.getDate() === i && 
                               classDate.getMonth() === this.currentMonth && 
                               classDate.getFullYear() === this.currentYear;
                    });
                    
                    if (dayClasses.length > 0) {
                        const eventIndicator = document.createElement('div');
                        eventIndicator.className = 'event-indicator';
                        
                        dayClasses.forEach(cls => {
                            const eventItem = document.createElement('div');
                            eventItem.className = 'event-item';
                            eventItem.textContent = cls.title;
                            eventItem.title = `${cls.title} at ${cls.startTime}`;
                            eventIndicator.appendChild(eventItem);
                        });
                        
                        day.appendChild(eventIndicator);
                    }
                    
                    // Add click event
                    day.addEventListener('click', () => {
                        this.openAddClassModal(date);
                    });
                    
                    calendarGrid.appendChild(day);
                }
            },

            openAddClassModal: function(date) {
                document.getElementById('addClassForm').reset();
                const formattedDate = `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getDate().toString().padStart(2, '0')}`;
                document.querySelector('#addClassForm input[type="date"]').value = formattedDate;
                new bootstrap.Modal(document.getElementById('addClassModal')).show();
            },

            // Class management
            saveClass: function() {
                const form = document.getElementById('addClassForm');
                const formData = new FormData(form);
                
                const title = form.querySelector('input[type="text"]').value;
                const date = form.querySelector('input[type="date"]').value;
                const startTime = form.querySelectorAll('input[type="time"]')[0].value;
                const endTime = form.querySelectorAll('input[type="time"]')[1].value;
                const description = form.querySelector('textarea').value;
                
                if (!title || !date || !startTime || !endTime) {
                    this.showToast('Please fill all required fields!', 'error');
                    return;
                }
                
                if (startTime >= endTime) {
                    this.showToast('End time must be after start time!', 'error');
                    return;
                }
                
                const classes = this.Database.getClasses();
                const newClass = {
                    id: Date.now(),
                    title: title,
                    date: date,
                    startTime: startTime,
                    endTime: endTime,
                    description: description,
                    createdAt: new Date().toISOString()
                };
                
                classes.push(newClass);
                localStorage.setItem('teacher_classes', JSON.stringify(classes));
                
                this.showToast('Class scheduled successfully!', 'success');
                bootstrap.Modal.getInstance(document.getElementById('addClassModal')).hide();
                this.loadTeacherData();
                this.renderCalendar();
            },

            // Reservation management
            saveReservation: function() {
                const form = document.getElementById('addReservationForm');
                const title = form.querySelector('input[type="text"]').value;
                const lab = form.querySelector('select').value;
                const purpose = form.querySelector('textarea').value;
                const date = form.querySelector('input[type="date"]').value;
                const participants = form.querySelector('input[type="number"]').value;
                const startTime = form.querySelectorAll('input[type="time"]')[0].value;
                const endTime = form.querySelectorAll('input[type="time"]')[1].value;
                
                if (!title || !lab || !purpose || !date || !startTime || !endTime) {
                    this.showToast('Please fill all required fields!', 'error');
                    return;
                }
                
                if (startTime >= endTime) {
                    this.showToast('End time must be after start time!', 'error');
                    return;
                }
                
                const reservations = this.Database.getReservations();
                const newReservation = {
                    id: Date.now(),
                    title: title,
                    lab: lab,
                    purpose: purpose,
                    date: date,
                    participants: participants,
                    startTime: startTime,
                    endTime: endTime,
                    status: 'pending',
                    requestedAt: new Date().toISOString()
                };
                
                reservations.push(newReservation);
                localStorage.setItem('teacher_reservations', JSON.stringify(reservations));
                
                this.showToast('Reservation request submitted for approval!', 'success');
                bootstrap.Modal.getInstance(document.getElementById('addReservationModal')).hide();
                this.loadTeacherData();
            },

            editReservation: function(id) {
                this.showToast('Edit functionality coming soon!', 'info');
            },

            cancelReservation: function(id) {
                if (confirm('Are you sure you want to cancel this reservation?')) {
                    const reservations = this.Database.getReservations();
                    const updatedReservations = reservations.filter(res => res.id !== id);
                    localStorage.setItem('teacher_reservations', JSON.stringify(updatedReservations));
                    
                    this.showToast('Reservation cancelled!', 'success');
                    this.loadTeacherData();
                }
            },

            viewTodaySchedule: function() {
                this.navigateToPage('calendar');
                this.showToast('Showing today\'s schedule', 'info');
            },

            // Initialize event listeners
            initializeEventListeners: function() {
                // Navigation
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        const pageId = link.getAttribute('data-page');
                        this.navigateToPage(pageId);
                    });
                });
                
                // Sidebar toggle
                document.getElementById('toggleSidebar').addEventListener('click', this.toggleSidebar);
                
                // Logout
                document.getElementById('logoutBtn').addEventListener('click', (e) => {
                    e.preventDefault();
                    if (confirm('Are you sure you want to logout?')) {
                        // Implement your logout logic here
                        window.location.href = 'logout.php';
                    }
                });
            },

            // Sidebar toggle
            toggleSidebar: function() {
                const sidebar = document.querySelector('.sidebar');
                const mainContent = document.querySelector('.main-content');
                const toggleButton = document.getElementById('toggleSidebar');
                
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                
                // Save sidebar state
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            },

            // Initialize sidebar state
            initializeSidebarState: function() {
                const sidebar = document.querySelector('.sidebar');
                const mainContent = document.querySelector('.main-content');
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                
                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('expanded');
                }
            },

            // Initialize teacher dashboard
            initializeTeacherDashboard: function() {
                this.initializeSidebarState();
                this.loadTeacherData();
            }
        };

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            TeacherDashboard.init();
        });
    </script>
</body>
</html>