<?php
require_once '../includes/config.php';
$page_title = 'Учебные группы';
$pdo = getDB();

$groups = $pdo->query("
    SELECT g.*, COUNT(DISTINCT s.id) as student_count,
           ROUND(AVG(gr.grade),2) as avg_grade
    FROM `groups` g
    LEFT JOIN students s  ON s.group_id = g.id AND s.status='active'
    LEFT JOIN grades gr   ON gr.student_id = s.id AND gr.grade_type='final'
    GROUP BY g.id
    ORDER BY g.name
")->fetchAll();

require_once '../includes/header.php';
?>
<div class="page-header">
  <div class="page-header-info">
    <h1 class="page-title">Учебные группы</h1>
    <p class="page-subtitle">Список всех групп колледжа</p>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <span class="card-title">Группы</span>
    <span class="badge badge-blue"><?= count($groups) ?> групп</span>
  </div>
  <div class="card-body-flush">
    <div class="table-wrapper">
      <table class="data-table">
        <thead>
          <tr>
            <th>№</th><th>Группа</th><th>Специальность</th>
            <th>Год поступления</th><th>Куратор</th><th>Студентов</th><th>Ср. балл</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($groups as $i => $g): ?>
        <tr>
          <td style="color:var(--color-text-muted)"><?= $i+1 ?></td>
          <td><strong><?= htmlspecialchars($g['name']) ?></strong></td>
          <td style="max-width:220px;white-space:normal"><?= htmlspecialchars($g['specialty']) ?></td>
          <td><?= $g['year_start'] ?></td>
          <td><?= htmlspecialchars($g['curator'] ?? '—') ?></td>
          <td><span class="badge badge-blue"><?= $g['student_count'] ?></span></td>
          <td>
            <?php if($g['avg_grade']): ?>
              <span class="badge <?= $g['avg_grade']>=4.5?'badge-green':($g['avg_grade']>=4?'badge-blue':($g['avg_grade']>=3?'badge-amber':'badge-red')) ?>">
                <?= $g['avg_grade'] ?>
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
