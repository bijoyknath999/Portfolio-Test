<?php
require_once '../includes/functions.php';
requireLogin();

// Handle delete action
if (isset($_POST['delete_visitor'])) {
    $id = $_POST['visitor_id'];
    if (deleteVisitor($id)) {
        $success = "Visitor record deleted successfully!";
    } else {
        $error = "Failed to delete visitor record.";
    }
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 50;
$offset = ($page - 1) * $perPage;

// Get visitor data
$visitors = getVisitors($perPage, $offset);
$totalVisitors = getTotalVisitors();
$todayVisitors = getTodayVisitors();
$uniqueVisitors = getUniqueVisitors();
$totalPages = ceil($totalVisitors / $perPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitors - Admin Panel</title>
    <link rel="stylesheet" href="admin-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-users"></i> Website Visitors</h1>
            <p>Track and monitor all website visitors</p>
        </div>

        <?php if (isset($success)): ?>
            <div class="notification success">
                <i class="fas fa-check-circle"></i>
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="notification error">
                <i class="fas fa-times-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stat-details">
                    <h3><?php echo number_format($totalVisitors); ?></h3>
                    <p>Total Visits</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-details">
                    <h3><?php echo number_format($todayVisitors); ?></h3>
                    <p>Today's Visits</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div class="stat-details">
                    <h3><?php echo number_format($uniqueVisitors); ?></h3>
                    <p>Unique Visitors</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-details">
                    <h3><?php echo $totalVisitors > 0 ? number_format(($uniqueVisitors / $totalVisitors) * 100, 1) : 0; ?>%</h3>
                    <p>Unique Rate</p>
                </div>
            </div>
        </div>

        <!-- Visitors Table -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-list"></i> Recent Visitors</h2>
                <span class="badge"><?php echo number_format($totalVisitors); ?> total</span>
            </div>
            <div class="card-content">
                <?php if (empty($visitors)): ?>
                    <div class="empty-state">
                        <i class="fas fa-users"></i>
                        <p>No visitors yet. Share your portfolio to get visitors!</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th><i class="fas fa-network-wired"></i> IP Address</th>
                                    <th><i class="fas fa-globe"></i> Location</th>
                                    <th><i class="fas fa-desktop"></i> Browser</th>
                                    <th><i class="fas fa-laptop"></i> OS</th>
                                    <th><i class="fas fa-mobile-alt"></i> Device</th>
                                    <th><i class="fas fa-link"></i> Referrer</th>
                                    <th><i class="fas fa-clock"></i> Visit Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($visitors as $visitor): ?>
                                    <tr>
                                        <td><?php echo $visitor['id']; ?></td>
                                        <td>
                                            <code class="ip-badge"><?php echo htmlspecialchars($visitor['ip_address']); ?></code>
                                        </td>
                                        <td>
                                            <?php if ($visitor['country'] !== 'Unknown'): ?>
                                                <span class="location-badge">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <?php echo htmlspecialchars($visitor['city'] . ', ' . $visitor['country']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="unknown-badge">Unknown</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="tech-badge browser">
                                                <i class="fas fa-globe"></i>
                                                <?php echo htmlspecialchars($visitor['browser']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="tech-badge os">
                                                <i class="fas fa-laptop"></i>
                                                <?php echo htmlspecialchars($visitor['os']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="device-badge device-<?php echo strtolower($visitor['device']); ?>">
                                                <i class="fas fa-<?php 
                                                    echo $visitor['device'] === 'Mobile' ? 'mobile-alt' : 
                                                        ($visitor['device'] === 'Tablet' ? 'tablet-alt' : 'desktop'); 
                                                ?>"></i>
                                                <?php echo htmlspecialchars($visitor['device']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php 
                                            $referrer = $visitor['referrer'];
                                            if ($referrer === 'Direct' || empty($referrer)): 
                                            ?>
                                                <span class="referrer-badge direct">
                                                    <i class="fas fa-arrow-right"></i> Direct
                                                </span>
                                            <?php else: ?>
                                                <span class="referrer-badge external" title="<?php echo htmlspecialchars($referrer); ?>">
                                                    <i class="fas fa-external-link-alt"></i>
                                                    <?php echo htmlspecialchars(substr(parse_url($referrer, PHP_URL_HOST) ?? 'External', 0, 20)); ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="visit-time">
                                                <div><?php echo date('M d, Y', strtotime($visitor['visit_date'])); ?></div>
                                                <small><?php echo date('h:i A', strtotime($visitor['visit_date'])); ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Delete this visitor record?');">
                                                <input type="hidden" name="visitor_id" value="<?php echo $visitor['id']; ?>">
                                                <button type="submit" name="delete_visitor" class="btn-action btn-delete" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo $page - 1; ?>" class="page-link">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                            <?php endif; ?>

                            <span class="page-info">
                                Page <?php echo $page; ?> of <?php echo $totalPages; ?>
                            </span>

                            <?php if ($page < $totalPages): ?>
                                <a href="?page=<?php echo $page + 1; ?>" class="page-link">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }

        .stat-details h3 {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0 0 5px 0;
        }

        .stat-details p {
            color: #666;
            margin: 0;
            font-size: 0.95rem;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        .data-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .data-table th {
            padding: 14px 12px;
            text-align: left;
            font-weight: 600;
            white-space: nowrap;
        }

        .data-table td {
            padding: 14px 12px;
            border-bottom: 1px solid #f0f0f0;
        }

        .data-table tbody tr:hover {
            background: #f8f9ff;
        }

        .ip-badge {
            background: #f0f0f0;
            padding: 5px 10px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: #333;
        }

        .location-badge, .unknown-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.85rem;
        }

        .location-badge {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }

        .unknown-badge {
            background: #e0e0e0;
            color: #666;
        }

        .tech-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .tech-badge.browser {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .tech-badge.os {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .device-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .device-badge.device-desktop {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .device-badge.device-mobile {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }

        .device-badge.device-tablet {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #333;
        }

        .referrer-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
        }

        .referrer-badge.direct {
            background: #e0e0e0;
            color: #666;
        }

        .referrer-badge.external {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            color: #333;
        }

        .visit-time {
            font-size: 0.85rem;
        }

        .visit-time small {
            color: #999;
            display: block;
            margin-top: 2px;
        }

        .btn-action {
            padding: 6px 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .btn-delete {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(238, 90, 111, 0.4);
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }

        .page-link {
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .page-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .page-info {
            font-weight: 600;
            color: #666;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.85rem;
            }

            .data-table th,
            .data-table td {
                padding: 10px 8px;
            }
        }
    </style>
</body>
</html>

