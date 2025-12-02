    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            :root {
                --primary-color: #4361ee;
                --primary-light: #4895ef;
                --secondary-color: #3f3 7c9;
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
            
            .user-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 50%;
            }
            
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
            
            .charts-container {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
                margin-bottom: 25px;
            }
            
            .chart-card {
                background: white;
                border-radius: 12px;
                padding: 20px;
                box-shadow: var(--card-shadow);
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }
            
            .chart-card:hover {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            }
            
            .chart-header {
                display: flex;
                align-items: center;
                margin-bottom: 15px;
            }
            
            .chart-icon {
                width: 40px;
                height: 40px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 12px;
                font-size: 1.2rem;
                background: rgba(67, 97, 238, 0.1);
                color: var(--primary-color);
            }
            
            .chart-title {
                font-weight: 700;
                color: var(--gray-800);
                margin: 0;
                font-size: 1rem;
            }
            
            .chart-container {
                position: relative;
                height: 200px;
                width: 100%;
            }
            
            .page-content {
                display: none;
                animation: fadeIn 0.5s ease;
            }
            
            .page-content.active {
                display: block;
            }
            
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
            
            .empty-state {
                text-align: center;
                padding: 40px 20px;
                color: var(--gray-600);
            }
            
            .empty-state i {
                font-size: 3.5rem;
                margin-bottom: 15px;
                color: var(--gray-400);
            }
            
            .empty-state h4 {
                margin-bottom: 10px;
                color: var(--gray-700);
                font-weight: 700;
            }
            
            .empty-state p {
                margin-bottom: 20px;
                font-size: 0.9rem;
            }
            
            .empty-state .btn {
                margin-top: 10px;
                border-radius: 8px;
                padding: 10px 20px;
                font-weight: 500;
                transition: all 0.3s ease;
            }
            
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
            
            .event-indicator {
                display: flex;
                flex-direction: column;
                gap: 2px;
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
            }
            
            .event-item:hover {
                opacity: 0.9;
            }
            
            .event-item.personal {
                background-color: #8b5cf6;
            }
            
            .event-item.academic {
                background-color: var(--success-color);
            }
            
            .event-item.holiday {
                background-color: var(--danger-color);
            }
            
            .event-item.meeting {
                background-color: var(--warning-color);
            }
            
            .event-item.class {
                background-color: #3b82f6;
            }
            
            .event-item.exam {
                background-color: #dc2626;
            }
            
            .event-item.workshop {
                background-color: #7c3aed;
            }
            
            .event-item.completed {
                background-color: var(--gray-500);
                text-decoration: line-through;
            }
            
            .event-list {
                margin-top: 15px;
            }
            
            .event-list-item {
                display: flex;
                align-items: center;
                padding: 15px 20px;
                border-bottom: 1px solid var(--border-color);
                transition: all 0.3s ease;
                cursor: pointer;
                background: white;
                margin-bottom: 6px;
                border-radius: 8px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            }
            
            .event-list-item:hover {
                background-color: #f8fafc;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }
            
            .event-list-item:last-child {
                border-bottom: none;
            }
            
            .event-date {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                min-width: 60px;
                margin-right: 15px;
                background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
                border-radius: 8px;
                padding: 10px;
                color: white;
            }
            
            .event-month {
                font-size: 0.7rem;
                font-weight: 600;
            }
            
            .event-day {
                font-size: 1.3rem;
                font-weight: 700;
                line-height: 1;
            }
            
            .event-details {
                flex: 1;
            }
            
            .event-title {
                font-weight: 600;
                margin-bottom: 4px;
                color: var(--gray-800);
                font-size: 0.9rem;
            }
            
            .event-time {
                font-size: 0.8rem;
                color: var(--gray-600);
            }
            
            .event-actions {
                display: flex;
                gap: 6px;
            }
            
            .is-invalid {
                border-color: #ef4444 !important;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23ef4444'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6 .4.4.4-.4'/%3e%3cpath d='M6 7v1'/%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right calc(0.375em + 0.1875rem) center;
                background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            }

            .is-invalid:focus {
                border-color: #ef4444;
                box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
            }
            
            .participants-container {
                max-height: 180px;
                overflow-y: auto;
                border: 1px solid var(--border-color);
                border-radius: 8px;
                padding: 12px;
                background-color: white;
            }
            
            .participants-list {
                max-height: 180px;
                overflow-y: auto;
            }
            
            .participant-item {
                display: flex;
                align-items: center;
                padding: 10px 12px;
                margin-bottom: 6px;
                background: white;
                border-radius: 6px;
                border: 1px solid var(--border-color);
                transition: all 0.2s ease;
                box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            }
            
            .participant-item:hover {
                background-color: #f1f5f9;
                border-color: var(--primary-color);
            }

            .form-check {
                padding: 8px 12px;
                margin-bottom: 6px;
                border-radius: 6px;
                transition: all 0.2s ease;
                border: 1px solid transparent;
            }

            .form-check:hover {
                background-color: #f8fafc;
                border-color: var(--border-color);
            }

            .form-check-input:checked {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }
            
            .modal-landscape .modal-dialog {
                max-width: 800px;
            }

            .modal-landscape .modal-body {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 25px;
                align-items: start;
            }

            .modal-landscape .event-details-section {
                border-right: 1px solid var(--border-color);
                padding-right: 25px;
            }

            .modal-landscape .participants-section {
                padding-left: 25px;
            }
            
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
            
            .search-filter-container {
                display: flex;
                gap: 12px;
                margin-bottom: 15px;
                flex-wrap: wrap;
            }
            
            .search-box {
                flex: 1;
                min-width: 200px;
                position: relative;
            }
            
            .search-box i {
                position: absolute;
                left: 12px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--gray-500);
            }
            
            .search-box input {
                padding-left: 40px;
            }
            
            .filter-dropdown {
                min-width: 150px;
            }
            
            .loading-spinner {
                display: inline-block;
                width: 18px;
                height: 18px;
                border: 2px solid rgba(255,255,255,.3);
                border-radius: 50%;
                border-top-color: #fff;
                animation: spin 1s ease-in-out infinite;
            }
            
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
            
            .status-badge {
                padding: 4px 8px;
                border-radius: 12px;
                font-size: 0.7rem;
                font-weight: 600;
            }
            
            .status-active {
                background-color: rgba(16, 185, 129, 0.1);
                color: var(--success-color);
            }
            
            .status-inactive {
                background-color: rgba(239, 68, 68, 0.1);
                color: var(--danger-color);
            }
            
            .status-pending {
                background-color: rgba(245, 158, 11, 0.1);
                color: var(--warning-color);
            }
            
            .status-completed {
                background-color: rgba(59, 130, 246, 0.1);
                color: #3b82f6;
            }
            
            .progress-container {
                margin-top: 8px;
            }
            
            .progress-label {
                display: flex;
                justify-content: space-between;
                margin-bottom: 4px;
                font-size: 0.75rem;
            }
            
            .progress {
                height: 6px;
                border-radius: 8px;
            }
            
            .table-hover tbody tr {
                transition: all 0.2s ease;
            }
            
            .table-hover tbody tr:hover {
                background-color: rgba(67, 97, 238, 0.03);
            }
            
            .card-actions {
                display: flex;
                gap: 8px;
            }
            
            .card-action-btn {
                width: 32px;
                height: 32px;
                border-radius: 6px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(67, 97, 238, 0.1);
                color: var(--primary-color);
                border: none;
                transition: all 0.3s ease;
            }
            
            .card-action-btn:hover {
                background: var(--primary-color);
                color: white;
            }
            
            .notification-badge {
                position: absolute;
                top: -4px;
                right: -4px;
                background: var(--danger-color);
                color: white;
                border-radius: 50%;
                width: 18px;
                height: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.65rem;
                font-weight: 700;
            }
            
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
            
            .action-buttons {
                display: flex;
                gap: 6px;
                flex-wrap: nowrap;
            }
            
            .participants-vertical {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }
            
            .participants-vertical .participants-section {
                padding-left: 0;
                border-left: none;
                border-top: 1px solid var(--border-color);
                padding-top: 15px;
            }
            
            .time-visualization {
                background-color: #f8fafc;
                border-radius: 8px;
                padding: 15px;
                margin-top: 20px;
            }
            
            .time-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
            }
            
            .time-title {
                font-weight: 600;
                color: var(--gray-800);
                font-size: 1rem;
            }
            
            .time-slots {
                display: flex;
                flex-direction: column;
                gap: 2px;
            }
            
            .time-slot {
                display: flex;
                align-items: center;
                height: 30px;
                position: relative;
            }
            
            .time-label {
                width: 60px;
                font-size: 0.75rem;
                color: var(--gray-600);
                text-align: right;
                padding-right: 8px;
            }
            
            .time-line {
                flex: 1;
                height: 1px;
                background-color: var(--gray-300);
                position: relative;
            }
            
            .time-marker {
                position: absolute;
                top: -4px;
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background-color: var(--primary-color);
                z-index: 2;
            }
            
            .time-block {
                position: absolute;
                height: 24px;
                border-radius: 4px;
                background-color: var(--primary-color);
                opacity: 0.7;
                z-index: 1;
                display: flex;
                align-items: center;
                padding: 0 8px;
                color: white;
                font-size: 0.7rem;
                font-weight: 500;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;
            }
            
            .time-block.personal {
                background-color: #8b5cf6;
            }
            
            .time-block.academic {
                background-color: var(--success-color);
            }
            
            .time-block.holiday {
                background-color: var(--danger-color);
            }
            
            .time-block.meeting {
                background-color: var(--warning-color);
            }
            
            .time-block.class {
                background-color: #3b82f6;
            }
            
            .time-block.exam {
                background-color: #dc2626;
            }
            
            .time-block.workshop {
                background-color: #7c3aed;
            }
            
            .event-details-modal .modal-dialog {
                max-width: 900px;
            }
            
            .event-details-modal .modal-body {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 25px;
                align-items: start;
            }
            
            .event-details-section {
                border-right: 1px solid var(--border-color);
                padding-right: 25px;
            }
            
            .time-visualization-section {
                padding-left: 25px;
            }
            
            .conflict-warning {
                background-color: rgba(239, 68, 68, 0.1);
                border: 1px solid var(--danger-color);
                border-radius: 8px;
                padding: 12px 15px;
                margin-bottom: 15px;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            
            .conflict-warning i {
                color: var(--danger-color);
                font-size: 1.2rem;
            }
            
            .conflict-warning h6 {
                margin: 0;
                color: var(--danger-color);
                font-weight: 600;
            }
            
            .conflict-warning p {
                margin: 0;
                font-size: 0.85rem;
                color: var(--gray-700);
            }
            
            .conflict-list {
                margin-top: 10px;
                padding-left: 20px;
            }
            
            .conflict-item {
                font-size: 0.8rem;
                color: var(--gray-700);
                margin-bottom: 5px;
            }
            
            .report-actions {
                display: flex;
                gap: 10px;
                margin-bottom: 20px;
            }
            
            .report-card {
                background: white;
                border-radius: 12px;
                padding: 20px;
                box-shadow: var(--card-shadow);
                margin-bottom: 20px;
            }
            
            .report-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
            }
            
            .report-title {
                font-weight: 700;
                color: var(--gray-800);
                margin: 0;
            }
            
            .archive-search-filter {
                display: flex;
                gap: 12px;
                margin-bottom: 15px;
                flex-wrap: wrap;
            }
            
            .archive-type-filter {
                min-width: 180px;
            }
            
            .archive-date-filter {
                min-width: 150px;
            }
            
            .archive-table th {
                background-color: var(--gray-100);
                border-top: none;
                font-weight: 600;
                color: var(--gray-700);
                padding: 12px;
                font-size: 0.8rem;
            }
            
            .archive-table td {
                padding: 12px;
                vertical-align: middle;
                font-size: 0.8rem;
            }
            
            .restore-btn {
                background-color: var(--success-color);
                border-color: var(--success-color);
                color: white;
            }
            
            .permanent-delete-btn {
                background-color: var(--danger-color);
                border-color: var(--danger-color);
                color: white;
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
            
            .reservation-status-completed {
                background-color: rgba(59, 130, 246, 0.1);
                color: #3b82f6;
            }
            
            .lab-badge {
                background-color: rgba(67, 97, 238, 0.1);
                color: var(--primary-color);
                padding: 4px 8px;
                border-radius: 12px;
                font-size: 0.7rem;
                font-weight: 600;
            }

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
            
            @keyframes slideInLeft {
                from {
                    opacity: 0;
                    transform: translateX(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            .stagger-item {
                animation: fadeInUp 0.6s ease forwards;
                opacity: 0;
            }

            .stagger-item:nth-child(1) { animation-delay: 0.1s; }
            .stagger-item:nth-child(2) { animation-delay: 0.2s; }
            .stagger-item:nth-child(3) { animation-delay: 0.3s; }
            .stagger-item:nth-child(4) { animation-delay: 0.4s; }
            .stagger-item:nth-child(5) { animation-delay: 0.5s; }
            
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
                
                .sidebar.expanded {
                    width: var(--sidebar-width);
                }
                
                .sidebar.expanded .sidebar-header h3 span {
                    display: inline;
                }
                
                .sidebar.expanded .sidebar-menu a span {
                    display: inline;
                }
                
                .sidebar.expanded .sidebar-menu a {
                    justify-content: flex-start;
                    padding: 12px 20px;
                }
                
                .sidebar.expanded .sidebar-menu i {
                    margin-right: 12px;
                }
                
                .sidebar.expanded .logout-btn span {
                    display: inline;
                }
                
                .sidebar.expanded .logout-btn {
                    justify-content: flex-start;
                    padding: 12px 20px;
                }

                .modal-landscape .modal-body {
                    grid-template-columns: 1fr;
                    gap: 15px;
                }

                .modal-landscape .event-details-section {
                    border-right: none;
                    padding-right: 0;
                    border-bottom: 1px solid var(--border-color);
                    padding-bottom: 15px;
                }

                .modal-landscape .participants-section {
                    padding-left: 0;
                }
                
                .event-details-modal .modal-body {
                    grid-template-columns: 1fr;
                    gap: 15px;
                }
                
                .event-details-section {
                    border-right: none;
                    padding-right: 0;
                    border-bottom: 1px solid var(--border-color);
                    padding-bottom: 15px;
                }
                
                .time-visualization-section {
                    padding-left: 0;
                }
            }
            
            @media (max-width: 768px) {
                .dashboard-container {
                    flex-direction: column;
                }
                
                .sidebar {
                    width: 100%;
                    height: auto;
                    position: relative;
                }
                
                .sidebar-menu {
                    display: flex;
                    overflow-x: auto;
                    padding: 10px 0;
                }
                
                .sidebar-menu li {
                    flex: 1;
                    min-width: 100px;
                    border-bottom: none;
                    border-right: 1px solid var(--border-color);
                }
                
                .sidebar-menu a {
                    justify-content: center;
                    flex-direction: column;
                    padding: 8px 4px;
                    text-align: center;
                    font-size: 0.7rem;
                }
                
                .sidebar-menu a span {
                    display: block;
                }
                
                .sidebar-menu i {
                    margin-right: 0;
                    margin-bottom: 4px;
                    font-size: 1rem;
                }
                
                .logout-btn {
                    display: none;
                }
                
                .main-content {
                    margin-left: 0;
                    padding: 15px;
                }
                
                .stats-cards {
                    grid-template-columns: 1fr;
                }
                
                .charts-container {
                    grid-template-columns: 1fr;
                }
                
                .card-header {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 12px;
                }
                
                .card-header .btn {
                    align-self: stretch;
                }
                
                .calendar-day {
                    min-height: 70px;
                }
                
                .event-item {
                    font-size: 0.6rem;
                }

                .quick-actions-grid {
                    grid-template-columns: 1fr;
                }
                
                .search-filter-container {
                    flex-direction: column;
                }
                
                .search-box, .filter-dropdown {
                    min-width: 100%;
                }
                
                .action-buttons {
                    flex-direction: column;
                }
                
                .report-actions {
                    flex-direction: column;
                }
                
                .archive-search-filter {
                    flex-direction: column;
                }
            }
        </style>
    </head>
    <body>
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
            <div class="sidebar">
                <div class="sidebar-header">
                    <h3><i class="fas fa-graduation-cap"></i> <span>Admin Portal</span></h3>
                    <button class="toggle-sidebar" id="toggleSidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <ul class="sidebar-menu">
                    <li><a href="#" class="nav-link" data-page="dashboard"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                    <li><a href="#" class="nav-link" data-page="manage-staff"><i class="fas fa-users"></i> <span>Manage Staff</span></a></li>
                    <li><a href="#" class="nav-link" data-page="manage-students"><i class="fas fa-user-graduate"></i> <span>Manage Students</span></a></li>
                    <li><a href="#" class="nav-link active" data-page="calendar"><i class="fas fa-calendar-alt"></i> <span>Calendar</span></a></li>
                    <li><a href="#" class="nav-link" data-page="reservation"><i class="fas fa-desktop"></i> <span>Reservation</span></a></li>
                    <li><a href="#" class="nav-link" data-page="archive"><i class="fas fa-archive"></i> <span>Archive</span></a></li>
                    <li><a href="#" class="nav-link" data-page="reports"><i class="fas fa-chart-bar"></i> <span>Reports</span></a></li>
                    <li>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <button class="logout-btn" id="logoutBtn">
                            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                        </button>
                    </li>
                </ul>
            </div>
            
            <div class="main-content">
                <div id="dashboard" class="page-content">
                    <div class="welcome-banner">
                        <h2>Welcome back, Administrator!</h2>
                        <p>Here's an overview of your system's performance and recent activities.</p>
                    </div>
                    
                    <div class="charts-container">
                        <div class="chart-card stagger-item">
                            <div class="chart-header">
                                <div class="chart-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h5 class="chart-title">Total Staff</h5>
                            </div>
                            <div class="chart-container">
                                <canvas id="staffChart"></canvas>
                            </div>
                        </div>
                        
                        <div class="chart-card stagger-item">
                            <div class="chart-header">
                                <div class="chart-icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <h5 class="chart-title">Total Students</h5>
                            </div>
                            <div class="chart-container">
                                <canvas id="studentsChart"></canvas>
                            </div>
                        </div>
                        
                        <div class="chart-card stagger-item">
                            <div class="chart-header">
                                <div class="chart-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <h5 class="chart-title">Active Courses</h5>
                            </div>
                            <div class="chart-container">
                                <canvas id="coursesChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stats-cards">
                        <div class="stat-card stagger-item">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-info">
                                <h3 id="totalStaffCount">0</h3>
                                <p>Total Staff</p>
                            </div>
                        </div>
                        <div class="stat-card stagger-item">
                            <div class="stat-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="stat-info">
                                <h3 id="totalStudentCount">0</h3>
                                <p>Total Students</p>
                            </div>
                        </div>
                        <div class="stat-card stagger-item">
                            <div class="stat-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="stat-info">
                                <h3 id="totalCourseCount">5</h3>
                                <p>Active Courses</p>
                            </div>
                        </div>
                        <div class="stat-card stagger-item">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-info">
                                <h3 id="totalEventCount">0</h3>
                                <p>Upcoming Events</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0"><i class="fas fa-history"></i> Recent Activity</h4>
                            <div class="card-actions">
                                <button class="card-action-btn" title="Refresh">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush" id="recentActivityList">
                            </ul>
                        </div>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h4>
                        </div>
                        <div class="card-body">
                            <div class="quick-actions-grid">
                                <div class="quick-action-card" onclick="navigateToPage('manage-staff')">
                                    <i class="fas fa-users"></i>
                                    <h5>Manage Staff</h5>
                                    <p>Add, edit, or remove staff members</p>
                                </div>
                                <div class="quick-action-card" onclick="navigateToPage('manage-students')">
                                    <i class="fas fa-user-graduate"></i>
                                    <h5>Manage Students</h5>
                                    <p>Handle student records and information</p>
                                </div>
                                <div class="quick-action-card" onclick="navigateToPage('calendar')">
                                    <i class="fas fa-calendar-alt"></i>
                                    <h5>Calendar</h5>
                                    <p>View and manage events schedule</p>
                                </div>
                                <div class="quick-action-card" onclick="navigateToPage('reservation')">
                                    <i class="fas fa-desktop"></i>
                                    <h5>Reservation</h5>
                                    <p>Manage lab reservations</p>
                                </div>
                                <div class="quick-action-card" onclick="navigateToPage('reports')">
                                    <i class="fas fa-chart-bar"></i>
                                    <h5>Reports</h5>
                                    <p>Generate and view system reports</p>
                                </div>
                                <div class="quick-action-card" onclick="navigateToPage('archive')">
                                    <i class="fas fa-archive"></i>
                                    <h5>Archive</h5>
                                    <p>View archived records</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
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
                    
                    <div class="search-filter-container">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" class="form-control" id="staffSearch" placeholder="Search staff by name, email, or department...">
                        </div>
                        <div class="filter-dropdown">
                            <select class="form-select" id="staffDepartmentFilter">
                                <option value="">All Departments</option>
                                <option value="BSIT">BSIT</option>
                                <option value="BSBA">BSBA</option>
                                <option value="BSHM">BSHM</option>
                                <option value="BEED">BEED</option>
                                <option value="BSED">BSED</option>
                                <option value="Administration">Administration</option>
                                <option value="Finance">Finance</option>
                                <option value="Human Resources">Human Resources</option>
                            </select>
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
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="staffTableBody">
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
                    
                    <div class="search-filter-container">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" class="form-control" id="studentSearch" placeholder="Search students by name, ID, or course...">
                        </div>
                        <div class="filter-dropdown">
                            <select class="form-select" id="studentCourseFilter">
                                <option value="">All Courses</option>
                                <option value="BSIT">BSIT</option>
                                <option value="BSBA">BSBA</option>
                                <option value="BSHM">BSHM</option>
                                <option value="BEED">BEED</option>
                                <option value="BSED">BSED</option>
                            </select>
                        </div>
                        <div class="filter-dropdown">
                            <select class="form-select" id="studentYearFilter">
                                <option value="">All Years</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                            </select>
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
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="studentTableBody">
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
                
                <div id="calendar" class="page-content active">
                    <div class="header">
                        <h1>Calendar</h1>
                        <div class="user-info">
                            <div class="user-avatar">
                                <span>A</span>
                            </div>
                            <span>Administrator</span>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0"><i class="fas fa-calendar-alt"></i> Academic Calendar</h4>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEventModal">
                                <i class="fas fa-plus"></i> Add Event
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
                                    <div class="calendar-view">
                                        <button class="btn btn-sm btn-outline-primary active" data-view="month">Month</button>
                                        <button class="btn btn-sm btn-outline-primary" data-view="list">List</button>
                                    </div>
                                </div>
                                <div class="calendar-view-container">
                                    <div class="calendar-month-view">
                                        <div class="calendar-grid" id="calendarGrid">
                                        </div>
                                    </div>
                                    <div class="calendar-list-view" style="display: none;">
                                        <div class="event-list" id="eventList">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="reservation" class="page-content">
                    <div class="header">
                        <h1>Computer Laboratory Reservation</h1>
                        <div class="user-info">
                            <div class="user-avatar">
                                <span>A</span>
                            </div>
                            <span>Administrator</span>
                        </div>
                    </div>
                    
                    <div class="search-filter-container">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" class="form-control" id="reservationSearch" placeholder="Search reservations by title, purpose, or requester...">
                        </div>
                        <div class="filter-dropdown">
                            <select class="form-select" id="reservationLabFilter">
                                <option value="">All Laboratories</option>
                                <option value="Computer Laboratory 1">Computer Laboratory 1</option>
                                <option value="Computer Laboratory 2">Computer Laboratory 2</option>
                                <option value="Computer Laboratory Netlab">Computer Laboratory Netlab</option>
                            </select>
                        </div>
                        <div class="filter-dropdown">
                            <select class="form-select" id="reservationStatusFilter">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0"><i class="fas fa-desktop"></i> Laboratory Reservations</h4>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addReservationModal">
                                <i class="fas fa-plus"></i> New Reservation
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Laboratory</th>
                                        <th>Date & Time</th>
                                        <th>Requester</th>
                                        <th>Purpose</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="reservationTableBody">
                                </tbody>
                            </table>
                        </div>
                        <div id="noReservationMessage" class="empty-state">
                            <i class="fas fa-desktop"></i>
                            <h4>No Reservations</h4>
                            <p>No laboratory reservations have been made yet.</p>
                            <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#addReservationModal">
                                <i class="fas fa-plus"></i> Create First Reservation
                            </button>
                        </div>
                    </div>
                </div>
                
                <div id="archive" class="page-content">
                    <div class="header">
                        <h1>Archive</h1>
                        <div class="user-info">
                            <div class="user-avatar">
                                <span>A</span>
                            </div>
                            <span>Administrator</span>
                        </div>
                    </div>
                    
                    <div class="archive-search-filter">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" class="form-control" id="archiveSearch" placeholder="Search archived items...">
                        </div>
                        <div class="filter-dropdown archive-type-filter">
                            <select class="form-select" id="archiveTypeFilter">
                                <option value="">All Types</option>
                                <option value="student">Students</option>
                                <option value="staff">Staff</option>
                                <option value="event">Events</option>
                                <option value="reservation">Reservations</option>
                            </select>
                        </div>
                        <div class="filter-dropdown archive-date-filter">
                            <select class="form-select" id="archiveDateFilter">
                                <option value="">All Time</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                            </select>
                        </div>
                        <button class="btn btn-danger" id="emptyArchiveBtn">
                            <i class="fas fa-trash"></i> Empty Archive
                        </button>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0"><i class="fas fa-archive"></i> Archived Records</h4>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover archive-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name/Title</th>
                                        <th>Type</th>
                                        <th>Details</th>
                                        <th>Archived Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="archiveTableBody">
                                </tbody>
                            </table>
                        </div>
                        <div id="noArchiveMessage" class="empty-state">
                            <i class="fas fa-archive"></i>
                            <h4>Archive Empty</h4>
                            <p>No items have been archived yet.</p>
                        </div>
                    </div>
                </div>
                
                <div id="reports" class="page-content">
                    <div class="header">
                        <h1>Reports</h1>
                        <div class="user-info">
                            <div class="user-avatar">
                                <span>A</span>
                            </div>
                            <span>Administrator</span>
                        </div>
                    </div>
                    
                    <div class="report-actions">
                        <button class="btn btn-primary" id="generateStudentReport">
                            <i class="fas fa-file-export me-2"></i> Generate Student Report
                        </button>
                        <button class="btn btn-primary" id="generateStaffReport">
                            <i class="fas fa-file-export me-2"></i> Generate Staff Report
                        </button>
                        <button class="btn btn-primary" id="generateEventReport">
                            <i class="fas fa-file-export me-2"></i> Generate Event Report
                        </button>
                        <button class="btn btn-primary" id="generateReservationReport">
                            <i class="fas fa-file-export me-2"></i> Generate Reservation Report
                        </button>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0"><i class="fas fa-chart-bar"></i> System Reports</h4>
                        </div>
                        <div class="card-body">
                            <div class="report-card">
                                <div class="report-header">
                                    <h5 class="report-title">Student Statistics</h5>
                                    <button class="btn btn-sm btn-outline-primary" id="exportStudentStats">
                                        <i class="fas fa-download me-1"></i> Export
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <canvas id="studentCourseChart" height="250"></canvas>
                                    </div>
                                    <div class="col-md-6">
                                        <canvas id="studentYearChart" height="250"></canvas>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="report-card">
                                <div class="report-header">
                                    <h5 class="report-title">Event Statistics</h5>
                                    <button class="btn btn-sm btn-outline-primary" id="exportEventStats">
                                        <i class="fas fa-download me-1"></i> Export
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <canvas id="eventTypeChart" height="250"></canvas>
                                    </div>
                                    <div class="col-md-6">
                                        <canvas id="eventMonthlyChart" height="250"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="report-card">
                                <div class="report-header">
                                    <h5 class="report-title">Reservation Statistics</h5>
                                    <button class="btn btn-sm btn-outline-primary" id="exportReservationStats">
                                        <i class="fas fa-download me-1"></i> Export
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <canvas id="reservationLabChart" height="250"></canvas>
                                    </div>
                                    <div class="col-md-6">
                                        <canvas id="reservationStatusChart" height="250"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStudentModalLabel"><i class="fas fa-user-graduate"></i> Add New Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle me-2"></i> Please fill out all required fields marked with <span class="text-danger">*</span>
                        </div>
                        <form id="addStudentForm">
                            <input type="hidden" id="editStudentId">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="studentId" class="form-label">Student ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="studentId" placeholder="Enter student ID (must start with C)" required>
                                        <div class="form-text">Student ID must start with 'C' followed by numbers</div>
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
                                        <input type="email" class="form-control" id="studentEmail" placeholder="username@smcbi.edu.ph" required>
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
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="studentPhone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="studentPhone" placeholder="Enter phone number (numbers only)">
                                        <div class="form-text">Only numbers allowed (no letters or special characters)</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="studentAddress" class="form-label">Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="studentAddress" placeholder="Enter address" required>
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

        <div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStaffModalLabel"><i class="fas fa-user-plus"></i> Add New Staff Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle me-2"></i> Please fill out all required fields marked with <span class="text-danger">*</span>
                        </div>
                        <form id="addStaffForm">
                            <input type="hidden" id="editStaffId">
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
                                        <input type="email" class="form-control" id="staffEmail" placeholder="username@smcbi.edu.ph" required>
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
                                            <option value="Administration">Administration</option>
                                            <option value="Finance">Finance</option>
                                            <option value="Human Resources">Human Resources</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="staffPhone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="staffPhone" placeholder="Enter phone number (numbers only)">
                                        <div class="form-text">Only numbers allowed (no letters or special characters)</div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="staffAddress" class="form-label">Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="staffAddress" rows="3" placeholder="Enter address" required></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveStaffBtn">Save Staff</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEventModalLabel"><i class="fas fa-calendar-plus"></i> Add New Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="conflict-warning" id="conflictWarning" style="display: none;">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div>
                                <h6>Potential Conflict Detected</h6>
                                <p id="conflictMessage">This event may conflict with existing events.</p>
                                <div class="conflict-list" id="conflictList"></div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle me-2"></i> Please fill out all required fields marked with <span class="text-danger">*</span>
                        </div>
                        <form id="addEventForm">
                            <input type="hidden" id="editEventId">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="eventTitle" class="form-label">Event Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="eventTitle" placeholder="Enter event title" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="eventType" class="form-label">Event Type <span class="text-danger">*</span></label>
                                        <select class="form-select" id="eventType" required>
                                            <option value="" selected disabled>Select Event Type</option>
                                            <option value="academic">Academic</option>
                                            <option value="personal">Personal</option>
                                            <option value="holiday">Holiday</option>
                                            <option value="meeting">Meeting</option>
                                            <option value="class">Class</option>
                                            <option value="exam">Exam</option>
                                            <option value="workshop">Workshop</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="eventDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="eventDescription" rows="3" placeholder="Enter event description"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="eventDate" class="form-label">Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="eventDate" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="eventTime" class="form-label">Time <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" id="eventTime" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="eventStartTime" class="form-label">Start Time</label>
                                        <input type="time" class="form-control" id="eventStartTime">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="eventEndTime" class="form-label">End Time</label>
                                        <input type="time" class="form-control" id="eventEndTime">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="computerLab" class="form-label">Computer Laboratory</label>
                                        <select class="form-select" id="computerLab">
                                            <option value="" selected>No Laboratory Required</option>
                                            <option value="Computer Laboratory 1">Computer Laboratory 1</option>
                                            <option value="Computer Laboratory 2">Computer Laboratory 2</option>
                                            <option value="Computer Laboratory Netlab">Computer Laboratory Netlab</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Participants</label>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span id="selectedParticipantsCount">0</span> participants selected
                                    </div>
                                    <button type="button" class="btn btn-outline-primary" id="selectParticipantsBtn">
                                        <i class="fas fa-users me-1"></i> Select Participants
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveEventBtn">Save Event</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addReservationModal" tabindex="-1" aria-labelledby="addReservationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addReservationModalLabel"><i class="fas fa-desktop"></i> New Laboratory Reservation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle me-2"></i> Please fill out all required fields marked with <span class="text-danger">*</span>
                        </div>
                        <form id="addReservationForm">
                            <input type="hidden" id="editReservationId">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="reservationTitle" class="form-label">Reservation Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="reservationTitle" placeholder="Enter reservation title" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="reservationLab" class="form-label">Computer Laboratory <span class="text-danger">*</span></label>
                                        <select class="form-select" id="reservationLab" required>
                                            <option value="" selected disabled>Select Laboratory</option>
                                            <option value="Computer Laboratory 1">Computer Laboratory 1</option>
                                            <option value="Computer Laboratory 2">Computer Laboratory 2</option>
                                            <option value="Computer Laboratory Netlab">Computer Laboratory Netlab</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="reservationPurpose" class="form-label">Purpose <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="reservationPurpose" rows="3" placeholder="Describe the purpose of this reservation" required></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="reservationDate" class="form-label">Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="reservationDate" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="reservationRequester" class="form-label">Requester <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="reservationRequester" placeholder="Enter requester name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="reservationStartTime" class="form-label">Start Time <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" id="reservationStartTime" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="reservationEndTime" class="form-label">End Time <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" id="reservationEndTime" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="reservationParticipants" class="form-label">Expected Participants</label>
                                <input type="number" class="form-control" id="reservationParticipants" placeholder="Enter number of participants" min="1">
                            </div>
                            <div class="mb-3">
                                <label for="reservationRequirements" class="form-label">Special Requirements</label>
                                <textarea class="form-control" id="reservationRequirements" rows="2" placeholder="Any special software or equipment requirements"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="saveReservationBtn">Save Reservation</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="participantSelectionModal" tabindex="-1" aria-labelledby="participantSelectionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="participantSelectionModalLabel"><i class="fas fa-users"></i> Select Participants</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="search-box">
                                    <i class="fas fa-search"></i>
                                    <input type="text" class="form-control" id="participantSearch" placeholder="Search students or staff...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="participantCourseFilter">
                                    <option value="">All Courses</option>
                                    <option value="BSIT">BSIT</option>
                                    <option value="BSBA">BSBA</option>
                                    <option value="BSHM">BSHM</option>
                                    <option value="BEED">BEED</option>
                                    <option value="BSED">BSED</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="participantYearFilter">
                                    <option value="">All Years</option>
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                    <option value="4th Year">4th Year</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0"><i class="fas fa-user-graduate me-2"></i> Students 
                                            <span class="badge bg-primary" id="studentCount">0</span>
                                        </h6>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllStudents()">
                                                <i class="fas fa-check-double"></i> All
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearStudents()">
                                                <i class="fas fa-times"></i> Clear
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-info" onclick="selectStudentsByFilter()">
                                                <i class="fas fa-filter"></i> Filtered
                                            </button>
                                        </div>
                                    </div>
                                    <div class="participants-container">
                                        <div id="studentsCheckboxList">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0"><i class="fas fa-users me-2"></i> Staff 
                                            <span class="badge bg-primary" id="staffCount">0</span>
                                        </h6>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllStaff()">
                                                <i class="fas fa-check-double"></i> All
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearStaff()">
                                                <i class="fas fa-times"></i> Clear
                                            </button>
                                        </div>
                                    </div>
                                    <div class="participants-container">
                                        <div id="staffCheckboxList">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmParticipantsBtn">Confirm Selection</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade event-details-modal" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventDetailsModalLabel"><i class="fas fa-calendar-day"></i> Event Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="event-details-section">
                            <div class="mb-4">
                                <h4 id="detailEventTitle" class="text-primary mb-3"></h4>
                                <p id="detailEventDescription" class="text-muted fs-6"></p>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-calendar text-primary me-3 fs-5"></i>
                                        <div>
                                            <div class="text-muted small">Date</div>
                                            <div id="detailEventDate" class="fw-semibold"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-clock text-primary me-3 fs-5"></i>
                                        <div>
                                            <div class="text-muted small">Time</div>
                                            <div id="detailEventTime" class="fw-semibold"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center mb-4">
                                <i class="fas fa-tag text-primary me-3 fs-5"></i>
                                <div>
                                    <div class="text-muted small">Event Type</div>
                                    <span id="detailEventType" class="badge bg-primary fs-6"></span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-4">
                                <i class="fas fa-desktop text-primary me-3 fs-5"></i>
                                <div>
                                    <div class="text-muted small">Computer Laboratory</div>
                                    <span id="detailComputerLab" class="fw-semibold">No Laboratory Required</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-4">
                                <i class="fas fa-check-circle text-primary me-3 fs-5"></i>
                                <div>
                                    <div class="text-muted small">Status</div>
                                    <span id="detailEventStatus" class="badge bg-success fs-6">Active</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold mb-3"><i class="fas fa-user-graduate me-2"></i> Students in this Event</h6>
                                <div class="participants-list" id="detailStudentsList">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3"><i class="fas fa-users me-2"></i> Staff in this Event</h6>
                                <div class="participants-list" id="detailStaffList">
                                </div>
                            </div>

                            <div class="event-actions mt-4">
                                <button class="btn btn-success me-2" id="markEventCompleteBtn">
                                    <i class="fas fa-check me-2"></i> Mark as Complete
                                </button>
                                <button class="btn btn-warning me-2" id="editEventBtn">
                                    <i class="fas fa-edit me-2"></i> Edit Event
                                </button>
                                <button class="btn btn-danger" id="deleteEventBtn">
                                    <i class="fas fa-trash me-2"></i> Delete Event
                                </button>
                            </div>
                        </div>
                        
                        <div class="time-visualization-section">
                            <div class="time-visualization">
                                <div class="time-header">
                                    <h6 class="time-title">Daily Schedule</h6>
                                    <span id="detailEventDay" class="badge bg-secondary">Today</span>
                                </div>
                                <div class="time-slots" id="timeSlots">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            const Database = {
                getStaff: function() {
                    return JSON.parse(localStorage.getItem('staff') || '[]');
                },

                getStudents: function() {
                    return JSON.parse(localStorage.getItem('students') || '[]');
                },
                
                getReservations: function() {
                    return JSON.parse(localStorage.getItem('reservations') || '[]');
                },

                init: function() {
                    if (!localStorage.getItem('staff')) {
                        localStorage.setItem('staff', JSON.stringify([]));
                    }
                    
                    if (!localStorage.getItem('students')) {
                        localStorage.setItem('students', JSON.stringify([]));
                    }
                    
                    if (!localStorage.getItem('calendarEvents')) {
                        localStorage.setItem('calendarEvents', JSON.stringify([]));
                    }
                    
                    if (!localStorage.getItem('reservations')) {
                        localStorage.setItem('reservations', JSON.stringify([]));
                    }
                    
                    if (!localStorage.getItem('archive')) {
                        localStorage.setItem('archive', JSON.stringify({
                            students: [],
                            staff: [],
                            events: [],
                            reservations: []
                        }));
                    }
                    
                    console.log('Database initialized - Empty arrays');
                }
            };

            let staffChart, studentsChart, coursesChart, studentCourseChart, studentYearChart, eventTypeChart, eventMonthlyChart, reservationLabChart, reservationStatusChart;

            function initializeCharts() {
                const staff = Database.getStaff();
                const students = Database.getStudents();
                const reservations = Database.getReservations();
                
                const courseCounts = {};
                students.forEach(student => {
                    const course = student.course || 'Unknown';
                    courseCounts[course] = (courseCounts[course] || 0) + 1;
                });
                
                document.getElementById('totalStaffCount').textContent = staff.length;
                document.getElementById('totalStudentCount').textContent = students.length;
                document.getElementById('totalCourseCount').textContent = Object.keys(courseCounts).length;
                document.getElementById('totalEventCount').textContent = reservations.length;
                
                const staffCtx = document.getElementById('staffChart').getContext('2d');
                staffChart = new Chart(staffCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Total Staff'],
                        datasets: [{
                            label: 'Staff Count',
                            data: [staff.length],
                            backgroundColor: 'rgba(67, 97, 238, 0.8)',
                            borderColor: 'rgba(67, 97, 238, 1)',
                            borderWidth: 1,
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Staff Count: ${context.raw}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
                
                const studentsCtx = document.getElementById('studentsChart').getContext('2d');
                studentsChart = new Chart(studentsCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Total Students'],
                        datasets: [{
                            label: 'Student Count',
                            data: [students.length],
                            backgroundColor: 'rgba(76, 201, 240, 0.8)',
                            borderColor: 'rgba(76, 201, 240, 1)',
                            borderWidth: 1,
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Student Count: ${context.raw}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
                
                const coursesCtx = document.getElementById('coursesChart').getContext('2d');
                coursesChart = new Chart(coursesCtx, {
                    type: 'pie',
                    data: {
                        labels: Object.keys(courseCounts),
                        datasets: [{
                            data: Object.values(courseCounts),
                            backgroundColor: [
                                'rgba(67, 97, 238, 0.8)',
                                'rgba(76, 201, 240, 0.8)',
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(139, 92, 246, 0.8)'
                            ],
                            borderColor: [
                                'rgba(67, 97, 238, 1)',
                                'rgba(76, 201, 240, 1)',
                                'rgba(239, 68, 68, 1)',
                                'rgba(245, 158, 11, 1)',
                                'rgba(59, 130, 246, 1)',
                                'rgba(139, 92, 246, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = Math.round((value / total) * 100);
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function initializeReportCharts() {
                const students = Database.getStudents();
                const events = JSON.parse(localStorage.getItem('calendarEvents')) || [];
                const reservations = Database.getReservations();
                
                const courseCounts = {};
                students.forEach(student => {
                    const course = student.course || 'Unknown';
                    courseCounts[course] = (courseCounts[course] || 0) + 1;
                });
                
                const studentCourseCtx = document.getElementById('studentCourseChart').getContext('2d');
                studentCourseChart = new Chart(studentCourseCtx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(courseCounts),
                        datasets: [{
                            data: Object.values(courseCounts),
                            backgroundColor: [
                                'rgba(67, 97, 238, 0.8)',
                                'rgba(76, 201, 240, 0.8)',
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(59, 130, 246, 0.8)'
                            ],
                            borderColor: [
                                'rgba(67, 97, 238, 1)',
                                'rgba(76, 201, 240, 1)',
                                'rgba(239, 68, 68, 1)',
                                'rgba(245, 158, 11, 1)',
                                'rgba(59, 130, 246, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true
                                },
                                title: {
                                    display: true,
                                    text: 'Course Distribution'
                                }
                            }
                        }
                    }
                });
                
                const yearCounts = {};
                students.forEach(student => {
                    const year = student.year_level || 'Unknown';
                    yearCounts[year] = (yearCounts[year] || 0) + 1;
                });
                
                const studentYearCtx = document.getElementById('studentYearChart').getContext('2d');
                studentYearChart = new Chart(studentYearCtx, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(yearCounts),
                        datasets: [{
                            label: 'Number of Students',
                            data: Object.values(yearCounts),
                            backgroundColor: 'rgba(76, 201, 240, 0.8)',
                            borderColor: 'rgba(76, 201, 240, 1)',
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Students by Year Level'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
                
                const eventTypeCounts = {};
                events.forEach(event => {
                    const type = event.type || 'Unknown';
                    eventTypeCounts[type] = (eventTypeCounts[type] || 0) + 1;
                });
                
                const eventTypeCtx = document.getElementById('eventTypeChart').getContext('2d');
                eventTypeChart = new Chart(eventTypeCtx, {
                    type: 'pie',
                    data: {
                        labels: Object.keys(eventTypeCounts),
                        datasets: [{
                            data: Object.values(eventTypeCounts),
                            backgroundColor: [
                                'rgba(67, 97, 238, 0.8)',
                                'rgba(76, 201, 240, 0.8)',
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(139, 92, 246, 0.8)',
                                'rgba(16, 185, 129, 0.8)'
                            ],
                            borderColor: [
                                'rgba(67, 97, 238, 1)',
                                'rgba(76, 201, 240, 1)',
                                'rgba(239, 68, 68, 1)',
                                'rgba(245, 158, 11, 1)',
                                'rgba(59, 130, 246, 1)',
                                'rgba(139, 92, 246, 1)',
                                'rgba(16, 185, 129, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true
                                },
                                title: {
                                    display: true,
                                    text: 'Event Types'
                                }
                            }
                        }
                    }
                });
                
                const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                const monthlyCounts = Array(12).fill(0);
                
                events.forEach(event => {
                    const eventDate = new Date(event.date);
                    const month = eventDate.getMonth();
                    monthlyCounts[month]++;
                });
                
                const eventMonthlyCtx = document.getElementById('eventMonthlyChart').getContext('2d');
                eventMonthlyChart = new Chart(eventMonthlyCtx, {
                    type: 'line',
                    data: {
                        labels: monthNames,
                        datasets: [{
                            label: 'Number of Events',
                            data: monthlyCounts,
                            backgroundColor: 'rgba(67, 97, 238, 0.1)',
                            borderColor: 'rgba(67, 97, 238, 1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Events by Month'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });

                const labCounts = {};
                reservations.forEach(reservation => {
                    const lab = reservation.lab || 'Unknown';
                    labCounts[lab] = (labCounts[lab] || 0) + 1;
                });
                
                const reservationLabCtx = document.getElementById('reservationLabChart').getContext('2d');
                reservationLabChart = new Chart(reservationLabCtx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(labCounts),
                        datasets: [{
                            data: Object.values(labCounts),
                            backgroundColor: [
                                'rgba(67, 97, 238, 0.8)',
                                'rgba(76, 201, 240, 0.8)',
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(245, 158, 11, 0.8)'
                            ],
                            borderColor: [
                                'rgba(67, 97, 238, 1)',
                                'rgba(76, 201, 240, 1)',
                                'rgba(239, 68, 68, 1)',
                                'rgba(245, 158, 11, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true
                                },
                                title: {
                                    display: true,
                                    text: 'Reservations by Laboratory'
                                }
                            }
                        }
                    }
                });

                const statusCounts = {
                    pending: 0,
                    approved: 0,
                    rejected: 0,
                    completed: 0
                };
                
                reservations.forEach(reservation => {
                    const status = reservation.status || 'pending';
                    statusCounts[status] = (statusCounts[status] || 0) + 1;
                });
                
                const reservationStatusCtx = document.getElementById('reservationStatusChart').getContext('2d');
                reservationStatusChart = new Chart(reservationStatusCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Pending', 'Approved', 'Rejected', 'Completed'],
                        datasets: [{
                            label: 'Number of Reservations',
                            data: [statusCounts.pending, statusCounts.approved, statusCounts.rejected, statusCounts.completed],
                            backgroundColor: [
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(59, 130, 246, 0.8)'
                            ],
                            borderColor: [
                                'rgba(245, 158, 11, 1)',
                                'rgba(16, 185, 129, 1)',
                                'rgba(239, 68, 68, 1)',
                                'rgba(59, 130, 246, 1)'
                            ],
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Reservations by Status'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            function updateCharts() {
                const staff = Database.getStaff();
                const students = Database.getStudents();
                const reservations = Database.getReservations();
                
                const courseCounts = {};
                students.forEach(student => {
                    const course = student.course || 'Unknown';
                    courseCounts[course] = (courseCounts[course] || 0) + 1;
                });
                
                document.getElementById('totalStaffCount').textContent = staff.length;
                document.getElementById('totalStudentCount').textContent = students.length;
                document.getElementById('totalCourseCount').textContent = Object.keys(courseCounts).length;
                document.getElementById('totalEventCount').textContent = reservations.length;
                
                staffChart.data.datasets[0].data = [staff.length];
                staffChart.update();
                
                studentsChart.data.datasets[0].data = [students.length];
                studentsChart.update();
                
                coursesChart.data.labels = Object.keys(courseCounts);
                coursesChart.data.datasets[0].data = Object.values(courseCounts);
                coursesChart.update();
                
                if (studentCourseChart) {
                    initializeReportCharts();
                }
            }

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

            function validateEmailDomain(email) {
                const validDomain = '@smcbi.edu.ph';
                return email.endsWith(validDomain);
            }

            function showEmailError(fieldId) {
                const field = document.getElementById(fieldId);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'text-danger mt-1';
                errorDiv.style.fontSize = '0.75rem';
                errorDiv.id = fieldId + 'Error';
                errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Email must end with @smcbi.edu.ph';
                
                const existingError = document.getElementById(fieldId + 'Error');
                if (existingError) {
                    existingError.remove();
                }
                
                field.parentNode.appendChild(errorDiv);
                field.classList.add('is-invalid');
            }

            function removeEmailError(fieldId) {
                const existingError = document.getElementById(fieldId + 'Error');
                if (existingError) {
                    existingError.remove();
                }
                const field = document.getElementById(fieldId);
                if (field) {
                    field.classList.remove('is-invalid');
                }
            }

            let currentDate = new Date();
            let currentMonth = currentDate.getMonth();
            let currentYear = currentDate.getFullYear();
            let events = JSON.parse(localStorage.getItem('calendarEvents')) || [];

            const calendarTitle = document.getElementById('calendarTitle');
            const calendarGrid = document.getElementById('calendarGrid');
            const prevMonthBtn = document.getElementById('prevMonth');
            const nextMonthBtn = document.getElementById('nextMonth');
            const todayBtn = document.getElementById('todayBtn');
            const calendarViewBtns = document.querySelectorAll('.calendar-view button');
            const calendarMonthView = document.querySelector('.calendar-month-view');
            const calendarListView = document.querySelector('.calendar-list-view');
            const eventList = document.getElementById('eventList');
            const timeSlots = document.getElementById('timeSlots');

            let allStudents = [];
            let allStaff = [];
            let filteredStudents = [];

            function populateParticipantLists() {
                allStudents = Database.getStudents();
                allStaff = Database.getStaff();
                
                filterParticipants();
                
                updateParticipantCounts();
            }

            function filterParticipants() {
                const searchTerm = document.getElementById('participantSearch').value.toLowerCase();
                const courseFilter = document.getElementById('participantCourseFilter').value;
                const yearFilter = document.getElementById('participantYearFilter').value;
                
                filteredStudents = allStudents.filter(student => {
                    const matchesSearch = !searchTerm || 
                        (student.full_name || student.name || '').toLowerCase().includes(searchTerm) ||
                        (student.student_id || '').toLowerCase().includes(searchTerm) ||
                        (student.course || '').toLowerCase().includes(searchTerm);
                    
                    const matchesCourse = !courseFilter || student.course === courseFilter;
                    const matchesYear = !yearFilter || student.year_level === yearFilter;
                    
                    return matchesSearch && matchesCourse && matchesYear;
                });
                
                const filteredStaff = allStaff.filter(staff => {
                    return !searchTerm || 
                        (staff.full_name || staff.name || '').toLowerCase().includes(searchTerm) ||
                        (staff.email || '').toLowerCase().includes(searchTerm) ||
                        (staff.department || '').toLowerCase().includes(searchTerm);
                });
                
                renderStudentCheckboxes(filteredStudents);
                renderStaffCheckboxes(filteredStaff);
                updateParticipantCounts();
            }

            function renderStudentCheckboxes(students) {
                const studentsList = document.getElementById('studentsCheckboxList');
                studentsList.innerHTML = '';
                
                if (students && students.length > 0) {
                    students.forEach(student => {
                        const checkbox = document.createElement('div');
                        checkbox.className = 'form-check';
                        checkbox.innerHTML = `
                            <input class="form-check-input student-checkbox" type="checkbox" value="${student.id}" id="student_${student.id}">
                            <label class="form-check-label" for="student_${student.id}">
                                <strong>${student.full_name || student.name || 'Unknown Student'}</strong>
                                <div class="small text-muted">
                                    ${student.student_id || 'N/A'} • ${student.course || 'N/A'} • ${student.year_level || 'N/A'}
                                </div>
                            </label>
                        `;
                        studentsList.appendChild(checkbox);
                    });
                } else {
                    studentsList.innerHTML = '<div class="text-muted text-center py-3">No students found</div>';
                }
            }

            function renderStaffCheckboxes(staff) {
                const staffList = document.getElementById('staffCheckboxList');
                staffList.innerHTML = '';
                
                if (staff && staff.length > 0) {
                    staff.forEach(staffMember => {
                        const checkbox = document.createElement('div');
                        checkbox.className = 'form-check';
                        checkbox.innerHTML = `
                            <input class="form-check-input staff-checkbox" type="checkbox" value="${staffMember.id}" id="staff_${staffMember.id}">
                            <label class="form-check-label" for="staff_${staffMember.id}">
                                <strong>${staffMember.full_name || staffMember.name || 'Unknown Staff'}</strong>
                                <div class="small text-muted">${staffMember.department || 'N/A'}</div>
                            </label>
                        `;
                        staffList.appendChild(checkbox);
                    });
                } else {
                    staffList.innerHTML = '<div class="text-muted text-center py-3">No staff found</div>';
                }
            }

            function updateParticipantCounts() {
                const studentCheckboxes = document.querySelectorAll('.student-checkbox');
                const staffCheckboxes = document.querySelectorAll('.staff-checkbox');
                
                document.getElementById('studentCount').textContent = studentCheckboxes.length;
                document.getElementById('staffCount').textContent = staffCheckboxes.length;
            }

            function selectAllStudents() {
                const checkboxes = document.querySelectorAll('.student-checkbox');
                if (checkboxes.length === 0) {
                    showToast('No students available to select', 'error');
                    return;
                }
                
                let selectedCount = 0;
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                    selectedCount++;
                });
                
                updateSelectedParticipantsCount();
                showToast(`All ${selectedCount} students selected!`, 'success');
            }

            function selectAllStaff() {
                const checkboxes = document.querySelectorAll('.staff-checkbox');
                if (checkboxes.length === 0) {
                    showToast('No staff available to select', 'error');
                    return;
                }
                
                let selectedCount = 0;
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                    selectedCount++;
                });
                
                updateSelectedParticipantsCount();
                showToast(`All ${selectedCount} staff selected!`, 'success');
            }

            function selectStudentsByFilter() {
                const checkboxes = document.querySelectorAll('.student-checkbox');
                if (checkboxes.length === 0) {
                    showToast('No students available to select', 'error');
                    return;
                }
                
                let selectedCount = 0;
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                    selectedCount++;
                });
                
                updateSelectedParticipantsCount();
                showToast(`${selectedCount} students selected based on current filter!`, 'success');
            }

            function clearStudents() {
                document.querySelectorAll('.student-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateSelectedParticipantsCount();
                showToast('Student selection cleared!', 'success');
            }

            function clearStaff() {
                document.querySelectorAll('.staff-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateSelectedParticipantsCount();
                showToast('Staff selection cleared!', 'success');
            }

            function clearAllParticipants() {
                document.querySelectorAll('.student-checkbox, .staff-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateSelectedParticipantsCount();
            }

            function getSelectedParticipants() {
                const selectedStudents = Array.from(document.querySelectorAll('.student-checkbox:checked'))
                    .map(checkbox => parseInt(checkbox.value));
                
                const selectedStaff = Array.from(document.querySelectorAll('.staff-checkbox:checked'))
                    .map(checkbox => parseInt(checkbox.value));
                
                return {
                    students: selectedStudents,
                    staff: selectedStaff
                };
            }

            function setSelectedParticipants(studentIds = [], staffIds = []) {
                clearAllParticipants();
                
                studentIds.forEach(id => {
                    const checkbox = document.querySelector(`.student-checkbox[value="${id}"]`);
                    if (checkbox) checkbox.checked = true;
                });
                
                staffIds.forEach(id => {
                    const checkbox = document.querySelector(`.staff-checkbox[value="${id}"]`);
                    if (checkbox) checkbox.checked = true;
                });
                
                updateSelectedParticipantsCount();
            }

            function updateSelectedParticipantsCount() {
                const selected = getSelectedParticipants();
                const totalCount = selected.students.length + selected.staff.length;
                document.getElementById('selectedParticipantsCount').textContent = totalCount;
            }

            function checkEventConflicts(eventData, excludeEventId = null) {
                const conflicts = [];
                const events = JSON.parse(localStorage.getItem('calendarEvents')) || [];
                
                const newEventDate = new Date(eventData.date);
                const newEventStartTime = eventData.startTime || '00:00';
                const newEventEndTime = eventData.endTime || '23:59';
                const newEventLab = eventData.computerLab || '';
                
                events.forEach(event => {
                    if (excludeEventId && event.id == excludeEventId) return;
                    
                    const existingEventDate = new Date(event.date);
                    
                    if (newEventDate.toDateString() === existingEventDate.toDateString()) {
                        const existingEventStartTime = event.startTime || '00:00';
                        const existingEventEndTime = event.endTime || '23:59';
                        const existingEventLab = event.computerLab || '';
                        
                        if ((newEventStartTime < existingEventEndTime && newEventEndTime > existingEventStartTime)) {
                            if (newEventLab && existingEventLab && newEventLab === existingEventLab) {
                                conflicts.push({
                                    type: 'lab',
                                    event: event,
                                    message: `Lab conflict: ${event.title} also uses ${newEventLab} at the same time`
                                });
                            } else {
                                conflicts.push({
                                    type: 'time',
                                    event: event,
                                    message: `Time conflict: ${event.title} at ${formatTime(existingEventStartTime)} - ${formatTime(existingEventEndTime)}`
                                });
                            }
                        }
                        
                        if (eventData.participants) {
                            const newParticipants = [...(eventData.participants.students || []), ...(eventData.participants.staff || [])];
                            const existingParticipants = [...(event.participants?.students || []), ...(event.participants?.staff || [])];
                            
                            const commonParticipants = newParticipants.filter(id => existingParticipants.includes(id));
                            
                            if (commonParticipants.length > 0) {
                                const students = Database.getStudents();
                                const staff = Database.getStaff();
                                const participantNames = commonParticipants.map(id => {
                                    const student = students.find(s => s.id == id);
                                    if (student) return student.full_name || student.name;
                                    const staffMember = staff.find(s => s.id == id);
                                    if (staffMember) return staffMember.full_name || staffMember.name;
                                    return 'Unknown';
                                });
                                
                                conflicts.push({
                                    type: 'participant',
                                    event: event,
                                    message: `Participant conflict: ${participantNames.join(', ')} already scheduled for ${event.title}`
                                });
                            }
                        }
                    }
                });
                
                return conflicts;
            }

            function showConflictWarning(conflicts) {
                const warningDiv = document.getElementById('conflictWarning');
                const conflictMessage = document.getElementById('conflictMessage');
                const conflictList = document.getElementById('conflictList');
                
                if (conflicts.length > 0) {
                    warningDiv.style.display = 'flex';
                    conflictMessage.textContent = `Found ${conflicts.length} potential conflict${conflicts.length > 1 ? 's' : ''}:`;
                    
                    conflictList.innerHTML = '';
                    conflicts.forEach(conflict => {
                        const conflictItem = document.createElement('div');
                        conflictItem.className = 'conflict-item';
                        conflictItem.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i> ${conflict.message}`;
                        conflictList.appendChild(conflictItem);
                    });
                } else {
                    warningDiv.style.display = 'none';
                }
            }

            function showEventDetails(eventId) {
                const event = events.find(e => e.id == eventId);
                if (!event) {
                    showToast('Event not found!', 'error');
                    return;
                }
                
                document.getElementById('detailEventTitle').textContent = event.title;
                document.getElementById('detailEventDescription').textContent = event.description || 'No description provided.';
                document.getElementById('detailEventDate').textContent = new Date(event.date).toLocaleDateString();
                document.getElementById('detailEventTime').textContent = event.startTime ? 
                    `${formatTime(event.startTime)} - ${formatTime(event.endTime)}` : 'All day';
                document.getElementById('detailEventType').textContent = event.type.charAt(0).toUpperCase() + event.type.slice(1);
                
                const status = event.status || 'active';
                const statusElement = document.getElementById('detailEventStatus');
                statusElement.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                if (status === 'completed') {
                    statusElement.className = 'badge bg-success fs-6';
                    document.getElementById('markEventCompleteBtn').style.display = 'none';
                } else {
                    statusElement.className = 'badge bg-primary fs-6';
                    document.getElementById('markEventCompleteBtn').style.display = 'inline-block';
                }
                
                const computerLab = event.computerLab || 'No Laboratory Required';
                document.getElementById('detailComputerLab').textContent = computerLab;
                
                const eventDate = new Date(event.date);
                const today = new Date();
                let dayIndicator = '';
                
                if (eventDate.toDateString() === today.toDateString()) {
                    dayIndicator = 'Today';
                } else if (eventDate.getTime() === today.getTime() + 86400000) {
                    dayIndicator = 'Tomorrow';
                } else {
                    dayIndicator = eventDate.toLocaleDateString('en-US', { weekday: 'long' });
                }
                
                document.getElementById('detailEventDay').textContent = dayIndicator;
                
                populateDetailParticipants(event);
                
                renderTimeVisualization(event);
                
                document.getElementById('editEventBtn').onclick = () => {
                    bootstrap.Modal.getInstance(document.getElementById('eventDetailsModal')).hide();
                    editEvent(eventId);
                };
                
                document.getElementById('deleteEventBtn').onclick = () => {
                    bootstrap.Modal.getInstance(document.getElementById('eventDetailsModal')).hide();
                    deleteEvent(eventId);
                };
                
                document.getElementById('markEventCompleteBtn').onclick = () => {
                    markEventComplete(eventId);
                };
                
                new bootstrap.Modal(document.getElementById('eventDetailsModal')).show();
            }

            function markEventComplete(eventId) {
                const event = events.find(e => e.id == eventId);
                if (!event) {
                    showToast('Event not found!', 'error');
                    return;
                }
                
                event.status = 'completed';
                event.completed_at = new Date().toISOString();
                localStorage.setItem('calendarEvents', JSON.stringify(events));
                
                showToast('Event marked as complete!', 'success');
                bootstrap.Modal.getInstance(document.getElementById('eventDetailsModal')).hide();
                
                renderCalendar();
                renderEventList();
            }

            function populateDetailParticipants(event) {
                const students = Database.getStudents();
                const staff = Database.getStaff();
                
                const studentsList = document.getElementById('detailStudentsList');
                studentsList.innerHTML = '';
                
                if (event.participants && event.participants.students && event.participants.students.length > 0) {
                    event.participants.students.forEach(studentId => {
                        const student = students.find(s => s.id == studentId);
                        if (student) {
                            const studentItem = document.createElement('div');
                            studentItem.className = 'participant-item';
                            studentItem.innerHTML = `
                                <div class="flex-grow-1">
                                    <strong>${student.full_name || student.name}</strong>
                                    <div class="text-muted small">${student.student_id || 'N/A'} • ${student.course || 'N/A'} • ${student.year_level || 'N/A'}</div>
                                </div>
                            `;
                            studentsList.appendChild(studentItem);
                        }
                    });
                } else {
                    studentsList.innerHTML = '<div class="text-muted text-center py-3">No students assigned to this event.</div>';
                }
                
                const staffList = document.getElementById('detailStaffList');
                staffList.innerHTML = '';
                
                if (event.participants && event.participants.staff && event.participants.staff.length > 0) {
                    event.participants.staff.forEach(staffId => {
                        const staffMember = staff.find(s => s.id == staffId);
                        if (staffMember) {
                            const staffItem = document.createElement('div');
                            staffItem.className = 'participant-item';
                            staffItem.innerHTML = `
                                <div class="flex-grow-1">
                                    <strong>${staffMember.full_name || staffMember.name}</strong>
                                    <div class="text-muted small">${staffMember.department || 'N/A'}</div>
                                </div>
                            `;
                            staffList.appendChild(staffItem);
                        }
                    });
                } else {
                    staffList.innerHTML = '<div class="text-muted text-center py-3">No staff assigned to this event.</div>';
                }
            }

            function renderTimeVisualization(event) {
                timeSlots.innerHTML = '';
                
                for (let hour = 0; hour <= 24; hour++) {
                    const timeSlot = document.createElement('div');
                    timeSlot.className = 'time-slot';
                    
                    const timeLabel = document.createElement('div');
                    timeLabel.className = 'time-label';
                    timeLabel.textContent = formatHour(hour);
                    
                    const timeLine = document.createElement('div');
                    timeLine.className = 'time-line';
                    
                    const timeMarker = document.createElement('div');
                    timeMarker.className = 'time-marker';
                    timeLine.appendChild(timeMarker);
                    
                    timeSlot.appendChild(timeLabel);
                    timeSlot.appendChild(timeLine);
                    timeSlots.appendChild(timeSlot);
                    
                    if (event.startTime) {
                        const startHour = parseInt(event.startTime.split(':')[0]);
                        const startMinute = parseInt(event.startTime.split(':')[1]);
                        const endHour = parseInt(event.endTime.split(':')[0]);
                        const endMinute = parseInt(event.endTime.split(':')[1]);
                        
                        if ((startHour <= hour && endHour >= hour) || 
                            (startHour === hour && endHour === hour)) {
                            
                            const eventBlock = document.createElement('div');
                            eventBlock.className = `time-block ${event.type}`;
                            
                            let left = 0;
                            let width = 100;
                            
                            if (startHour === hour) {
                                left = (startMinute / 60) * 100;
                            }
                            
                            if (endHour === hour) {
                                width = (endMinute / 60) * 100 - left;
                            } else if (startHour === hour) {
                                width = 100 - left;
                            }
                            
                            eventBlock.style.left = `${left}%`;
                            eventBlock.style.width = `${width}%`;
                            eventBlock.textContent = event.title;
                            
                            timeLine.appendChild(eventBlock);
                        }
                    }
                }
            }

            function formatTime(timeString) {
                if (!timeString) return '';
                
                const [hours, minutes] = timeString.split(':');
                const hour = parseInt(hours);
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const formattedHour = hour % 12 || 12;
                
                return `${formattedHour}:${minutes} ${ampm}`;
            }

            function formatHour(hour) {
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const formattedHour = hour % 12 || 12;
                return `${formattedHour} ${ampm}`;
            }

            function renderCalendar() {
                const monthNames = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"];
                calendarTitle.textContent = `${monthNames[currentMonth]} ${currentYear}`;
                
                calendarGrid.innerHTML = '';
                
                const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                dayNames.forEach(day => {
                    const dayHeader = document.createElement('div');
                    dayHeader.className = 'calendar-day-header';
                    dayHeader.textContent = day;
                    calendarGrid.appendChild(dayHeader);
                });
                
                const firstDay = new Date(currentYear, currentMonth, 1).getDay();
                const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
                const daysInPrevMonth = new Date(currentYear, currentMonth, 0).getDate();
                
                for (let i = firstDay - 1; i >= 0; i--) {
                    const day = document.createElement('div');
                    day.className = 'calendar-day other-month';
                    day.innerHTML = `<div class="day-number">${daysInPrevMonth - i}</div>`;
                    calendarGrid.appendChild(day);
                }
                
                const today = new Date();
                for (let i = 1; i <= daysInMonth; i++) {
                    const day = document.createElement('div');
                    const date = new Date(currentYear, currentMonth, i);
                    
                    if (date.getDate() === today.getDate() && 
                        date.getMonth() === today.getMonth() && 
                        date.getFullYear() === today.getFullYear()) {
                        day.className = 'calendar-day today';
                    } else {
                        day.className = 'calendar-day';
                    }
                    
                    day.innerHTML = `<div class="day-number">${i}</div>`;
                    
                    const dayEvents = events.filter(event => {
                        const eventDate = new Date(event.date);
                        return eventDate.getDate() === i && 
                            eventDate.getMonth() === currentMonth && 
                            eventDate.getFullYear() === currentYear;
                    });
                    
                    if (dayEvents.length > 0) {
                        const eventIndicator = document.createElement('div');
                        eventIndicator.className = 'event-indicator';
                        
                        dayEvents.forEach(event => {
                            const eventItem = document.createElement('div');
                            eventItem.className = `event-item ${event.type} ${event.status === 'completed' ? 'completed' : ''}`;
                            eventItem.textContent = event.title;
                            eventItem.title = `${event.title}${event.startTime ? ' at ' + formatTime(event.startTime) : ''}`;
                            
                            eventItem.addEventListener('click', (e) => {
                                e.stopPropagation();
                                showEventDetails(event.id);
                            });
                            
                            eventIndicator.appendChild(eventItem);
                        });
                        
                        day.appendChild(eventIndicator);
                    }
                    
                    day.addEventListener('click', () => {
                        document.getElementById('editEventId').value = '';
                        document.getElementById('addEventForm').reset();
                        document.getElementById('eventDate').value = `${currentYear}-${(currentMonth + 1).toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`;
                        document.getElementById('computerLab').value = '';
                        document.getElementById('addEventModalLabel').textContent = 'Add New Event';
                        document.getElementById('saveEventBtn').textContent = 'Save Event';
                        
                        clearAllParticipants();
                        
                        new bootstrap.Modal(document.getElementById('addEventModal')).show();
                    });
                    
                    calendarGrid.appendChild(day);
                }
                
                const totalCells = 42;
                const daysSoFar = firstDay + daysInMonth;
                const daysNextMonth = totalCells - daysSoFar;
                
                for (let i = 1; i <= daysNextMonth; i++) {
                    const day = document.createElement('div');
                    day.className = 'calendar-day other-month';
                    day.innerHTML = `<div class="day-number">${i}</div>`;
                    calendarGrid.appendChild(day);
                }
            }

            function renderEventList() {
                eventList.innerHTML = '';
                
                const sortedEvents = [...events].sort((a, b) => new Date(a.date) - new Date(b.date));
                
                if (sortedEvents.length === 0) {
                    eventList.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <h4>No Events</h4>
                            <p>You haven't added any events yet.</p>
                            <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#addEventModal">
                                <i class="fas fa-plus"></i> Add First Event
                            </button>
                        </div>
                    `;
                    return;
                }
                
                sortedEvents.forEach(event => {
                    const eventDate = new Date(event.date);
                    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                    
                    const eventItem = document.createElement('div');
                    eventItem.className = 'event-list-item';
                    eventItem.addEventListener('click', () => {
                        showEventDetails(event.id);
                    });
                    
                    eventItem.innerHTML = `
                        <div class="event-date">
                            <div class="event-month">${monthNames[eventDate.getMonth()]}</div>
                            <div class="event-day">${eventDate.getDate()}</div>
                        </div>
                        <div class="event-details">
                            <div class="event-title">${event.title} ${event.status === 'completed' ? '<span class="badge bg-success ms-2">Completed</span>' : ''}</div>
                            <div class="event-time">${event.startTime ? formatTime(event.startTime) + ' - ' + formatTime(event.endTime) : 'All day'}</div>
                            <div class="event-description">${event.description}</div>
                            ${event.computerLab ? `<div class="event-lab"><small><i class="fas fa-desktop"></i> ${event.computerLab}</small></div>` : ''}
                        </div>
                        <div class="event-actions">
                            ${event.status !== 'completed' ? `
                            <button class="btn btn-success btn-sm" onclick="event.stopPropagation(); markEventComplete(${event.id})">
                                <i class="fas fa-check"></i>
                            </button>
                            ` : ''}
                            <button class="btn btn-warning btn-sm" onclick="event.stopPropagation(); editEvent(${event.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="event.stopPropagation(); deleteEvent(${event.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                    eventList.appendChild(eventItem);
                });
            }
            
            function saveEvent() {
                const eventTitle = document.getElementById('eventTitle').value;
                const eventDescription = document.getElementById('eventDescription').value;
                const eventDate = document.getElementById('eventDate').value;
                const eventTime = document.getElementById('eventTime').value;
                const eventStartTime = document.getElementById('eventStartTime').value;
                const eventEndTime = document.getElementById('eventEndTime').value;
                const eventType = document.getElementById('eventType').value;
                const computerLab = document.getElementById('computerLab').value;
                const editEventId = document.getElementById('editEventId').value;
                
                if (!eventTitle || !eventDate || !eventType || !eventTime) {
                    showToast('Please fill in all required fields.', 'error');
                    return;
                }
                    
                if (eventStartTime && eventEndTime) {
                    if (eventStartTime >= eventEndTime) {
                        showToast('End time must be after start time.', 'error');
                        return;
                    }
                }

                const participants = getSelectedParticipants();
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const saveBtn = document.getElementById('saveEventBtn');
                const originalText = saveBtn.innerHTML;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                saveBtn.disabled = true;

                const eventData = {
                    title: eventTitle,
                    date: eventDate,
                    startTime: eventStartTime,
                    endTime: eventEndTime,
                    computerLab: computerLab,
                    participants: participants
                };
                
                const conflicts = checkEventConflicts(eventData, editEventId);
                if (conflicts.length > 0 && !confirm(`This event has ${conflicts.length} potential conflict(s). Do you want to proceed anyway?`)) {
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                    return;
                }

                const isLocalEvent = editEventId && editEventId > 1000000;

                if (editEventId && isLocalEvent) {
                    const dateObj = new Date(eventDate);
                    const index = events.findIndex(e => e.id == editEventId);
                    if (index !== -1) {
                        events[index] = {
                            id: parseInt(editEventId),
                            title: eventTitle,
                            description: eventDescription,
                            date: dateObj,
                            time: eventTime,
                            startTime: eventStartTime,
                            endTime: eventEndTime,
                            type: eventType,
                            computerLab: computerLab,
                            participants: participants,
                            status: events[index].status || 'active'
                        };
                        localStorage.setItem('calendarEvents', JSON.stringify(events));
                        showToast('Event updated successfully!', 'success');
                        renderCalendar();
                        renderEventList();
                        bootstrap.Modal.getInstance(document.getElementById('addEventModal')).hide();
                    }
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                    return;
                }

                const formData = new FormData();
                formData.append('event_title', eventTitle);
                formData.append('description', eventDescription || '');
                formData.append('event_date', eventDate);
                formData.append('event_time', eventTime || '00:00');
                formData.append('event_start_time', eventStartTime || '00:00');
                formData.append('event_end_time', eventEndTime || '00:00');
                formData.append('event_type', eventType);
                formData.append('computer_lab', computerLab || '');
                formData.append('participants', JSON.stringify(participants));
                formData.append('_token', csrfToken);

                if (editEventId) {
                    formData.append('_method', 'PUT');
                }

                const url = editEventId ? `/admin/events/${editEventId}` : '/admin/events';

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Event saved successfully!', 'success');
                        
                        const dateObj = new Date(eventDate);
                        if (editEventId) {
                            const index = events.findIndex(e => e.id == editEventId);
                            if (index !== -1) {
                                events[index] = {
                                    id: parseInt(editEventId),
                                    title: eventTitle,
                                    description: eventDescription,
                                    date: dateObj,
                                    time: eventTime,
                                    startTime: eventStartTime,
                                    endTime: eventEndTime,
                                    type: eventType,
                                    computerLab: computerLab,
                                    participants: participants,
                                    status: events[index].status || 'active'
                                };
                            }
                        } else {
                            const newEvent = {
                                id: data.data.id,
                                title: eventTitle,
                                description: eventDescription,
                                date: dateObj,
                                time: eventTime,
                                startTime: eventStartTime,
                                endTime: eventEndTime,
                                type: eventType,
                                computerLab: computerLab,
                                participants: participants,
                                status: 'active'
                            };
                            events.push(newEvent);
                        }
                        
                        localStorage.setItem('calendarEvents', JSON.stringify(events));
                        
                        renderCalendar();
                        renderEventList();
                        
                        bootstrap.Modal.getInstance(document.getElementById('addEventModal')).hide();
                    } else {
                        if (data.errors) {
                            const errorMessages = Object.values(data.errors).flat().join(', ');
                            showToast('Validation errors: ' + errorMessages, 'error');
                        } else {
                            showToast(data.message || 'Error saving event', 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    if (error.errors) {
                        const errorMessages = Object.values(error.errors).flat().join(', ');
                        showToast('Validation errors: ' + errorMessages, 'error');
                    } else {
                        const dateObj = new Date(eventDate);
                        if (editEventId) {
                            const index = events.findIndex(e => e.id == editEventId);
                            if (index !== -1) {
                                events[index] = {
                                    id: parseInt(editEventId),
                                    title: eventTitle,
                                    description: eventDescription,
                                    date: dateObj,
                                    time: eventTime,
                                    startTime: eventStartTime,
                                    endTime: eventEndTime,
                                    type: eventType,
                                    computerLab: computerLab,
                                    participants: participants,
                                    status: events[index].status || 'active'
                                };
                                localStorage.setItem('calendarEvents', JSON.stringify(events));
                                showToast('Event updated in local storage!', 'success');
                            }
                        } else {
                            const newId = events.length > 0 ? Math.max(...events.map(e => e.id)) + 1 : 1;
                            events.push({
                                id: newId,
                                title: eventTitle,
                                description: eventDescription,
                                date: dateObj,
                                time: eventTime,
                                startTime: eventStartTime,
                                endTime: eventEndTime,
                                type: eventType,
                                computerLab: computerLab,
                                participants: participants,
                                status: 'active'
                            });
                            localStorage.setItem('calendarEvents', JSON.stringify(events));
                            showToast('Event added to local storage!', 'success');
                        }
                        renderCalendar();
                        renderEventList();
                        bootstrap.Modal.getInstance(document.getElementById('addEventModal')).hide();
                    }
                })
                .finally(() => {
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                });
            }

            function editEvent(id) {
                const localEvent = events.find(e => e.id === id);
                if (localEvent) {
                    const eventDate = new Date(localEvent.date);
                    const formattedDate = `${eventDate.getFullYear()}-${(eventDate.getMonth() + 1).toString().padStart(2, '0')}-${eventDate.getDate().toString().padStart(2, '0')}`;
                    
                    document.getElementById('editEventId').value = localEvent.id;
                    document.getElementById('eventTitle').value = localEvent.title;
                    document.getElementById('eventDescription').value = localEvent.description;
                    document.getElementById('eventDate').value = formattedDate;
                    document.getElementById('eventTime').value = localEvent.time;
                    document.getElementById('eventStartTime').value = localEvent.startTime;
                    document.getElementById('eventEndTime').value = localEvent.endTime;
                    document.getElementById('eventType').value = localEvent.type;
                    document.getElementById('computerLab').value = localEvent.computerLab || '';
                    
                    if (localEvent.participants) {
                        setSelectedParticipants(localEvent.participants.students || [], localEvent.participants.staff || []);
                    }
                    
                    document.getElementById('addEventModalLabel').textContent = 'Edit Event';
                    document.getElementById('saveEventBtn').textContent = 'Update Event';
                    
                    const eventData = {
                        title: localEvent.title,
                        date: formattedDate,
                        startTime: localEvent.startTime,
                        endTime: localEvent.endTime,
                        computerLab: localEvent.computerLab,
                        participants: localEvent.participants || { students: [], staff: [] }
                    };
                    
                    const conflicts = checkEventConflicts(eventData, localEvent.id);
                    showConflictWarning(conflicts);
                    
                    new bootstrap.Modal(document.getElementById('addEventModal')).show();
                } else {
                    fetch(`/admin/events/${id}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const event = data.data;
                                const eventDate = new Date(event.event_date);
                                const formattedDate = `${eventDate.getFullYear()}-${(eventDate.getMonth() + 1).toString().padStart(2, '0')}-${eventDate.getDate().toString().padStart(2, '0')}`;
                                
                                document.getElementById('editEventId').value = event.id;
                                document.getElementById('eventTitle').value = event.event_title;
                                document.getElementById('eventDescription').value = event.description;
                                document.getElementById('eventDate').value = formattedDate;
                                document.getElementById('eventTime').value = event.event_time;
                                document.getElementById('eventStartTime').value = event.event_start_time;
                                document.getElementById('eventEndTime').value = event.event_end_time;
                                document.getElementById('eventType').value = event.event_type;
                                document.getElementById('computerLab').value = event.computer_lab || '';
                                
                                if (event.participants) {
                                    const participants = JSON.parse(event.participants);
                                    setSelectedParticipants(participants.students || [], participants.staff || []);
                                }
                                
                                document.getElementById('addEventModalLabel').textContent = 'Edit Event';
                                document.getElementById('saveEventBtn').textContent = 'Update Event';
                                new bootstrap.Modal(document.getElementById('addEventModal')).show();
                            } else {
                                showToast('Event not found', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching event:', error);
                            showToast('Error loading event data', 'error');
                        });
                }
            }

            function archiveStudent(id) {
                const students = Database.getStudents();
                const student = students.find(s => s.id == id);
                
                if (student) {
                    const archive = JSON.parse(localStorage.getItem('archive') || '{"students": [], "staff": [], "events": [], "reservations": []}');
                    
                    archive.students.push({
                        ...student,
                        archived_at: new Date().toISOString()
                    });
                    
                    const updatedStudents = students.filter(s => s.id != id);
                    localStorage.setItem('students', JSON.stringify(updatedStudents));
                    localStorage.setItem('archive', JSON.stringify(archive));
                    
                    showToast('Student archived successfully!', 'success');
                    
                    renderStudentTable(updatedStudents);
                    updateCharts();
                    
                    addRecentActivity('Student Archived', `Archived ${student.full_name} (${student.student_id})`);
                }
            }

            function archiveStaff(id) {
                const staff = Database.getStaff();
                const staffMember = staff.find(s => s.id == id);
                
                if (staffMember) {
                    const archive = JSON.parse(localStorage.getItem('archive') || '{"students": [], "staff": [], "events": [], "reservations": []}');
                    
                    archive.staff.push({
                        ...staffMember,
                        archived_at: new Date().toISOString()
                    });
                    
                    const updatedStaff = staff.filter(s => s.id != id);
                    localStorage.setItem('staff', JSON.stringify(updatedStaff));
                    localStorage.setItem('archive', JSON.stringify(archive));
                    
                    showToast('Staff archived successfully!', 'success');
                    
                    renderStaffTable(updatedStaff);
                    updateCharts();
                    
                    addRecentActivity('Staff Archived', `Archived ${staffMember.full_name}`);
                }
            }

            function archiveEvent(id) {
                const event = events.find(e => e.id == id);
                
                if (event) {
                    const archive = JSON.parse(localStorage.getItem('archive') || '{"students": [], "staff": [], "events": [], "reservations": []}');
                    
                    archive.events.push({
                        ...event,
                        archived_at: new Date().toISOString()
                    });
                    
                    events = events.filter(e => e.id != id);
                    localStorage.setItem('calendarEvents', JSON.stringify(events));
                    localStorage.setItem('archive', JSON.stringify(archive));
                    
                    showToast('Event archived successfully!', 'success');
                    renderCalendar();
                    renderEventList();
                    
                    addRecentActivity('Event Archived', `Archived ${event.title}`);
                }
            }

            function archiveReservation(id) {
                const reservations = Database.getReservations();
                const reservation = reservations.find(r => r.id == id);
                
                if (reservation) {
                    const archive = JSON.parse(localStorage.getItem('archive') || '{"students": [], "staff": [], "events": [], "reservations": []}');
                    
                    archive.reservations.push({
                        ...reservation,
                        archived_at: new Date().toISOString()
                    });
                    
                    const updatedReservations = reservations.filter(r => r.id != id);
                    localStorage.setItem('reservations', JSON.stringify(updatedReservations));
                    localStorage.setItem('archive', JSON.stringify(archive));
                    
                    showToast('Reservation archived successfully!', 'success');
                    loadReservationData();
                    
                    addRecentActivity('Reservation Archived', `Archived ${reservation.title}`);
                }
            }

            function deleteEvent(id) {
                if (confirm('Are you sure you want to delete this event? It will be moved to archive.')) {
                    archiveEvent(id);
                }
            }

            function loadReservationData() {
                const reservations = Database.getReservations();
                renderReservationTable(reservations);
            }

            function renderReservationTable(reservations) {
                const tableBody = document.getElementById('reservationTableBody');
                const emptyState = document.getElementById('noReservationMessage');
                
                if (!reservations || reservations.length === 0) {
                    tableBody.innerHTML = '';
                    emptyState.style.display = 'block';
                    return;
                }
                
                emptyState.style.display = 'none';
                tableBody.innerHTML = '';
                
                reservations.forEach((reservation, index) => {
                    const newRow = document.createElement('tr');
                    const statusClass = `reservation-status-${reservation.status}`;
                    const statusText = reservation.status.charAt(0).toUpperCase() + reservation.status.slice(1);
                    
                    newRow.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${reservation.title}</td>
                        <td><span class="lab-badge">${reservation.lab}</span></td>
                        <td>
                            <div>${new Date(reservation.date).toLocaleDateString()}</div>
                            <small class="text-muted">${formatTime(reservation.startTime)} - ${formatTime(reservation.endTime)}</small>
                        </td>
                        <td>${reservation.requester}</td>
                        <td>${reservation.purpose.substring(0, 50)}${reservation.purpose.length > 50 ? '...' : ''}</td>
                        <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-info btn-action edit-reservation" data-id="${reservation.id}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-action archive-reservation" data-id="${reservation.id}">
                                    <i class="fas fa-trash"></i> Archive
                                </button>
                                ${reservation.status === 'pending' ? `
                                <button class="btn btn-success btn-action approve-reservation" data-id="${reservation.id}">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button class="btn btn-warning btn-action reject-reservation" data-id="${reservation.id}">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                                ` : ''}
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(newRow);
                });
                
                attachReservationEventListeners();
            }

            function saveReservation() {
                const title = document.getElementById('reservationTitle').value;
                const lab = document.getElementById('reservationLab').value;
                const purpose = document.getElementById('reservationPurpose').value;
                const date = document.getElementById('reservationDate').value;
                const requester = document.getElementById('reservationRequester').value;
                const startTime = document.getElementById('reservationStartTime').value;
                const endTime = document.getElementById('reservationEndTime').value;
                const participants = document.getElementById('reservationParticipants').value;
                const requirements = document.getElementById('reservationRequirements').value;
                const editId = document.getElementById('editReservationId').value;
                
                if (!title || !lab || !purpose || !date || !requester || !startTime || !endTime) {
                    showToast('Please fill all required fields!', 'error');
                    return;
                }
                
                if (startTime >= endTime) {
                    showToast('End time must be after start time!', 'error');
                    return;
                }

                const saveBtn = document.getElementById('saveReservationBtn');
                const originalText = saveBtn.innerHTML;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                saveBtn.disabled = true;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const formData = new FormData();
                formData.append('title', title);
                formData.append('lab', lab);
                formData.append('purpose', purpose);
                formData.append('date', date);
                formData.append('requester', requester);
                formData.append('start_time', startTime);
                formData.append('end_time', endTime);
                formData.append('participants', participants || 0);
                formData.append('requirements', requirements || '');
                formData.append('status', 'pending');
                formData.append('_token', csrfToken);

                if (editId) {
                    formData.append('_method', 'PUT');
                }

                const url = editId ? `/admin/reservations/${editId}` : '/admin/reservations';

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Reservation saved successfully!', 'success');
                        
                        const reservations = Database.getReservations();
                        
                        if (editId) {
                            const index = reservations.findIndex(r => r.id == editId);
                            if (index !== -1) {
                                reservations[index] = {
                                    ...reservations[index],
                                    title: title,
                                    lab: lab,
                                    purpose: purpose,
                                    date: date,
                                    requester: requester,
                                    startTime: startTime,
                                    endTime: endTime,
                                    participants: participants,
                                    requirements: requirements,
                                    updated_at: new Date().toISOString()
                                };
                            }
                        } else {
                            const newReservation = {
                                id: data.data.id,
                                title: title,
                                lab: lab,
                                purpose: purpose,
                                date: date,
                                requester: requester,
                                startTime: startTime,
                                endTime: endTime,
                                participants: participants,
                                requirements: requirements,
                                status: 'pending',
                                created_at: new Date().toISOString()
                            };
                            reservations.push(newReservation);
                            
                            const events = JSON.parse(localStorage.getItem('calendarEvents')) || [];
                            events.push({
                                id: Date.now(),
                                title: `Lab Reservation: ${title}`,
                                description: `Laboratory: ${lab}\nRequester: ${requester}\nPurpose: ${purpose}`,
                                date: new Date(date),
                                startTime: startTime,
                                endTime: endTime,
                                type: 'class',
                                computerLab: lab,
                                status: 'active'
                            });
                            localStorage.setItem('calendarEvents', JSON.stringify(events));
                        }
                        
                        localStorage.setItem('reservations', JSON.stringify(reservations));
                        
                        loadReservationData();
                        renderCalendar();
                        
                        bootstrap.Modal.getInstance(document.getElementById('addReservationModal')).hide();
                        
                        if (editId) {
                            addRecentActivity('Reservation Updated', `Updated ${title} reservation`);
                        } else {
                            addRecentActivity('New Reservation', `Created ${title} for ${lab}`);
                        }
                    } else {
                        if (data.errors) {
                            const errorMessages = Object.values(data.errors).flat().join(', ');
                            showToast('Validation errors: ' + errorMessages, 'error');
                        } else {
                            showToast(data.message || 'Error saving reservation', 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    if (error.errors) {
                        const errorMessages = Object.values(error.errors).flat().join(', ');
                        showToast('Validation errors: ' + errorMessages, 'error');
                    } else {
                        const reservations = Database.getReservations();
                        
                        if (editId) {
                            const index = reservations.findIndex(r => r.id == editId);
                            if (index !== -1) {
                                reservations[index] = {
                                    ...reservations[index],
                                    title: title,
                                    lab: lab,
                                    purpose: purpose,
                                    date: date,
                                    requester: requester,
                                    startTime: startTime,
                                    endTime: endTime,
                                    participants: participants,
                                    requirements: requirements,
                                    updated_at: new Date().toISOString()
                                };
                                localStorage.setItem('reservations', JSON.stringify(reservations));
                                showToast('Reservation updated in local storage!', 'success');
                            }
                        } else {
                            const newId = reservations.length > 0 ? Math.max(...reservations.map(r => r.id)) + 1 : 1;
                            reservations.push({
                                id: newId,
                                title: title,
                                lab: lab,
                                purpose: purpose,
                                date: date,
                                requester: requester,
                                startTime: startTime,
                                endTime: endTime,
                                participants: participants,
                                requirements: requirements,
                                status: 'pending',
                                created_at: new Date().toISOString()
                            });
                            localStorage.setItem('reservations', JSON.stringify(reservations));
                            
                            const events = JSON.parse(localStorage.getItem('calendarEvents')) || [];
                            events.push({
                                id: Date.now(),
                                title: `Lab Reservation: ${title}`,
                                description: `Laboratory: ${lab}\nRequester: ${requester}\nPurpose: ${purpose}`,
                                date: new Date(date),
                                startTime: startTime,
                                endTime: endTime,
                                type: 'class',
                                computerLab: lab,
                                status: 'active'
                            });
                            localStorage.setItem('calendarEvents', JSON.stringify(events));
                            
                            showToast('Reservation added to local storage!', 'success');
                        }
                        
                        loadReservationData();
                        renderCalendar();
                        bootstrap.Modal.getInstance(document.getElementById('addReservationModal')).hide();
                        
                        if (editId) {
                            addRecentActivity('Reservation Updated', `Updated ${title} reservation`);
                        } else {
                            addRecentActivity('New Reservation', `Created ${title} for ${lab}`);
                        }
                    }
                })
                .finally(() => {
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                });
            }

            function editReservation(id) {
                const reservations = Database.getReservations();
                const reservation = reservations.find(r => r.id == id);
                
                if (reservation) {
                    document.getElementById('editReservationId').value = reservation.id;
                    document.getElementById('reservationTitle').value = reservation.title;
                    document.getElementById('reservationLab').value = reservation.lab;
                    document.getElementById('reservationPurpose').value = reservation.purpose;
                    document.getElementById('reservationDate').value = reservation.date;
                    document.getElementById('reservationRequester').value = reservation.requester;
                    document.getElementById('reservationStartTime').value = reservation.startTime;
                    document.getElementById('reservationEndTime').value = reservation.endTime;
                    document.getElementById('reservationParticipants').value = reservation.participants || '';
                    document.getElementById('reservationRequirements').value = reservation.requirements || '';
                    
                    document.getElementById('addReservationModalLabel').innerHTML = '<i class="fas fa-edit"></i> Edit Reservation';
                    document.getElementById('saveReservationBtn').textContent = 'Update Reservation';
                    
                    const modal = new bootstrap.Modal(document.getElementById('addReservationModal'));
                    modal.show();
                }
            }

            

            function updateReservationStatus(id, status) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch(`/admin/reservations/${id}/${status}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        admin_notes: `${status} by administrator`
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast(`Reservation ${status} successfully!`, 'success');
                        
                        const reservations = Database.getReservations();
                        const reservation = reservations.find(r => r.id == id);
                        if (reservation) {
                            reservation.status = status;
                            localStorage.setItem('reservations', JSON.stringify(reservations));
                        }
                        
                        loadReservationData();
                        
                        const reservationTitle = reservation ? reservation.title : 'Reservation';
                        addRecentActivity('Reservation Status Updated', `${reservationTitle} ${status}`);
                    } else {
                        showToast(data.message || `Error ${status} reservation`, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast(`Failed to ${status} reservation`, 'error');
                    
                    const reservations = Database.getReservations();
                    const reservation = reservations.find(r => r.id == id);
                    if (reservation) {
                        reservation.status = status;
                        localStorage.setItem('reservations', JSON.stringify(reservations));
                        loadReservationData();
                        showToast(`Reservation ${status} in local storage!`, 'success');
                    }
                });
            }

            function loadArchiveData() {
                const archive = JSON.parse(localStorage.getItem('archive') || '{"students": [], "staff": [], "events": [], "reservations": []}');
                renderArchiveTable(archive);
            }

            function renderArchiveTable(archive) {
                const tableBody = document.getElementById('archiveTableBody');
                const emptyState = document.getElementById('noArchiveMessage');
                
                const allArchivedItems = [
                    ...archive.students.map(item => ({ ...item, type: 'student' })),
                    ...archive.staff.map(item => ({ ...item, type: 'staff' })),
                    ...archive.events.map(item => ({ ...item, type: 'event' })),
                    ...archive.reservations.map(item => ({ ...item, type: 'reservation' }))
                ];
                
                const searchTerm = document.getElementById('archiveSearch').value.toLowerCase();
                const typeFilter = document.getElementById('archiveTypeFilter').value;
                const dateFilter = document.getElementById('archiveDateFilter').value;
                
                const filteredItems = allArchivedItems.filter(item => {
                    const matchesSearch = !searchTerm || 
                        (item.full_name || item.name || item.title || '').toLowerCase().includes(searchTerm) ||
                        (item.student_id || '').toLowerCase().includes(searchTerm) ||
                        (item.email || '').toLowerCase().includes(searchTerm);
                    
                    const matchesType = !typeFilter || item.type === typeFilter;
                    
                    const matchesDate = !dateFilter || filterByDate(item.archived_at, dateFilter);
                    
                    return matchesSearch && matchesType && matchesDate;
                });
                
                if (filteredItems.length === 0) {
                    tableBody.innerHTML = '';
                    emptyState.style.display = 'block';
                    return;
                }
                
                emptyState.style.display = 'none';
                tableBody.innerHTML = '';
                
                filteredItems.sort((a, b) => new Date(b.archived_at) - new Date(a.archived_at));
                
                filteredItems.forEach((item, index) => {
                    const newRow = document.createElement('tr');
                    
                    let name = item.full_name || item.name || item.title || 'Unknown';
                    let details = '';
                    let typeBadge = '';
                    
                    switch(item.type) {
                        case 'student':
                            details = `${item.student_id || 'N/A'} • ${item.course || 'N/A'} • ${item.year_level || 'N/A'}`;
                            typeBadge = '<span class="badge bg-primary">Student</span>';
                            break;
                        case 'staff':
                            details = `${item.department || 'N/A'} • ${item.email || 'N/A'}`;
                            typeBadge = '<span class="badge bg-info">Staff</span>';
                            break;
                        case 'event':
                            details = `${item.type || 'N/A'} • ${new Date(item.date).toLocaleDateString()}`;
                            typeBadge = '<span class="badge bg-warning">Event</span>';
                            break;
                        case 'reservation':
                            details = `${item.lab || 'N/A'} • ${item.requester || 'N/A'}`;
                            typeBadge = '<span class="badge bg-success">Reservation</span>';
                            break;
                    }
                    
                    newRow.innerHTML = `
                        <td>${index + 1}</td>
                        <td>
                            <div class="fw-semibold">${name}</div>
                            ${details ? `<small class="text-muted">${details}</small>` : ''}
                        </td>
                        <td>${typeBadge}</td>
                        <td>${getArchiveItemDetails(item)}</td>
                        <td>${new Date(item.archived_at).toLocaleString()}</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-success btn-action restore-archive" data-type="${item.type}" data-id="${item.id}">
                                    <i class="fas fa-undo"></i> Restore
                                </button>
                                <button class="btn btn-danger btn-action permanent-delete" data-type="${item.type}" data-id="${item.id}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(newRow);
                });
                
                attachArchiveEventListeners();
            }

            function getArchiveItemDetails(item) {
                switch(item.type) {
                    case 'student':
                        return `Course: ${item.course || 'N/A'}, Year: ${item.year_level || 'N/A'}`;
                    case 'staff':
                        return `Department: ${item.department || 'N/A'}`;
                    case 'event':
                        return `Date: ${new Date(item.date).toLocaleDateString()}, Type: ${item.type || 'N/A'}`;
                    case 'reservation':
                        return `Lab: ${item.lab || 'N/A'}, Status: ${item.status || 'N/A'}`;
                    default:
                        return 'No details available';
                }
            }

            function filterByDate(archiveDate, filter) {
                const date = new Date(archiveDate);
                const today = new Date();
                
                switch(filter) {
                    case 'today':
                        return date.toDateString() === today.toDateString();
                    case 'week':
                        const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                        return date >= weekAgo;
                    case 'month':
                        const monthAgo = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
                        return date >= monthAgo;
                    default:
                        return true;
                }
            }

            function restoreArchiveItem(type, id) {
                const archive = JSON.parse(localStorage.getItem('archive') || '{"students": [], "staff": [], "events": [], "reservations": []}');
                const item = archive[type + 's'].find(i => i.id == id);
                
                if (item) {
                    archive[type + 's'] = archive[type + 's'].filter(i => i.id != id);
                    
                    switch(type) {
                        case 'student':
                            const students = Database.getStudents();
                            students.push(item);
                            localStorage.setItem('students', JSON.stringify(students));
                            break;
                        case 'staff':
                            const staff = Database.getStaff();
                            staff.push(item);
                            localStorage.setItem('staff', JSON.stringify(staff));
                            break;
                        case 'event':
                            const events = JSON.parse(localStorage.getItem('calendarEvents')) || [];
                            events.push(item);
                            localStorage.setItem('calendarEvents', JSON.stringify(events));
                            break;
                        case 'reservation':
                            const reservations = Database.getReservations();
                            reservations.push(item);
                            localStorage.setItem('reservations', JSON.stringify(reservations));
                            break;
                    }
                    
                    localStorage.setItem('archive', JSON.stringify(archive));
                    showToast('Item restored successfully!', 'success');
                    loadArchiveData();
                    
                    if (type === 'student' && document.getElementById('manage-students').classList.contains('active')) {
                        loadStudentDataFromServer();
                    } else if (type === 'staff' && document.getElementById('manage-staff').classList.contains('active')) {
                        loadStaffDataFromServer();
                    } else if (type === 'event' && document.getElementById('calendar').classList.contains('active')) {
                        renderCalendar();
                    } else if (type === 'reservation' && document.getElementById('reservation').classList.contains('active')) {
                        loadReservationData();
                    }
                }
            }

            function permanentDeleteItem(type, id) {
                if (confirm('Are you sure you want to permanently delete this item? This action cannot be undone.')) {
                    const archive = JSON.parse(localStorage.getItem('archive') || '{"students": [], "staff": [], "events": [], "reservations": []}');
                    
                    archive[type + 's'] = archive[type + 's'].filter(i => i.id != id);
                    localStorage.setItem('archive', JSON.stringify(archive));
                    
                    showToast('Item permanently deleted!', 'success');
                    loadArchiveData();
                }
            }

            function emptyArchive() {
                if (confirm('Are you sure you want to empty the entire archive? This will permanently delete all archived items and cannot be undone.')) {
                    localStorage.setItem('archive', JSON.stringify({
                        students: [],
                        staff: [],
                        events: [],
                        reservations: []
                    }));
                    
                    showToast('Archive emptied successfully!', 'success');
                    loadArchiveData();
                }
            }

            function generateStudentReport() {
                const students = Database.getStudents();
                
                if (students.length === 0) {
                    showToast('No student data available for report', 'error');
                    return;
                }
                
                let csvContent = "Student ID,Full Name,Email,Course,Section,Year Level,Phone,Address\n";
                
                students.forEach(student => {
                    csvContent += `"${student.student_id || ''}","${student.full_name || ''}","${student.email || ''}","${student.course || ''}","${student.section || ''}","${student.year_level || ''}","${student.phone || ''}","${student.address || ''}"\n`;
                });
                
                downloadCSV(csvContent, 'student_report.csv');
                showToast('Student report generated successfully!', 'success');
            }

            function generateStaffReport() {
                const staff = Database.getStaff();
                
                if (staff.length === 0) {
                    showToast('No staff data available for report', 'error');
                    return;
                }
                
                let csvContent = "Full Name,Email,Department,Phone,Address\n";
                
                staff.forEach(staffMember => {
                    csvContent += `"${staffMember.full_name || ''}","${staffMember.email || ''}","${staffMember.department || ''}","${staffMember.phone || ''}","${staffMember.address || ''}"\n`;
                });
                
                downloadCSV(csvContent, 'staff_report.csv');
                showToast('Staff report generated successfully!', 'success');
            }

            function generateEventReport() {
                const events = JSON.parse(localStorage.getItem('calendarEvents')) || [];
                
                if (events.length === 0) {
                    showToast('No event data available for report', 'error');
                    return;
                }
                
                let csvContent = "Event Title,Description,Date,Start Time,End Time,Type,Computer Lab,Status\n";
                
                events.forEach(event => {
                    const eventDate = new Date(event.date);
                    csvContent += `"${event.title || ''}","${event.description || ''}","${eventDate.toLocaleDateString()}","${event.startTime || ''}","${event.endTime || ''}","${event.type || ''}","${event.computerLab || ''}","${event.status || 'active'}"\n`;
                });
                
                downloadCSV(csvContent, 'event_report.csv');
                showToast('Event report generated successfully!', 'success');
            }

            function generateReservationReport() {
                const reservations = Database.getReservations();
                
                if (reservations.length === 0) {
                    showToast('No reservation data available for report', 'error');
                    return;
                }
                
                let csvContent = "Title,Lab,Purpose,Date,Start Time,End Time,Requester,Participants,Requirements,Status\n";
                
                reservations.forEach(reservation => {
                    const reservationDate = new Date(reservation.date);
                    csvContent += `"${reservation.title || ''}","${reservation.lab || ''}","${reservation.purpose || ''}","${reservationDate.toLocaleDateString()}","${reservation.startTime || ''}","${reservation.endTime || ''}","${reservation.requester || ''}","${reservation.participants || ''}","${reservation.requirements || ''}","${reservation.status || 'pending'}"\n`;
                });
                
                downloadCSV(csvContent, 'reservation_report.csv');
                showToast('Reservation report generated successfully!', 'success');
            }

            function downloadCSV(csvContent, filename) {
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                
                if (link.download !== undefined) {
                    const url = URL.createObjectURL(blob);
                    link.setAttribute('href', url);
                    link.setAttribute('download', filename);
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            }

            function loadStaffDataFromServer() {
                console.log('Loading staff data...');
                
                const localStaff = Database.getStaff();
                console.log('Local staff data:', localStaff);
                
                if (localStaff && localStaff.length > 0) {
                    renderStaffTable(localStaff);
                    updateCharts();
                } else {
                    fetch('/admin/admin/api/manage-staff')
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            console.log('Staff API Response:', data);
                            
                            let staffData = [];
                            if (data.success && data.data) {
                                staffData = data.data;
                            } else if (Array.isArray(data)) {
                                staffData = data;
                            }
                            
                            if (staffData.length > 0) {
                                localStorage.setItem('staff', JSON.stringify(staffData));
                            }
                            
                            renderStaffTable(staffData);
                            updateCharts();
                        })
                        .catch(error => {
                            console.error('Staff API failed:', error);
                            renderStaffTable([]);
                        });
                }
            }

            function loadStudentDataFromServer() {
                console.log('Loading student data...');
                
                const localStudents = Database.getStudents();
                console.log('Local student data:', localStudents);
                
                if (localStudents && localStudents.length > 0) {
                    renderStudentTable(localStudents);
                    updateCharts();
                } else {
                    fetch('/admin/admin/api/manage-students')
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            console.log('Student API Response:', data);
                            
                            let studentData = [];
                            if (data.success && data.data) {
                                studentData = data.data;
                            } else if (Array.isArray(data)) {
                                studentData = data;
                            }
                            
                            if (studentData.length > 0) {
                                localStorage.setItem('students', JSON.stringify(studentData));
                            }
                            
                            renderStudentTable(studentData);
                            updateCharts();
                        })
                        .catch(error => {
                            console.error('Student API failed:', error);
                            renderStudentTable([]);
                        });
                }
            }

            function loadDashboardStats() {
                console.log('Loading dashboard stats...');
                
                const staff = Database.getStaff();
                const students = Database.getStudents();
                
                updateCharts();
                
                Promise.all([
                    fetch('/admin/admin/api/manage-staff').then(res => res.ok ? res.json() : Promise.reject(res)).catch(() => null),
                    fetch('/admin/admin/api/manage-students').then(res => res.ok ? res.json() : Promise.reject(res)).catch(() => null)
                ])
                .then(([staffData, studentData]) => {
                    if (staffData) {
                        const staffCount = staffData.success ? staffData.data.length : (Array.isArray(staffData) ? staffData.length : 0);
                        if (staffCount > 0) {
                            localStorage.setItem('staff', JSON.stringify(staffData.success ? staffData.data : staffData));
                        }
                    }
                    if (studentData) {
                        const studentCount = studentData.success ? studentData.data.length : (Array.isArray(studentData) ? studentData.length : 0);
                        if (studentCount > 0) {
                            localStorage.setItem('students', JSON.stringify(studentData.success ? studentData.data : studentData));
                        }
                    }
                    updateCharts();
                })
                .catch(error => {
                    console.log('Using localStorage data for dashboard');
                });
            }

            function renderStaffTable(staff) {
                const tableBody = document.getElementById('staffTableBody');
                const emptyState = document.getElementById('noStaffMessage');
                
                console.log('Rendering staff table with:', staff);
                
                if (!staff || staff.length === 0) {
                    tableBody.innerHTML = '';
                    emptyState.style.display = 'block';
                    return;
                }
                
                emptyState.style.display = 'none';
                tableBody.innerHTML = '';
                
                staff.forEach((staffMember, index) => {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${staffMember.full_name || 'N/A'}</td>
                        <td>${staffMember.email || 'N/A'}</td>
                        <td>${staffMember.department || 'N/A'}</td>
                        <td>${staffMember.phone || 'N/A'}</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-info btn-action edit-staff" data-id="${staffMember.id}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-action delete-staff" data-id="${staffMember.id}">
                                    <i class="fas fa-trash"></i> Archive
                                </button>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(newRow);
                });
                
                attachStaffEventListeners();
            }

            function renderStudentTable(students) {
                const tableBody = document.getElementById('studentTableBody');
                const emptyState = document.getElementById('noStudentMessage');
                
                console.log('Rendering student table with:', students);
                
                if (!students || students.length === 0) {
                    tableBody.innerHTML = '';
                    emptyState.style.display = 'block';
                    return;
                }
                
                emptyState.style.display = 'none';
                tableBody.innerHTML = '';
                
                students.forEach((student, index) => {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${student.student_id || 'N/A'}</td>
                        <td>${student.full_name || 'N/A'}</td>
                        <td>${student.email || 'N/A'}</td>
                        <td>${student.course || 'N/A'}</td>
                        <td>${student.section || 'N/A'}</td>
                        <td>${student.year_level || 'N/A'}</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-info btn-action edit-student" data-id="${student.id}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-action delete-student" data-id="${student.id}">
                                    <i class="fas fa-trash"></i> Archive
                                </button>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(newRow);
                });
                
                attachStudentEventListeners();
            }

            function editStaff(id) {
                const staff = Database.getStaff();
                const staffMember = staff.find(s => s.id == id);
                
                if (staffMember) {
                    document.getElementById('editStaffId').value = staffMember.id;
                    document.getElementById('staffName').value = staffMember.full_name || '';
                    document.getElementById('staffEmail').value = staffMember.email || '';
                    document.getElementById('staffDepartment').value = staffMember.department || '';
                    document.getElementById('staffPhone').value = staffMember.phone || '';
                    document.getElementById('staffAddress').value = staffMember.address || '';
                    
                    document.getElementById('addStaffModalLabel').innerHTML = '<i class="fas fa-edit"></i> Edit Staff Member';
                    document.getElementById('saveStaffBtn').textContent = 'Update Staff';
                    
                    const modal = new bootstrap.Modal(document.getElementById('addStaffModal'));
                    modal.show();
                }
            }

            function editStudent(id) {
                const students = Database.getStudents();
                const student = students.find(s => s.id == id);
                
                if (student) {
                    document.getElementById('editStudentId').value = student.id;
                    document.getElementById('studentId').value = student.student_id || '';
                    document.getElementById('studentName').value = student.full_name || '';
                    document.getElementById('studentEmail').value = student.email || '';
                    document.getElementById('studentCourse').value = student.course || '';
                    document.getElementById('studentSection').value = student.section || '';
                    document.getElementById('studentYearLevel').value = student.year_level || '';
                    document.getElementById('studentPhone').value = student.phone || '';
                    document.getElementById('studentAddress').value = student.address || '';
                    
                    document.getElementById('addStudentModalLabel').innerHTML = '<i class="fas fa-edit"></i> Edit Student';
                    document.getElementById('saveStudentBtn').textContent = 'Update Student';
                    
                    const modal = new bootstrap.Modal(document.getElementById('addStudentModal'));
                    modal.show();
                }
            }

            function resetStaffModal() {
                document.getElementById('editStaffId').value = '';
                document.getElementById('addStaffForm').reset();
                document.getElementById('addStaffModalLabel').innerHTML = '<i class="fas fa-user-plus"></i> Add New Staff Member';
                document.getElementById('saveStaffBtn').textContent = 'Save Staff Member';
            }

            function resetStudentModal() {
                document.getElementById('editStudentId').value = '';
                document.getElementById('addStudentForm').reset();
                document.getElementById('addStudentModalLabel').innerHTML = '<i class="fas fa-user-graduate"></i> Add New Student';
                document.getElementById('saveStudentBtn').textContent = 'Save Student';
            }

            function resetReservationModal() {
                document.getElementById('editReservationId').value = '';
                document.getElementById('addReservationForm').reset();
                document.getElementById('addReservationModalLabel').innerHTML = '<i class="fas fa-desktop"></i> New Laboratory Reservation';
                document.getElementById('saveReservationBtn').textContent = 'Save Reservation';
            }

            function navigateToPage(pageId) {
                document.querySelectorAll('.page-content').forEach(page => {
                    page.classList.remove('active');
                });
                
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.classList.remove('active');
                });
                
                document.getElementById(pageId).classList.add('active');
                document.querySelector(`.nav-link[data-page="${pageId}"]`).classList.add('active');
                
                const dataLoaders = {
                    'manage-staff': loadStaffDataFromServer,
                    'manage-students': loadStudentDataFromServer,
                    'dashboard': () => {
                        loadDashboardStats();
                        loadRecentActivities();
                    },
                    'calendar': () => {
                        renderCalendar();
                        renderEventList();
                    },
                    'reservation': loadReservationData,
                    'archive': loadArchiveData,
                    'reports': initializeReportCharts
                };
                
                if (dataLoaders[pageId]) {
                    dataLoaders[pageId]();
                }
            }

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
                if (activities.length > 10) {
                    activities.splice(10);
                }
                
                localStorage.setItem('recentActivities', JSON.stringify(activities));
                loadRecentActivities();
            }

            function addNewStaff() {
                const name = document.getElementById('staffName').value;
                const email = document.getElementById('staffEmail').value;
                const department = document.getElementById('staffDepartment').value;
                const phone = document.getElementById('staffPhone').value;
                const address = document.getElementById('staffAddress').value;
                const editId = document.getElementById('editStaffId').value;
                
                if (!name || !email || !department || !address) {
                    showToast('Please fill all required fields!', 'error');
                    return;
                }

                if (!validateEmailDomain(email)) {
                    showEmailError('staffEmail');
                    showToast('Email must be from @smcbi.edu.ph domain!', 'error');
                    return;
                }
                removeEmailError('staffEmail');

                if (phone && !/^\d+$/.test(phone)) {
                    showToast('Phone number must contain only numbers!', 'error');
                    return;
                }

                const saveBtn = document.getElementById('saveStaffBtn');
                const originalText = saveBtn.innerHTML;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                saveBtn.disabled = true;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const formData = new FormData();
                formData.append('full_name', name);
                formData.append('email', email);
                formData.append('department', department);
                formData.append('phone', phone || '');
                formData.append('address', address || '');
                formData.append('_token', csrfToken);

                if (editId) {
                    formData.append('_method', 'PUT');
                }

                const url = editId ? `/admin/manage-staff/${editId}` : '/admin/manage-staff';

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Staff saved successfully!', 'success');
                        
                        const staff = Database.getStaff();
                        
                        if (editId) {
                            const index = staff.findIndex(s => s.id == editId);
                            if (index !== -1) {
                                staff[index] = {
                                    ...staff[index],
                                    full_name: name,
                                    email: email,
                                    department: department,
                                    phone: phone,
                                    address: address,
                                    updated_at: new Date().toISOString()
                                };
                            }
                        } else {
                            const newStaff = {
                                id: data.data.id,
                                full_name: name,
                                email: email,
                                department: department,
                                phone: phone,
                                address: address,
                                created_at: new Date().toISOString()
                            };
                            staff.push(newStaff);
                        }
                        
                        localStorage.setItem('staff', JSON.stringify(staff));
                        
                        loadStaffDataFromServer();
                        loadDashboardStats();
                        
                        bootstrap.Modal.getInstance(document.getElementById('addStaffModal')).hide();
                        
                        if (editId) {
                            addRecentActivity('Staff Updated', `Updated ${name}'s information`);
                        } else {
                            addRecentActivity('New Staff Added', `Added ${name} to the staff directory`);
                        }
                    } else {
                        if (data.errors) {
                            const errorMessages = Object.values(data.errors).flat().join(', ');
                            showToast('Validation errors: ' + errorMessages, 'error');
                        } else {
                            showToast(data.message || 'Error saving staff', 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    if (error.errors) {
                        const errorMessages = Object.values(error.errors).flat().join(', ');
                        showToast('Validation errors: ' + errorMessages, 'error');
                    } else {
                        const staff = Database.getStaff();
                        
                        if (editId) {
                            const index = staff.findIndex(s => s.id == editId);
                            if (index !== -1) {
                                staff[index] = {
                                    ...staff[index],
                                    full_name: name,
                                    email: email,
                                    department: department,
                                    phone: phone,
                                    address: address,
                                    updated_at: new Date().toISOString()
                                };
                                localStorage.setItem('staff', JSON.stringify(staff));
                                showToast('Staff updated in local storage!', 'success');
                            }
                        } else {
                            const newId = staff.length > 0 ? Math.max(...staff.map(s => s.id)) + 1 : 1;
                            staff.push({
                                id: newId,
                                full_name: name,
                                email: email,
                                department: department,
                                phone: phone,
                                address: address,
                                created_at: new Date().toISOString()
                            });
                            localStorage.setItem('staff', JSON.stringify(staff));
                            showToast('Staff added to local storage!', 'success');
                        }
                        
                        loadStaffDataFromServer();
                        loadDashboardStats();
                        bootstrap.Modal.getInstance(document.getElementById('addStaffModal')).hide();
                        
                        if (editId) {
                            addRecentActivity('Staff Updated', `Updated ${name}'s information`);
                        } else {
                            addRecentActivity('New Staff Added', `Added ${name} to the staff directory`);
                        }
                    }
                })
                .finally(() => {
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                });
            }

            function deleteStaff(id) {
                if (confirm('Are you sure you want to archive this staff member?')) {
                    archiveStaff(id);
                }
            }

            function addNewStudent() {
                const studentId = document.getElementById('studentId').value;
                const name = document.getElementById('studentName').value;
                const email = document.getElementById('studentEmail').value;
                const course = document.getElementById('studentCourse').value;
                const section = document.getElementById('studentSection').value;
                const yearLevel = document.getElementById('studentYearLevel').value;
                const phone = document.getElementById('studentPhone').value;
                const address = document.getElementById('studentAddress').value;
                const editId = document.getElementById('editStudentId').value;
                
                if (!studentId || !name || !email || !course || !section || !yearLevel || !address) {
                    showToast('Please fill all required fields!', 'error');
                    return;
                }

                if (!studentId.startsWith('C')) {
                    showToast('Student ID must start with "C"!', 'error');
                    return;
                }

                if (!validateEmailDomain(email)) {
                    showEmailError('studentEmail');
                    showToast('Email must be from @smcbi.edu.ph domain!', 'error');
                    return;
                }
                removeEmailError('studentEmail');

                if (phone && !/^\d+$/.test(phone)) {
                    showToast('Phone number must contain only numbers!', 'error');
                    return;
                }

                const saveBtn = document.getElementById('saveStudentBtn');
                const originalText = saveBtn.innerHTML;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                saveBtn.disabled = true;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const formData = new FormData();
                formData.append('student_id', studentId);
                formData.append('full_name', name);
                formData.append('email', email);
                formData.append('course', course);
                formData.append('section', section);
                formData.append('year_level', yearLevel);
                formData.append('phone', phone || '');
                formData.append('address', address || '');
                formData.append('_token', csrfToken);

                if (editId) {
                    formData.append('_method', 'PUT');
                }

                const url = editId ? `/admin/manage-students/${editId}` : '/admin/manage-students';

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Student saved successfully!', 'success');
                        
                        const students = Database.getStudents();
                        
                        if (editId) {
                            const index = students.findIndex(s => s.id == editId);
                            if (index !== -1) {
                                students[index] = {
                                    ...students[index],
                                    student_id: studentId,
                                    full_name: name,
                                    email: email,
                                    course: course,
                                    section: section,
                                    year_level: yearLevel,
                                    phone: phone,
                                    address: address,
                                    updated_at: new Date().toISOString()
                                };
                            }
                        } else {
                            const newStudent = {
                                id: data.data.id,
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
                        }
                        
                        localStorage.setItem('students', JSON.stringify(students));
                        
                        loadStudentDataFromServer();
                        loadDashboardStats();
                        
                        bootstrap.Modal.getInstance(document.getElementById('addStudentModal')).hide();
                        
                        if (editId) {
                            addRecentActivity('Student Updated', `Updated ${name}'s information`);
                        } else {
                            addRecentActivity('New Student Added', `Added ${name} (${studentId}) to student records`);
                        }
                    } else {
                        if (data.errors) {
                            const errorMessages = Object.values(data.errors).flat().join(', ');
                            showToast('Validation errors: ' + errorMessages, 'error');
                        } else {
                            showToast(data.message || 'Error saving student', 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    if (error.errors) {
                        const errorMessages = Object.values(error.errors).flat().join(', ');
                        showToast('Validation errors: ' + errorMessages, 'error');
                    } else {
                        const students = Database.getStudents();
                        
                        if (editId) {
                            const index = students.findIndex(s => s.id == editId);
                            if (index !== -1) {
                                students[index] = {
                                    ...students[index],
                                    student_id: studentId,
                                    full_name: name,
                                    email: email,
                                    course: course,
                                    section: section,
                                    year_level: yearLevel,
                                    phone: phone,
                                    address: address,
                                    updated_at: new Date().toISOString()
                                };
                                localStorage.setItem('students', JSON.stringify(students));
                                showToast('Student updated in local storage!', 'success');
                            }
                        } else {
                            const newId = students.length > 0 ? Math.max(...students.map(s => s.id)) + 1 : 1;
                            students.push({
                                id: newId,
                                student_id: studentId,
                                full_name: name,
                                email: email,
                                course: course,
                                section: section,
                                year_level: yearLevel,
                                phone: phone,
                                address: address,
                                created_at: new Date().toISOString()
                            });
                            localStorage.setItem('students', JSON.stringify(students));
                            showToast('Student added to local storage!', 'success');
                        }
                        
                        loadStudentDataFromServer();
                        loadDashboardStats();
                        bootstrap.Modal.getInstance(document.getElementById('addStudentModal')).hide();
                        
                        if (editId) {
                            addRecentActivity('Student Updated', `Updated ${name}'s information`);
                        } else {
                            addRecentActivity('New Student Added', `Added ${name} (${studentId}) to student records`);
                        }
                    }
                })
                .finally(() => {
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                });
            }

            function deleteStudent(id) {
                if (confirm('Are you sure you want to archive this student?')) {
                    archiveStudent(id);
                }
            }

            function initializeSearchAndFilter() {
                const staffSearch = document.getElementById('staffSearch');
                const staffDepartmentFilter = document.getElementById('staffDepartmentFilter');
                
                if (staffSearch) {
                    staffSearch.addEventListener('input', filterStaff);
                }
                
                if (staffDepartmentFilter) {
                    staffDepartmentFilter.addEventListener('change', filterStaff);
                }
                
                const studentSearch = document.getElementById('studentSearch');
                const studentCourseFilter = document.getElementById('studentCourseFilter');
                const studentYearFilter = document.getElementById('studentYearFilter');
                
                if (studentSearch) {
                    studentSearch.addEventListener('input', filterStudents);
                }
                
                if (studentCourseFilter) {
                    studentCourseFilter.addEventListener('change', filterStudents);
                }
                
                if (studentYearFilter) {
                    studentYearFilter.addEventListener('change', filterStudents);
                }
                
                const participantSearch = document.getElementById('participantSearch');
                const participantCourseFilter = document.getElementById('participantCourseFilter');
                const participantYearFilter = document.getElementById('participantYearFilter');
                
                if (participantSearch) {
                    participantSearch.addEventListener('input', filterParticipants);
                }
                
                if (participantCourseFilter) {
                    participantCourseFilter.addEventListener('change', filterParticipants);
                }
                
                if (participantYearFilter) {
                    participantYearFilter.addEventListener('change', filterParticipants);
                }
                
                const eventDateInput = document.getElementById('eventDate');
                const eventStartTimeInput = document.getElementById('eventStartTime');
                const eventEndTimeInput = document.getElementById('eventEndTime');
                const computerLabInput = document.getElementById('computerLab');
                
                if (eventDateInput) {
                    eventDateInput.addEventListener('change', checkEventConflictsOnInput);
                }
                if (eventStartTimeInput) {
                    eventStartTimeInput.addEventListener('change', checkEventConflictsOnInput);
                }
                if (eventEndTimeInput) {
                    eventEndTimeInput.addEventListener('change', checkEventConflictsOnInput);
                }
                if (computerLabInput) {
                    computerLabInput.addEventListener('change', checkEventConflictsOnInput);
                }

                const reservationSearch = document.getElementById('reservationSearch');
                const reservationLabFilter = document.getElementById('reservationLabFilter');
                const reservationStatusFilter = document.getElementById('reservationStatusFilter');
                
                if (reservationSearch) {
                    reservationSearch.addEventListener('input', filterReservations);
                }
                
                if (reservationLabFilter) {
                    reservationLabFilter.addEventListener('change', filterReservations);
                }
                
                if (reservationStatusFilter) {
                    reservationStatusFilter.addEventListener('change', filterReservations);
                }

                const archiveSearch = document.getElementById('archiveSearch');
                const archiveTypeFilter = document.getElementById('archiveTypeFilter');
                const archiveDateFilter = document.getElementById('archiveDateFilter');
                
                if (archiveSearch) {
                    archiveSearch.addEventListener('input', loadArchiveData);
                }
                
                if (archiveTypeFilter) {
                    archiveTypeFilter.addEventListener('change', loadArchiveData);
                }
                
                if (archiveDateFilter) {
                    archiveDateFilter.addEventListener('change', loadArchiveData);
                }
                
                const emptyArchiveBtn = document.getElementById('emptyArchiveBtn');
                if (emptyArchiveBtn) {
                    emptyArchiveBtn.addEventListener('click', emptyArchive);
                }
            }

            function checkEventConflictsOnInput() {
                const eventTitle = document.getElementById('eventTitle').value;
                const eventDate = document.getElementById('eventDate').value;
                const eventStartTime = document.getElementById('eventStartTime').value;
                const eventEndTime = document.getElementById('eventEndTime').value;
                const computerLab = document.getElementById('computerLab').value;
                const editEventId = document.getElementById('editEventId').value;
                
                if (!eventDate) return;
                
                const participants = getSelectedParticipants();
                const eventData = {
                    title: eventTitle,
                    date: eventDate,
                    startTime: eventStartTime,
                    endTime: eventEndTime,
                    computerLab: computerLab,
                    participants: participants
                };
                
                const conflicts = checkEventConflicts(eventData, editEventId);
                showConflictWarning(conflicts);
            }

            function filterStaff() {
                const searchTerm = document.getElementById('staffSearch').value.toLowerCase();
                const departmentFilter = document.getElementById('staffDepartmentFilter').value;
                
                const staff = Database.getStaff();
                const filteredStaff = staff.filter(staffMember => {
                    const matchesSearch = !searchTerm || 
                        staffMember.full_name?.toLowerCase().includes(searchTerm) ||
                        staffMember.email?.toLowerCase().includes(searchTerm) ||
                        staffMember.department?.toLowerCase().includes(searchTerm);
                    
                    const matchesDepartment = !departmentFilter || staffMember.department === departmentFilter;
                    
                    return matchesSearch && matchesDepartment;
                });
                
                renderStaffTable(filteredStaff);
            }

            function filterStudents() {
                const searchTerm = document.getElementById('studentSearch').value.toLowerCase();
                const courseFilter = document.getElementById('studentCourseFilter').value;
                const yearFilter = document.getElementById('studentYearFilter').value;
                
                const students = Database.getStudents();
                const filteredStudents = students.filter(student => {
                    const matchesSearch = !searchTerm || 
                        student.full_name?.toLowerCase().includes(searchTerm) ||
                        student.student_id?.toLowerCase().includes(searchTerm) ||
                        student.course?.toLowerCase().includes(searchTerm);
                    
                    const matchesCourse = !courseFilter || student.course === courseFilter;
                    const matchesYear = !yearFilter || student.year_level === yearFilter;
                    
                    return matchesSearch && matchesCourse && matchesYear;
                });
                
                renderStudentTable(filteredStudents);
            }

            function filterReservations() {
                const searchTerm = document.getElementById('reservationSearch').value.toLowerCase();
                const labFilter = document.getElementById('reservationLabFilter').value;
                const statusFilter = document.getElementById('reservationStatusFilter').value;
                
                const reservations = Database.getReservations();
                const filteredReservations = reservations.filter(reservation => {
                    const matchesSearch = !searchTerm || 
                        reservation.title?.toLowerCase().includes(searchTerm) ||
                        reservation.purpose?.toLowerCase().includes(searchTerm) ||
                        reservation.requester?.toLowerCase().includes(searchTerm);
                    
                    const matchesLab = !labFilter || reservation.lab === labFilter;
                    const matchesStatus = !statusFilter || reservation.status === statusFilter;
                    
                    return matchesSearch && matchesLab && matchesStatus;
                });
                
                renderReservationTable(filteredReservations);
            }

            function toggleSidebar() {
                const sidebar = document.querySelector('.sidebar');
                const mainContent = document.querySelector('.main-content');
                const toggleButton = document.getElementById('toggleSidebar');
                
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                
                if (sidebar.classList.contains('collapsed')) {
                    toggleButton.innerHTML = '<i class="fas fa-bars"></i>';
                } else {
                    toggleButton.innerHTML = '<i class="fas fa-times"></i>';
                }
                
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            }

            function initializeSidebarState() {
                const sidebar = document.querySelector('.sidebar');
                const mainContent = document.querySelector('.main-content');
                const toggleButton = document.getElementById('toggleSidebar');
                
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                
                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('expanded');
                    toggleButton.innerHTML = '<i class="fas fa-bars"></i>';
                } else {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('expanded');
                    toggleButton.innerHTML = '<i class="fas fa-times"></i>';
                }
            }

            function attachStaffEventListeners() {
                document.querySelectorAll('.edit-staff').forEach(button => {
                    button.addEventListener('click', function() {
                        const id = parseInt(this.getAttribute('data-id'));
                        editStaff(id);
                    });
                });
                
                document.querySelectorAll('.delete-staff').forEach(button => {
                    button.addEventListener('click', function() {
                        const id = parseInt(this.getAttribute('data-id'));
                        deleteStaff(id);
                    });
                });
            }

            function attachStudentEventListeners() {
                document.querySelectorAll('.edit-student').forEach(button => {
                    button.addEventListener('click', function() {
                        const id = parseInt(this.getAttribute('data-id'));
                        editStudent(id);
                    });
                });
                
                document.querySelectorAll('.delete-student').forEach(button => {
                    button.addEventListener('click', function() {
                        const id = parseInt(this.getAttribute('data-id'));
                        deleteStudent(id);
                    });
                });
            }

            function attachReservationEventListeners() {
                document.querySelectorAll('.edit-reservation').forEach(button => {
                    button.addEventListener('click', function() {
                        const id = parseInt(this.getAttribute('data-id'));
                        editReservation(id);
                    });
                });
                
                document.querySelectorAll('.archive-reservation').forEach(button => {
                    button.addEventListener('click', function() {
                        const id = parseInt(this.getAttribute('data-id'));
                        archiveReservation(id);
                    });
                });
                
                document.querySelectorAll('.approve-reservation').forEach(button => {
                    button.addEventListener('click', function() {
                        const id = parseInt(this.getAttribute('data-id'));
                        updateReservationStatus(id, 'approved');
                    });
                });
                
                document.querySelectorAll('.reject-reservation').forEach(button => {
                    button.addEventListener('click', function() {
                        const id = parseInt(this.getAttribute('data-id'));
                        updateReservationStatus(id, 'rejected');
                    });
                });
            }

            function attachArchiveEventListeners() {
                document.querySelectorAll('.restore-archive').forEach(button => {
                    button.addEventListener('click', function() {
                        const type = this.getAttribute('data-type');
                        const id = parseInt(this.getAttribute('data-id'));
                        restoreArchiveItem(type, id);
                    });
                });
                
                document.querySelectorAll('.permanent-delete').forEach(button => {
                    button.addEventListener('click', function() {
                        const type = this.getAttribute('data-type');
                        const id = parseInt(this.getAttribute('data-id'));
                        permanentDeleteItem(type, id);
                    });
                });
            }

            function attachEventListenersAfterRender() {
                attachStaffEventListeners();
                attachStudentEventListeners();
                attachReservationEventListeners();
                attachArchiveEventListeners();
            }

            document.addEventListener('DOMContentLoaded', function() {
                Database.init();
                
                if (!localStorage.getItem('recentActivities')) {
                    localStorage.setItem('recentActivities', JSON.stringify([]));
                }
                
                initializeCharts();
                loadDashboardStats();
                loadRecentActivities();
                navigateToPage('calendar');
                
                document.getElementById('logoutBtn').addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to logout?')) {
                        document.getElementById('logout-form').submit();
                    }
                });
                
                initializeManagementSystem();
                initializeCalendarEventListeners();
                initializeEmailValidation();
                initializeSearchAndFilter();
                initializeSidebarState();
                
                document.getElementById('toggleSidebar').addEventListener('click', toggleSidebar);
                
                document.getElementById('generateStudentReport').addEventListener('click', generateStudentReport);
                document.getElementById('generateStaffReport').addEventListener('click', generateStaffReport);
                document.getElementById('generateEventReport').addEventListener('click', generateEventReport);
                document.getElementById('generateReservationReport').addEventListener('click', generateReservationReport);
                document.getElementById('exportStudentStats').addEventListener('click', generateStudentReport);
                document.getElementById('exportEventStats').addEventListener('click', generateEventReport);
                document.getElementById('exportReservationStats').addEventListener('click', generateReservationReport);
            });

            function initializeManagementSystem() {
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const pageId = this.getAttribute('data-page');
                        navigateToPage(pageId);
                    });
                });

                document.getElementById('saveStaffBtn').addEventListener('click', function(e) {
                    e.preventDefault();
                    addNewStaff();
                });
                
                document.getElementById('saveStudentBtn').addEventListener('click', function(e) {
                    e.preventDefault();
                    addNewStudent();
                });
                
                document.getElementById('saveReservationBtn').addEventListener('click', function(e) {
                    e.preventDefault();
                    saveReservation();
                });
                
                document.getElementById('addStaffModal').addEventListener('hidden.bs.modal', function() {
                    resetStaffModal();
                    removeEmailError('staffEmail');
                });
                
                document.getElementById('addStudentModal').addEventListener('hidden.bs.modal', function() {
                    resetStudentModal();
                    removeEmailError('studentEmail');
                });
                
                document.getElementById('addReservationModal').addEventListener('hidden.bs.modal', function() {
                    resetReservationModal();
                });
                
                document.getElementById('addStudentBtn').addEventListener('click', function() {
                    resetStudentModal();
                    new bootstrap.Modal(document.getElementById('addStudentModal')).show();
                });
                
                document.getElementById('addFirstStudentBtn').addEventListener('click', function() {
                    resetStudentModal();
                    new bootstrap.Modal(document.getElementById('addStudentModal')).show();
                });
            }

            function initializeCalendarEventListeners() {
                prevMonthBtn.addEventListener('click', () => {
                    currentMonth--;
                    if (currentMonth < 0) {
                        currentMonth = 11;
                        currentYear--;
                    }
                    renderCalendar();
                });

                nextMonthBtn.addEventListener('click', () => {
                    currentMonth++;
                    if (currentMonth > 11) {
                        currentMonth = 0;
                        currentYear++;
                    }
                    renderCalendar();
                });

                todayBtn.addEventListener('click', () => {
                    currentDate = new Date();
                    currentMonth = currentDate.getMonth();
                    currentYear = currentDate.getFullYear();
                    renderCalendar();
                });

                calendarViewBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const view = this.getAttribute('data-view');
                        calendarViewBtns.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        
                        if (view === 'month') {
                            calendarMonthView.style.display = 'block';
                            calendarListView.style.display = 'none';
                        } else {
                            calendarMonthView.style.display = 'none';
                            calendarListView.style.display = 'block';
                            renderEventList();
                        }
                    });
                });

                document.getElementById('saveEventBtn').addEventListener('click', function(e) {
                    e.preventDefault();
                    saveEvent();
                });

                document.getElementById('selectParticipantsBtn').addEventListener('click', function() {
                    populateParticipantLists();
                    new bootstrap.Modal(document.getElementById('participantSelectionModal')).show();
                });

                document.getElementById('confirmParticipantsBtn').addEventListener('click', function() {
                    updateSelectedParticipantsCount();
                    bootstrap.Modal.getInstance(document.getElementById('participantSelectionModal')).hide();
                    showToast('Participants selected successfully!', 'success');
                });

                document.getElementById('participantSelectionModal').addEventListener('show.bs.modal', function() {
                    populateParticipantLists();
                });
            }

            function initializeEmailValidation() {
                const staffEmail = document.getElementById('staffEmail');
                const studentEmail = document.getElementById('studentEmail');
                
                if (staffEmail) {
                    staffEmail.addEventListener('blur', function() {
                        const email = this.value;
                        if (email && !validateEmailDomain(email)) {
                            showEmailError('staffEmail');
                        } else {
                            removeEmailError('staffEmail');
                        }
                    });
                }
                
                if (studentEmail) {
                    studentEmail.addEventListener('blur', function() {
                        const email = this.value;
                        if (email && !validateEmailDomain(email)) {
                            showEmailError('studentEmail');
                        } else {
                            removeEmailError('studentEmail');
                        }
                    });
                }
            }

            $(document).ready(function () {
                $('#reservationForm').on('submit', function (e) {
                    e.preventDefault();

                    let form = $(this);
                    let formData = new FormData(this);
                    let token = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: '/computer-reservations',
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': token },
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Reservation Submitted!',
                                    text: 'Your reservation is pending approval.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                form.trigger('reset');
                                $('#reservationModal').modal('hide');
                                $('#reservationTable').DataTable().ajax.reload();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message || 'Failed to submit reservation.'
                                });
                            }
                        },
                        error: function (xhr) {
                            console.error(xhr);
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Failed',
                                text: xhr.responseJSON?.message || 'Please check your inputs.'
                            });
                        }
                    });
                });
            });
            $(document).ready(function() {
                console.log('Reservation script loaded ✅');

                $('#addReservationForm').on('submit', function(e) {
                    e.preventDefault();

                    console.log('Save Reservation button clicked 🟢');

                    const formData = new FormData(this);
                    const token = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: '/computer-reservations',
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': token },
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            $('#saveReservationBtn').prop('disabled', true).text('Saving...');
                        },
                        success: function(response) {
                            console.log('Reservation saved ✅', response);

                            Swal.fire({
                                icon: 'success',
                                title: 'Reservation Submitted!',
                                text: 'Your reservation is now pending for approval.',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            $('#addReservationModal').modal('hide');
                            $('#addReservationForm')[0].reset();
                            $('#reservationTable').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            console.error('Error saving reservation ❌', xhr);
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: xhr.responseJSON?.message || 'Please check all required fields.'
                            });
                        },
                        complete: function() {
                            $('#saveReservationBtn').prop('disabled', false).text('Save Reservation');
                        }
                    });
                });
            });
        </script>
    </body>
    </html>