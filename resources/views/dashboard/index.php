<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body class="dashboard-page">
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <div class="logo-icon">W</div>
                    <span class="logo-text">Web Makeer</span>
                </div>
                <button class="toggle-btn" id="sidebarToggle">☰</button>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/dashboard" class="nav-link active">
                                <span class="nav-icon">📊</span>
                                <span class="nav-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/customers" class="nav-link">
                                <span class="nav-icon">👥</span>
                                <span class="nav-text">Customers</span>
                                <span class="nav-badge">12</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/leads" class="nav-link">
                                <span class="nav-icon">🎯</span>
                                <span class="nav-text">Leads</span>
                                <span class="nav-badge">8</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/deals" class="nav-link">
                                <span class="nav-icon">💼</span>
                                <span class="nav-text">Deals</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Analytics</div>
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/reports" class="nav-link">
                                <span class="nav-icon">📈</span>
                                <span class="nav-text">Reports</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/analytics" class="nav-link">
                                <span class="nav-icon">📉</span>
                                <span class="nav-text">Analytics</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Settings</div>
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/profile" class="nav-link">
                                <span class="nav-icon">⚙️</span>
                                <span class="nav-text">Settings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/help" class="nav-link">
                                <span class="nav-icon">❓</span>
                                <span class="nav-text">Help</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="user-avatar"><?= strtoupper(substr($user['username'] ?? 'U', 0, 1)) ?></div>
                    <div class="user-info">
                        <div class="user-name"><?= htmlspecialchars($user['username'] ?? 'User') ?></div>
                        <div class="user-role">Administrator</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="dashboard-header">
                <div class="header-left">
                    <h1>Welcome back, <?= htmlspecialchars($user['username'] ?? 'User') ?>! 👋</h1>
                </div>
                <div class="header-right">
                    <div class="search-box">
                        <span class="search-icon">🔍</span>
                        <input type="text" class="search-input" placeholder="Search...">
                    </div>
                    <div class="header-actions">
                        <button class="action-btn" title="Notifications">
                            🔔
                            <span class="badge">3</span>
                        </button>
                        <button class="action-btn" title="Messages">
                            💬
                            <span class="badge">5</span>
                        </button>
                        <a href="<?= BASE_URL ?>/logout" class="action-btn" title="Logout">
                            🚪
                        </a>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-label">Total Customers</div>
                            </div>
                            <div class="stat-icon"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                👥
                            </div>
                        </div>
                        <div class="stat-value">1,284</div>
                        <div class="stat-change positive">
                            ↗ +12.5% from last month
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-label">Active Leads</div>
                            </div>
                            <div class="stat-icon"
                                style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                🎯
                            </div>
                        </div>
                        <div class="stat-value">342</div>
                        <div class="stat-change positive">
                            ↗ +8.3% from last month
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-label">Total Revenue</div>
                            </div>
                            <div class="stat-icon"
                                style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                💰
                            </div>
                        </div>
                        <div class="stat-value">$48.5K</div>
                        <div class="stat-change positive">
                            ↗ +15.2% from last month
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-label">Conversion Rate</div>
                            </div>
                            <div class="stat-icon"
                                style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                📊
                            </div>
                        </div>
                        <div class="stat-value">24.8%</div>
                        <div class="stat-change negative">
                            ↘ -2.1% from last month
                        </div>
                    </div>
                </div>

                <!-- Dashboard Cards -->
                <div class="dashboard-grid">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3 class="card-title">Recent Activity</h3>
                            <a href="#" class="card-action">View All →</a>
                        </div>
                        <div class="card-content">
                            <p style="color: rgba(255, 255, 255, 0.7); padding: 20px 0;">
                                Your recent activities will appear here. Start by adding customers or creating leads to
                                see real-time updates.
                            </p>
                        </div>
                    </div>

                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3 class="card-title">Quick Actions</h3>
                        </div>
                        <div class="card-content"
                            style="display: flex; flex-direction: column; gap: 12px; padding-top: 10px;">
                            <a href="<?= BASE_URL ?>/customers/add"
                                style="padding: 15px; background: rgba(255, 255, 255, 0.1); border-radius: 12px; text-decoration: none; color: white; transition: all 0.3s; display: block;">
                                <strong>➕ Add New Customer</strong>
                            </a>
                            <a href="<?= BASE_URL ?>/leads/add"
                                style="padding: 15px; background: rgba(255, 255, 255, 0.1); border-radius: 12px; text-decoration: none; color: white; transition: all 0.3s; display: block;">
                                <strong>🎯 Create New Lead</strong>
                            </a>
                            <a href="<?= BASE_URL ?>/deals/add"
                                style="padding: 15px; background: rgba(255, 255, 255, 0.1); border-radius: 12px; text-decoration: none; color: white; transition: all 0.3s; display: block;">
                                <strong>💼 Add New Deal</strong>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="<?= BASE_URL ?>/assets/dashboard.js"></script>
</body>

</html>