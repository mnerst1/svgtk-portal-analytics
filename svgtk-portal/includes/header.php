<?php
// includes/header.php

// Определяем глубину пути — корень или /pages/
$depth = (strpos(str_replace('\\','/',$_SERVER['PHP_SELF']), '/pages/') !== false) ? '../' : '';
$current = basename($_SERVER['PHP_SELF'], '.php');

$nav = [
    ['href' => $depth.'index.php',            'icon' => 'home',        'label' => 'Дашборд',        'id' => 'index'],
    ['href' => $depth.'pages/grades.php',     'icon' => 'bar-chart-2', 'label' => 'Успеваемость',   'id' => 'grades'],
    ['href' => $depth.'pages/attendance.php', 'icon' => 'calendar',    'label' => 'Посещаемость',   'id' => 'attendance'],
    ['href' => $depth.'pages/graduates.php',  'icon' => 'award',       'label' => 'Выпускники',     'id' => 'graduates'],
    ['href' => $depth.'pages/groups.php',     'icon' => 'users',       'label' => 'Группы',         'id' => 'groups'],
    ['href' => $depth.'pages/students.php',   'icon' => 'user',        'label' => 'Студенты',       'id' => 'students'],
];

$icons = [
    'home'        => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
    'bar-chart-2' => '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
    'calendar'    => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="16" y1="2" x2="16" y2="6"/>',
    'award'       => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>',
    'users'       => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
    'user'        => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
];
?>
<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title ?? 'Дашборд') ?> — СВГТК Портал</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300..700&family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= $depth ?>assets/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>

<!-- ── Sidebar ──────────────────────────────────── -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <div class="logo">
      <svg width="32" height="32" viewBox="0 0 32 32" fill="none" aria-label="СВГТК Портал">
        <rect width="32" height="32" rx="8" fill="#1a56db"/>
        <text x="16" y="22" text-anchor="middle" font-family="Montserrat,sans-serif"
              font-weight="700" font-size="13" fill="white">СП</text>
      </svg>
      <div class="logo-text">
        <span class="logo-title">СВГТК Портал</span>
        <span class="logo-sub">Аналитика</span>
      </div>
    </div>
    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Свернуть меню">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="15 18 9 12 15 6"/>
      </svg>
    </button>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-label">Навигация</div>
    <?php foreach($nav as $item): ?>
    <a href="<?= $item['href'] ?>" class="nav-item <?= $current === $item['id'] ? 'active' : '' ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
           stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <?= $icons[$item['icon']] ?>
      </svg>
      <span><?= $item['label'] ?></span>
    </a>
    <?php endforeach; ?>

    <div class="nav-section-label" style="margin-top:var(--space-4)">Отчёты</div>
    <a href="<?= $depth ?>pages/grades.php" class="nav-item">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
        <line x1="16" y1="13" x2="8" y2="13"/>
        <line x1="16" y1="17" x2="8" y2="17"/>
      </svg>
      <span>Отчёт успеваемости</span>
    </a>
    <a href="<?= $depth ?>pages/attendance.php" class="nav-item">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="9 11 12 14 22 4"/>
        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
      </svg>
      <span>Отчёт посещаемости</span>
    </a>
    <a href="<?= $depth ?>pages/graduates.php" class="nav-item">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
      </svg>
      <span>Отчёт выпуска</span>
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="college-info">
      <span>СВГТК им. Абая Кунанбаева</span>
      <span>г. Сарань, <?= date('Y') ?></span>
    </div>
  </div>
</aside>

<!-- ── Main wrapper ─────────────────────────────── -->
<div class="main-wrapper" id="mainWrapper">

  <header class="topbar">
    <div class="topbar-left">
      <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Открыть меню">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="3" y1="6" x2="21" y2="6"/>
          <line x1="3" y1="12" x2="21" y2="12"/>
          <line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
      </button>
      <div class="breadcrumb">
        <span class="breadcrumb-root">СВГТК</span>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="9 18 15 12 9 6"/>
        </svg>
        <span class="breadcrumb-current"><?= htmlspecialchars($page_title ?? 'Дашборд') ?></span>
      </div>
    </div>
    <div class="topbar-right">
      <div class="academic-year-badge">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="4" width="18" height="18" rx="2"/>
          <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        <?= ACADEMIC_YEAR ?>
      </div>
      <button class="theme-toggle" id="themeToggle" aria-label="Переключить тему">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
        </svg>
      </button>
      <div class="user-avatar" title="Администратор">АД</div>
    </div>
  </header>

  <main class="page-content">
