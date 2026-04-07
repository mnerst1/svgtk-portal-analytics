<?php
require_once 'includes/config.php';
$page_title = 'Дашборд';
$pdo = getDB();

$total_students  = $pdo->query("SELECT COUNT(*) FROM students WHERE status='active'")->fetchColumn();
$total_groups    = $pdo->query("SELECT COUNT(*) FROM `groups`")->fetchColumn();
$total_graduates = $pdo->query("SELECT COUNT(*) FROM graduates")->fetchColumn();
$avg_grade       = $pdo->query("SELECT ROUND(AVG(grade),2) FROM grades WHERE grade_type='final' AND academic_year='2024-2025'")->fetchColumn();
$att_total       = $pdo->query("SELECT COUNT(*) FROM attendance")->fetchColumn();
$att_present     = $pdo->query("SELECT COUNT(*) FROM attendance WHERE status='present'")->fetchColumn();
$att_pct         = $att_total > 0 ? round($att_present / $att_total * 100, 1) : 0;

$grade_dist = $pdo->query("
  SELECT grade, COUNT(*) as cnt
  FROM grades WHERE grade_type='final' AND academic_year='2024-2025'
  GROUP BY grade ORDER BY grade DESC
")->fetchAll();

$group_perf = $pdo->query("
  SELECT g.name, ROUND(AVG(gr.grade),2) as avg_grade
  FROM `groups` g
  JOIN students s ON s.group_id=g.id
  JOIN grades gr ON gr.student_id=s.id
  WHERE gr.grade_type='final' AND gr.academic_year='2024-2025'
  GROUP BY g.id, g.name ORDER BY avg_grade DESC
")->fetchAll();

$group_att = $pdo->query("
  SELECT g.name,
    COUNT(*) as total,
    SUM(a.status='present') as present_cnt
  FROM `groups` g
  JOIN students s ON s.group_id=g.id
  JOIN attendance a ON a.student_id=s.id
  GROUP BY g.id, g.name
")->fetchAll();

$top_students = $pdo->query("
  SELECT s.full_name, g.name as group_name, ROUND(AVG(gr.grade),2) as avg
  FROM students s
  JOIN `groups` g ON g.id=s.group_id
  JOIN grades gr ON gr.student_id=s.id
  WHERE gr.grade_type='final'
  GROUP BY s.id ORDER BY avg DESC LIMIT 8
")->fetchAll();

require_once 'includes/header.php';
?>

<div class="page-header">
  <div class="page-header-info">
    <h1 class="page-title">Аналитика и отчётность</h1>
    <p class="page-subtitle">Учебный год <?= ACADEMIC_YEAR ?> — сводная панель ключевых показателей</p>
  </div>
  <div class="page-actions">
    <a href="pages/grades.php" class="btn btn-outline">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
      </svg>
      Отчёты
    </a>
    <button class="btn btn-primary" onclick="window.print()">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="6 9 6 2 18 2 18 9"/>
        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
        <rect x="6" y="14" width="12" height="8"/>
      </svg>
      Печать
    </button>
  </div>
</div>

<!-- KPI -->
<div class="kpi-grid">
  <div class="kpi-card">
    <div class="kpi-card-header">
      <span class="kpi-label">Студентов</span>
      <div class="kpi-icon blue">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
      </div>
    </div>
    <div class="kpi-value"><?= $total_students ?></div>
    <div class="kpi-change">Активных студентов</div>
  </div>
  <div class="kpi-card">
    <div class="kpi-card-header">
      <span class="kpi-label">Средний балл</span>
      <div class="kpi-icon green">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
        </svg>
      </div>
    </div>
    <div class="kpi-value"><?= number_format($avg_grade, 2) ?></div>
    <div class="kpi-change">По всем предметам</div>
  </div>
  <div class="kpi-card">
    <div class="kpi-card-header">
      <span class="kpi-label">Посещаемость</span>
      <div class="kpi-icon amber">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/>
          <line x1="8" y1="2" x2="8" y2="6"/><line x1="16" y1="2" x2="16" y2="6"/>
        </svg>
      </div>
    </div>
    <div class="kpi-value"><?= $att_pct ?>%</div>
    <div class="kpi-change">Явка на занятия</div>
  </div>
  <div class="kpi-card">
    <div class="kpi-card-header">
      <span class="kpi-label">Выпускников</span>
      <div class="kpi-icon gold">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
          <path d="M6 12v5c3 3 9 3 12 0v-5"/>
        </svg>
      </div>
    </div>
    <div class="kpi-value"><?= $total_graduates ?></div>
    <div class="kpi-change"><?= $total_groups ?> учебных групп</div>
  </div>
</div>

<!-- Графики -->
<div class="charts-grid">
  <div class="card">
    <div class="card-header">
      <span class="card-title">Распределение оценок</span>
      <span class="badge badge-blue"><?= ACADEMIC_YEAR ?></span>
    </div>
    <div class="card-body">
      <div class="chart-container">
        <canvas id="gradeDistChart"></canvas>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <span class="card-title">Успеваемость по группам</span>
    </div>
    <div class="card-body">
      <div class="chart-container">
        <canvas id="groupGradeChart"></canvas>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <span class="card-title">Посещаемость по группам</span>
    </div>
    <div class="card-body">
      <div class="chart-container">
        <canvas id="attChart"></canvas>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <span class="card-title">Лучшие студенты</span>
      <a href="pages/grades.php" class="btn btn-outline btn-sm">Все →</a>
    </div>
    <div class="card-body-flush">
      <div class="table-wrapper">
        <table class="data-table">
          <thead><tr>
            <th>#</th><th>Студент</th><th>Группа</th><th>Ср. балл</th>
          </tr></thead>
          <tbody>
          <?php foreach($top_students as $i => $row): ?>
          <tr>
            <td><span class="badge <?= $i===0?'badge-gold':($i<3?'badge-blue':'badge-gray') ?>"><?= $i+1 ?></span></td>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><span class="badge badge-gray"><?= htmlspecialchars($row['group_name']) ?></span></td>
            <td>
              <span class="badge <?= $row['avg']>=4.5?'badge-green':($row['avg']>=4?'badge-blue':($row['avg']>=3?'badge-amber':'badge-red')) ?>">
                <?= $row['avg'] ?>
              </span>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if(empty($top_students)): ?>
          <tr><td colspan="4" class="empty-cell">Нет данных</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
const textColor  = getComputedStyle(document.documentElement).getPropertyValue('--color-text').trim();
const mutedColor = getComputedStyle(document.documentElement).getPropertyValue('--color-text-muted').trim();
const gridColor  = getComputedStyle(document.documentElement).getPropertyValue('--color-divider').trim();
const surfColor  = getComputedStyle(document.documentElement).getPropertyValue('--color-surface').trim();

const baseOpts = {
  responsive: true,
  maintainAspectRatio: true,
  plugins: { legend: { labels: { color: textColor, font: { family: 'Inter', size: 12 } } } },
  scales: {
    x: { ticks: { color: mutedColor }, grid: { color: gridColor } },
    y: { ticks: { color: mutedColor }, grid: { color: gridColor } }
  }
};

// Пончик — оценки
const gd = <?= json_encode($grade_dist) ?>;
new Chart(document.getElementById('gradeDistChart'), {
  type: 'doughnut',
  data: {
    labels: gd.map(r => 'Оценка ' + r.grade),
    datasets: [{ data: gd.map(r => r.cnt),
      backgroundColor: ['#dc2626','#d97706','#1a56db','#16a34a'],
      borderWidth: 3, borderColor: surfColor }]
  },
  options: { responsive: true, maintainAspectRatio: true,
    plugins: { legend: { labels: { color: textColor, font:{family:'Inter',size:12} } } } }
});

// Средний балл по группам
const gp = <?= json_encode($group_perf) ?>;
new Chart(document.getElementById('groupGradeChart'), {
  type: 'bar',
  data: {
    labels: gp.map(r => r.name),
    datasets: [{ label: 'Средний балл', data: gp.map(r => r.avg_grade),
      backgroundColor: '#1a56db', borderRadius: 6 }]
  },
  options: { ...baseOpts, scales: { ...baseOpts.scales, y: { ...baseOpts.scales.y, min: 0, max: 5 } } }
});

// Посещаемость по группам
const att = <?= json_encode($group_att) ?>;
new Chart(document.getElementById('attChart'), {
  type: 'bar',
  data: {
    labels: att.map(r => r.name),
    datasets: [
      { label: 'Присутствовали', data: att.map(r => r.present_cnt), backgroundColor: '#16a34a', borderRadius: 6 },
      { label: 'Всего записей',  data: att.map(r => r.total),       backgroundColor: '#cbd5e1', borderRadius: 6 }
    ]
  },
  options: baseOpts
});
</script>

<?php require_once 'includes/footer.php'; ?>
