<?php
require_once '../includes/config.php';
$page_title = 'Посещаемость';
$pdo = getDB();

$selected_group = isset($_GET['group_id']) ? (int)$_GET['group_id'] : 0;
$groups = $pdo->query("SELECT * FROM `groups` ORDER BY name")->fetchAll();

$where = "WHERE 1=1";
if ($selected_group > 0) $where .= " AND g.id=" . $selected_group;

$rows = $pdo->query("
    SELECT s.full_name, g.name as group_name, sub.name as subject,
           a.date, a.status
    FROM attendance a
    JOIN students s   ON s.id   = a.student_id
    JOIN `groups` g   ON g.id   = s.group_id
    JOIN subjects sub ON sub.id = a.subject_id
    $where
    ORDER BY a.date DESC, g.name, s.full_name
")->fetchAll();

$summary = $pdo->query("
    SELECT
        COUNT(*) as total,
        SUM(a.status='present') as present,
        SUM(a.status='absent')  as absent,
        SUM(a.status='late')    as late,
        SUM(a.status='excused') as excused
    FROM attendance a
    JOIN students s ON s.id = a.student_id
    JOIN `groups` g ON g.id = s.group_id
    $where
")->fetch();

$pct = $summary['total'] > 0 ? round($summary['present'] / $summary['total'] * 100, 1) : 0;

$statusLabel = ['present'=>'Присутствовал','absent'=>'Отсутствовал','late'=>'Опоздал','excused'=>'Уважительная'];
$statusBadge = ['present'=>'badge-green','absent'=>'badge-red','late'=>'badge-amber','excused'=>'badge-blue'];

require_once '../includes/header.php';
?>
<div class="page-header">
  <div class="page-header-info">
    <h1 class="page-title">Посещаемость</h1>
    <p class="page-subtitle">Журнал посещений занятий студентами</p>
  </div>
  <div class="page-actions">
    <button class="btn btn-primary" onclick="window.print()">🖨 Печать</button>
  </div>
</div>

<form method="GET" class="filters-bar">
  <div class="form-group">
    <label class="form-label">Группа</label>
    <select name="group_id" class="form-control">
      <option value="0">Все группы</option>
      <?php foreach($groups as $g): ?>
      <option value="<?= $g['id'] ?>" <?= $selected_group==$g['id']?'selected':'' ?>>
        <?= htmlspecialchars($g['name']) ?>
      </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group" style="justify-content:flex-end">
    <button type="submit" class="btn btn-primary">Применить</button>
  </div>
</form>

<div class="card" style="margin-bottom:var(--space-6)">
  <div class="summary-strip">
    <div class="summary-item">
      <span class="summary-value"><?= $summary['total'] ?></span>
      <span class="summary-label">Всего записей</span>
    </div>
    <div class="summary-item">
      <span class="summary-value" style="color:var(--color-success)"><?= $summary['present'] ?></span>
      <span class="summary-label">Присутствовали</span>
    </div>
    <div class="summary-item">
      <span class="summary-value" style="color:var(--color-error)"><?= $summary['absent'] ?></span>
      <span class="summary-label">Отсутствовали</span>
    </div>
    <div class="summary-item">
      <span class="summary-value" style="color:var(--color-warning)"><?= $summary['late'] ?></span>
      <span class="summary-label">Опоздали</span>
    </div>
    <div class="summary-item">
      <span class="summary-value" style="color:var(--color-primary)"><?= $summary['excused'] ?></span>
      <span class="summary-label">Уважительных</span>
    </div>
    <div class="summary-item">
      <span class="summary-value <?= $pct>=80?'':''" style="color:<?= $pct>=80?'var(--color-success)':($pct>=60?'var(--color-warning)':'var(--color-error)') ?>"><?= $pct ?>%</span>
      <span class="summary-label">Явка</span>
    </div>
  </div>
  <div style="padding:var(--space-4) var(--space-6) var(--space-5)">
    <div style="display:flex;gap:var(--space-3);align-items:center">
      <div class="progress-bar-wrap" style="flex:1">
        <div class="progress-bar <?= $pct>=80?'green':($pct>=60?'amber':'red') ?>" style="width:<?= $pct ?>%"></div>
      </div>
      <span style="font-size:var(--text-sm);font-weight:700;min-width:44px"><?= $pct ?>%</span>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <span class="card-title">Журнал посещаемости</span>
    <span class="badge badge-blue"><?= count($rows) ?> записей</span>
  </div>
  <div class="card-body-flush">
    <div class="table-wrapper">
      <table class="data-table">
        <thead>
          <tr>
            <th>№</th><th>Студент</th><th>Группа</th>
            <th>Предмет</th><th>Дата</th><th>Статус</th>
          </tr>
        </thead>
        <tbody>
        <?php if (empty($rows)): ?>
          <tr><td colspan="6" class="empty-cell">Нет данных</td></tr>
        <?php else: ?>
          <?php foreach($rows as $i => $r): ?>
          <tr>
            <td style="color:var(--color-text-muted)"><?= $i+1 ?></td>
            <td><?= htmlspecialchars($r['full_name']) ?></td>
            <td><span class="badge badge-gray"><?= htmlspecialchars($r['group_name']) ?></span></td>
            <td><?= htmlspecialchars($r['subject']) ?></td>
            <td style="white-space:nowrap"><?= date('d.m.Y', strtotime($r['date'])) ?></td>
            <td><span class="badge <?= $statusBadge[$r['status']] ?>"><?= $statusLabel[$r['status']] ?></span></td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once '../includes/footer.php'; ?>
