<?php
require_once '../includes/config.php';
$page_title = 'Успеваемость';
$pdo = getDB();

$selected_group = $_GET['group_id'] ?? '';
$selected_year  = $_GET['year']     ?? ACADEMIC_YEAR;
$selected_sem   = $_GET['semester'] ?? '';

$groups = $pdo->query("SELECT * FROM `groups` ORDER BY name")->fetchAll();

$where = "WHERE gr.grade_type='final' AND gr.academic_year=" . $pdo->quote($selected_year);
if ($selected_group) $where .= " AND g.id=" . (int)$selected_group;
if ($selected_sem)   $where .= " AND gr.semester=" . (int)$selected_sem;

$rows = $pdo->query("
  SELECT s.full_name, g.name as group_name, sub.name as subject,
         gr.grade, gr.semester, gr.academic_year
  FROM grades gr
  JOIN students s   ON s.id   = gr.student_id
  JOIN `groups` g   ON g.id   = s.group_id
  JOIN subjects sub ON sub.id = gr.subject_id
  $where
  ORDER BY g.name, s.full_name, sub.name
")->fetchAll();

$summary = $pdo->query("
  SELECT COUNT(*) as total,
    SUM(gr.grade=5) as cnt5, SUM(gr.grade=4) as cnt4,
    SUM(gr.grade=3) as cnt3, SUM(gr.grade=2) as cnt2,
    ROUND(AVG(gr.grade),2) as avg_grade
  FROM grades gr
  JOIN students s ON s.id=gr.student_id
  JOIN `groups` g ON g.id=s.group_id
  $where
")->fetch();

$by_group = $pdo->query("
  SELECT g.name, COUNT(*) as total,
    SUM(gr.grade=5) as cnt5, SUM(gr.grade=4) as cnt4,
    SUM(gr.grade=3) as cnt3, SUM(gr.grade=2) as cnt2,
    ROUND(AVG(gr.grade),2) as avg_grade
  FROM grades gr
  JOIN students s ON s.id=gr.student_id
  JOIN `groups` g ON g.id=s.group_id
  $where
  GROUP BY g.id, g.name ORDER BY avg_grade DESC
")->fetchAll();

require_once '../includes/header.php';
?>
<div class="page-header">
  <div class="page-header-info">
    <h1 class="page-title">Успеваемость</h1>
    <p class="page-subtitle">Итоговые оценки студентов по предметам</p>
  </div>
  <div class="page-actions">
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

<form method="GET" class="filters-bar">
  <div class="form-group">
    <label class="form-label">Группа</label>
    <select name="group_id" class="form-control">
      <option value="">Все группы</option>
      <?php foreach($groups as $g): ?>
      <option value="<?= $g['id'] ?>" <?= $selected_group==$g['id']?'selected':'' ?>>
        <?= htmlspecialchars($g['name']) ?>
      </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group">
    <label class="form-label">Учебный год</label>
    <select name="year" class="form-control">
      <option value="2024-2025" <?= $selected_year=='2024-2025'?'selected':'' ?>>2024–2025</option>
      <option value="2023-2024" <?= $selected_year=='2023-2024'?'selected':'' ?>>2023–2024</option>
      <option value="2022-2023" <?= $selected_year=='2022-2023'?'selected':'' ?>>2022–2023</option>
    </select>
  </div>
  <div class="form-group">
    <label class="form-label">Семестр</label>
    <select name="semester" class="form-control">
      <option value="">Оба семестра</option>
      <option value="1" <?= $selected_sem=='1'?'selected':'' ?>>1 семестр</option>
      <option value="2" <?= $selected_sem=='2'?'selected':'' ?>>2 семестр</option>
    </select>
  </div>
  <div class="form-group" style="justify-content:flex-end">
    <button type="submit" class="btn btn-primary">Применить</button>
  </div>
</form>

<!-- Сводка -->
<div class="card" style="margin-bottom:var(--space-5)">
  <div class="summary-strip">
    <div class="summary-item">
      <span class="summary-value"><?= $summary['total'] ?></span>
      <span class="summary-label">Всего оценок</span>
    </div>
    <div class="summary-item">
      <span class="summary-value" style="color:var(--color-success)"><?= $summary['cnt5'] ?></span>
      <span class="summary-label">Отлично (5)</span>
    </div>
    <div class="summary-item">
      <span class="summary-value" style="color:var(--color-primary)"><?= $summary['cnt4'] ?></span>
      <span class="summary-label">Хорошо (4)</span>
    </div>
    <div class="summary-item">
      <span class="summary-value" style="color:var(--color-warning)"><?= $summary['cnt3'] ?></span>
      <span class="summary-label">Удовл. (3)</span>
    </div>
    <div class="summary-item">
      <span class="summary-value" style="color:var(--color-error)"><?= $summary['cnt2'] ?></span>
      <span class="summary-label">Неудовл. (2)</span>
    </div>
    <div class="summary-item">
      <span class="summary-value"><?= $summary['avg_grade'] ?? '—' ?></span>
      <span class="summary-label">Средний балл</span>
    </div>
  </div>
</div>

<!-- По группам -->
<?php if (!$selected_group && count($by_group) > 0): ?>
<div class="card" style="margin-bottom:var(--space-5)">
  <div class="card-header"><span class="card-title">Сводка по группам</span></div>
  <div class="card-body-flush">
    <div class="table-wrapper">
      <table class="data-table">
        <thead><tr>
          <th>Группа</th><th>Всего</th><th>«5»</th><th>«4»</th><th>«3»</th><th>«2»</th><th>Ср. балл</th>
        </tr></thead>
        <tbody>
        <?php foreach($by_group as $bg): ?>
        <tr>
          <td><strong><?= htmlspecialchars($bg['name']) ?></strong></td>
          <td><?= $bg['total'] ?></td>
          <td style="color:var(--color-success)"><?= $bg['cnt5'] ?></td>
          <td style="color:var(--color-primary)"><?= $bg['cnt4'] ?></td>
          <td style="color:var(--color-warning)"><?= $bg['cnt3'] ?></td>
          <td style="color:var(--color-error)"><?= $bg['cnt2'] ?></td>
          <td>
            <span class="badge <?= $bg['avg_grade']>=4.5?'badge-green':($bg['avg_grade']>=4?'badge-blue':($bg['avg_grade']>=3?'badge-amber':'badge-red')) ?>">
              <?= $bg['avg_grade'] ?>
            </span>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Подробная таблица -->
<div class="card">
  <div class="card-header">
    <span class="card-title">Итоговые оценки</span>
    <span class="badge badge-blue"><?= count($rows) ?> записей</span>
  </div>
  <div class="card-body-flush">
    <div class="table-wrapper">
      <table class="data-table">
        <thead><tr>
          <th>Студент</th><th>Группа</th><th>Предмет</th><th>Семестр</th><th>Оценка</th>
        </tr></thead>
        <tbody>
        <?php if (empty($rows)): ?>
        <tr><td colspan="5" class="empty-cell">Нет данных для выбранных параметров</td></tr>
        <?php else: ?>
        <?php foreach($rows as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['full_name']) ?></td>
          <td><span class="badge badge-gray"><?= htmlspecialchars($r['group_name']) ?></span></td>
          <td><?= htmlspecialchars($r['subject']) ?></td>
          <td style="text-align:center"><?= $r['semester'] ?></td>
          <td><span class="badge grade-<?= $r['grade'] ?>"><?= $r['grade'] ?></span></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once '../includes/footer.php'; ?>
