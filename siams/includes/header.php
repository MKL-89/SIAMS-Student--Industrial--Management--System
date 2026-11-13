<?php
/**
 * Page header and navigation. Set $page_title before including this file.
 * Logged-in users get a side menu with only the pages allowed for their role;
 * visitors get a top bar with the public pages.
 */
$page_title = $page_title ?? APP_NAME;
$role = current_role();

// Menu items per role: label => [path, icon]. New modules add their pages here.
$menus = [
    'admin'       => [
        'Dashboard'    => ['/admin/index.php', 'bi-speedometer2'],
        'Users'        => ['/admin/users.php', 'bi-people'],
        'Activity log' => ['/admin/audit.php', 'bi-list-check'],
        'Backup'       => ['/admin/backup.php', 'bi-database-down'],
    ],
    'coordinator' => [
        'Dashboard'     => ['/coordinator/index.php', 'bi-speedometer2'],
        'Organizations' => ['/coordinator/organizations.php', 'bi-buildings'],
        'Opportunities' => ['/coordinator/opportunities.php', 'bi-briefcase'],
        'Placements'    => ['/coordinator/placements.php', 'bi-check2-square'],
        'Supervisors'   => ['/coordinator/assign.php', 'bi-person-plus'],
        'Results'       => ['/coordinator/results.php', 'bi-award'],
        'Documents'     => ['/coordinator/documents.php', 'bi-folder2-open'],
        'Reports'       => ['/coordinator/reports.php', 'bi-bar-chart'],
        'Periods'       => ['/coordinator/periods.php', 'bi-calendar-range'],
    ],
    'lecturer'    => [
        'Dashboard'   => ['/lecturer/index.php', 'bi-speedometer2'],
        'My students' => ['/lecturer/students.php', 'bi-people'],
    ],
    'industrial'  => [
        'Dashboard'     => ['/industrial/index.php', 'bi-speedometer2'],
        'Opportunities' => ['/industrial/opportunities.php', 'bi-briefcase'],
        'Applicants'    => ['/industrial/applicants.php', 'bi-person-lines-fill'],
        'My students'   => ['/industrial/students.php', 'bi-people'],
        'Logbooks'      => ['/industrial/logbook.php', 'bi-journal-check'],
        'Assessments'   => ['/industrial/assess.php', 'bi-clipboard-check'],
    ],
    'student'     => [
        'Dashboard'       => ['/student/index.php', 'bi-speedometer2'],
        'Opportunities'   => ['/student/opportunities.php', 'bi-search'],
        'My applications' => ['/student/applications.php', 'bi-send'],
        'My logbook'      => ['/student/logbook.php', 'bi-journal-text'],
        'My documents'    => ['/student/documents.php', 'bi-file-earmark-arrow-up'],
        'My results'      => ['/student/results.php', 'bi-award'],
        'My profile'      => ['/student/profile.php', 'bi-person-badge'],
    ],
];
$current_path = substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), strlen(BASE_URL));

/** A menu item is active on its own page and on pages that belong to it (e.g. opportunity.php under opportunities.php). */
function menu_active(string $path, string $current): bool
{
    if ($current === $path) {
        return true;
    }
    $stem = preg_replace('/(ies|s)\.php$/', '', $path);
    return $stem !== $path && str_starts_with($current, $stem);
}

$initials = '';
if ($role) {
    foreach (array_slice(preg_split('/\s+/', trim($_SESSION['full_name'])), 0, 2) as $w) { $initials .= mb_strtoupper(mb_substr($w, 0, 1)); }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($page_title) ?> | <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css?v=<?= filemtime(__DIR__ . "/../assets/css/style.css") ?>" rel="stylesheet">
    <script>
        // Apply the saved light/dark theme before the page is drawn, so it does not flash.
        try { if (localStorage.getItem('siams-theme') === 'dark') { document.documentElement.setAttribute('data-bs-theme', 'dark'); } } catch (e) {}
    </script>
</head>
<body class="bg-light<?= $role ? ' app-body' : ' public-body' ?>" data-base="<?= e(BASE_PATH) ?>" data-slot="<?= current_slot() ?>" data-auth="<?= $role ? '1' : '' ?>" data-uid="<?= (int) ($_SESSION['user_id'] ?? 0) ?>">
<?php if ($role): ?>
<div class="app-shell">
    <aside class="app-sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="appSidebar" aria-label="Main menu">
        <div class="sidebar-brand">
            <a class="d-flex align-items-center gap-2 text-decoration-none" href="<?= BASE_URL . ROLE_HOME[$role] ?>">
                <span class="brand-mark"><i class="bi bi-gear-wide-connected"></i></span>
                <span>
                    <span class="brand-name"><?= APP_NAME ?></span>
                    <span class="brand-sub">Industrial Attachment</span>
                </span>
            </a>
            <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#appSidebar" aria-label="Close menu"></button>
        </div>
        <div class="sidebar-role"><i class="bi bi-shield-check me-1"></i><?= e(ROLE_LABEL[$role]) ?></div>
        <nav class="sidebar-nav">
            <p class="sidebar-heading">Menu</p>
            <ul class="nav flex-column">
                <?php foreach ($menus[$role] as $label => [$path, $icon]): ?>
                    <li class="nav-item">
                        <a class="nav-link<?= menu_active($path, $current_path) ? ' active' : '' ?>"
                           href="<?= BASE_URL . $path ?>"><i class="bi <?= $icon ?>"></i><span><?= e($label) ?></span></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p class="sidebar-heading mt-3">Account</p>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link<?= $current_path === '/account.php' ? ' active' : '' ?>" href="<?= BASE_URL ?>/account.php"><i class="bi bi-key"></i><span>My account</span></a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/logout.php"><i class="bi bi-box-arrow-right"></i><span>Log out</span></a></li>
            </ul>
        </nav>
        <div class="sidebar-foot"><i class="bi bi-mortarboard me-1"></i><?= e(INSTITUTION) ?></div>
    </aside>

    <div class="app-main">
        <header class="app-topbar">
            <button class="btn btn-icon d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#appSidebar" aria-controls="appSidebar" aria-label="Open menu">
                <i class="bi bi-list"></i>
            </button>
            <div class="topbar-title text-truncate"><?= e($page_title) ?></div>
            <button class="theme-toggle ms-auto" type="button" data-theme-toggle aria-label="Switch light or dark theme" title="Switch light or dark theme">
                <i class="bi bi-moon-stars" aria-hidden="true"></i><i class="bi bi-sun" aria-hidden="true"></i>
            </button>
            <div class="dropdown">
                <button class="btn user-chip dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="<?= e(ROLE_LABEL[$role]) ?>">
                    <span class="avatar"><?= e($initials) ?></span>
                    <span class="d-none d-sm-inline text-truncate user-name"><?= e($_SESSION['full_name']) ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text small text-muted"><?= e(ROLE_LABEL[$role]) ?></span></li>
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/account.php"><i class="bi bi-key me-2"></i>My account (change password)</a></li>
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/login.php" target="_blank" rel="noopener"
                           title="Opens a new tab where you can log in with another account; this tab keeps its account"><i class="bi bi-person-plus me-2"></i>Use another account (new tab)</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Log out</a></li>
                </ul>
            </div>
        </header>
        <main class="app-content">
<?php else: ?>
<?php
// Visitors who are not logged in: links to the public pages.
$publicMenu = ['Home' => '/index.php', 'Opportunities' => '/opportunities.php', 'For organizations' => '/register_host.php'];
?>
<nav class="navbar navbar-expand-lg public-nav sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL ?>/index.php">
            <span class="brand-mark"><i class="bi bi-gear-wide-connected"></i></span>
            <span class="brand-name"><?= APP_NAME ?></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto ms-lg-3">
                <?php foreach ($publicMenu as $label => $path): ?>
                    <li class="nav-item">
                        <a class="nav-link<?= $current_path === $path || ($path === '/index.php' && $current_path === '/') || ($path === '/opportunities.php' && $current_path === '/opportunity.php') ? ' active' : '' ?>"
                           href="<?= BASE_URL . $path ?>"><?= e($label) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button class="theme-toggle me-2 mb-2 mb-lg-0" type="button" data-theme-toggle aria-label="Switch light or dark theme" title="Switch light or dark theme">
                <i class="bi bi-moon-stars" aria-hidden="true"></i><i class="bi bi-sun" aria-hidden="true"></i>
            </button>
            <a class="btn btn-outline-secondary btn-sm me-2 text-nowrap" href="<?= BASE_URL ?>/login.php">
                <i class="bi bi-box-arrow-in-right me-1"></i>Log in
            </a>
            <a class="btn btn-accent btn-sm text-nowrap fw-semibold" href="<?= BASE_URL ?>/register.php">
                <i class="bi bi-person-plus me-1"></i>Create account
            </a>
        </div>
    </div>
</nav>
<main class="<?= empty($full_width) ? 'container py-4' : '' ?>">
<?php endif; ?>
