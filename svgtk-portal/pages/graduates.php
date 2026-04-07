<?php
require_once '../includes/config.php';
$page_title = 'Выпускники';
$pdo = getDB();

$rows = $pdo->query("
    SELECT s.full_name, g.name as group_name, g.specialty,
           gr.graduation_year, gr.diploma_grade, gr.employment_status, gr.employer
    FROM graduates gr
    JOIN students s ON s.id = gr.student_id
    JOIN `groups` g ON g.id = s.group_id
    ORDER BY gr.graduation_year DESC, s.full_name
")->fetchAll();

$summary = $pdo->query("
    SELECT
        COUNT(*) as total,
        SUM(diploma_grade='red')           as red_diploma,
        SUM(employment_status='employed')  as employed,
        SUM(employment_status='studying')  as studying,
        SUM(employment_status='unemployed') as unemployed
    FROM graduates
")->fetch();

$empLabel = ['employed'=>'Трудоустроен','unemployed'=>'Не трудоустроен','studying'=>'Продолжает учёбу','unknown'=>'Нет данных'];
$empBadge = ['employed'=>'badge-green','unemployed'=>'badge-red','studying'=>'badge-blue','unknown'=>'badge-gray'];
$dipLabel = ['red'=>'Красный','blue'=>'Синий'];
$dipBadge = ['red'=>'badge-gold','blue'=>'badge-blue'];

require_once '../includes/header.php';
?>
<div class="page-header">
  <div class="page-header-info">
    <h1 class="page-title">Выпускники</h1>
    <p class="page-subtitle">Сведения о выпуске и трудоустройстве</p>
  </div>
  <div class="page-actions">
    <button class="btn btn-primary" onclick="window.print()">🖨 Печать</button>
  </div>
</div>

<div class="kpi-grid">
  <div class="kpi-card">
    <div class="kpi-card-header"><span class="kpi-label">Всего выпускников</span><div class="kpi-icon blue">🎓</div></div>
    <div class="kpi-value"><?= $summary['total'] ?></div>
    <div class="kpi-change">За все годы</div>
  </div>
  <div class="kpi-card">
    <div class="kpi-card-header"><span class="kpi-label">Красный диплом</span><div class="kpi-icon gold">🏆</div></div>
    <div class="kpi-value"><?= $summary['red_diploma'] ?></div>
    <div class="kpi-change">Отличников</div>
  </div>
  <div class="kpi-card">
    <div class="kpi-card-header"><span class="kpi-label">Трудоустроены</span><div class="kpi-icon green">💼</div></div>
    <div class="kpi-value"><?= $summary['employed'] ?></div>
    <div class="kpi-change">Работают по специальности</div>
  </div>
  <div class="kpi-card">
    <div class="kpi-card-header"><span class="kpi-label">Продолжают учёбу</span><div class="kpi-icon blue">📚</div></div>
    <div class="kpi-value"><?= $summary['studying'] ?></div>
    <div class="kpi-change">В вузах и колледжах</div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <span class="card-title">Список выпускников</span>
    <span class="badge badge-blue"><?= count($rows) ?> чел.</span>
  </div>
  <div class="card-body-flush">
    <div class="table-wrapper">
      <table class="data-table">
        <thead>
          <tr>
            <th>№</th><th>ФИО</th><th>Группа</th><th>Год выпуска</th>
            <th>Диплом</th><th>Статус</th><th>Место работы / учёбы</th>
          </tr>
        </thead>
        <tbody>
        <?php if (empty($rows)): ?>
          <tr><td colspan="7" class="empty-cell">Нет данных</td></tr>
        <?php else: ?>
          <?php foreach($rows as $i => $r): ?>
          <tr>
            <td style="color:var(--color-text-muted)"><?= $i+1 ?></td>
            <td><?= htmlspecialchars($r['full_name']) ?></td>
            <td><span class="badge badge-gray"><?= htmlspecialchars($r['group_name']) ?></span></td>
            <td><?= $r['graduation_year'] ?></td>
            <td><span class="badge <?= $dipBadge[$r['diploma_grade']] ?>"><?= $dipLabel[$r['diploma_grade']] ?></span></td>
            <td><span class="badge <?= $empBadge[$r['employment_status']] ?>"><?= $empLabel[$r['employment_status']] ?></span></td>
            <td><?= $r['employer'] ? htmlspecialchars($r['employer']) : '<span style="color:var(--color-text-faint)">—</span>' ?></td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once '../includes/footer.php'; ?>
