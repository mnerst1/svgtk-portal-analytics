<?php
require_once '../includes/config.php';
$page_title = 'Студенты';
$pdo = getDB();

$selected_group = isset($_GET['group_id']) ? (int)$_GET['group_id'] : 0;
$groups = $pdo->query("SELECT * FROM `groups` ORDER BY name")->fetchAll();

$where = "WHERE s.status='active'";
if ($selected_group > 0) $where .= " AND s.group_id=" . $selected_group;

$students = $pdo->query("
    SELECT s.full_name, s.admission_year, g.name as group_name, g.specialty,
           ROUND(AVG(gr.grade),2) as avg_grade
    FROM students s
    JOIN `groups` g ON g.id = s.group_id
    LEFT JOIN grades gr ON gr.student_id = s.id AND gr.grade_type='final'
    $where
    GROUP BY s.id
    ORDER BY g.name, s.full_name
")->fetchAll();

require_once '../includes/header.php';
?>
<div class="page-header">
  <div class="page-header-info">
    <h1 class="page-title">Студенты</h1>
    <p class="page-subtitle">Список студентов колледжа</p>
  </div>
</div>

<form method="GET" class="filters-bar">
  <div class="form-group">
    <label class="form-label">Фильтр по группе</label>
    <select name="group_id" class="form-control" onchange="this.form.submit()">
      <option value="0">Все группы</option>
      <?php foreach($groups as $g): ?>
      <option value="<?= $g['id'] ?>" <?= $selected_group==$g['id']?'selected':'' ?>>
        <?= htmlspecialchars($g['name']) ?>
      </option>
      <?php endforeach; ?>
    </select>
  </div>
</form>

<div class="card">
  <div class="card-header">
    <span class="card-title">Список студентов</span>
    <span class="badge badge-blue"><?= count($students) ?> чел.</span>
  </div>
  <div class="card-body-flush">
    <div class="table-wrapper">
      <table class="data-table">
        <thead>
          <tr>
            <th>№</th><th>ФИО</th><th>Группа</th>
            <th>Специальность</th><th>Год поступления</th><th>Ср. балл</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($students as $i => $s): ?>
        <tr>
          <td style="color:var(--color-text-muted)"><?= $i+1 ?></td>
          <td><?= htmlspecialchars($s['full_name']) ?></td>
          <td><span class="badge badge-gray"><?= htmlspecialchars($s['group_name']) ?></span></td>
          <td style="max-width:200px;white-space:normal"><?= htmlspecialchars($s['specialty']) ?></td>
          <td><?= $s['admission_year'] ?></td>
          <td>
            <?php if($s['avg_grade']): ?>
              <span class="badge <?= $s['avg_grade']>=4.5?'badge-green':($s['avg_grade']>=4?'badge-blue':($s['avg_grade']>=3?'badge-amber':'badge-red')) ?>">
                <?= $s['avg_grade'] ?>
              </span>
            <?php else: ?><span style="color:var(--color-text-faint)">—</span><?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once '../includes/footer.php'; ?>
