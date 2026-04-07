<?php // includes/footer.php ?>
  </main>

  <footer class="page-footer">
    <span>СВГТК им. Абая Кунанбаева — Информационная система «СВГТК Портал»</span>
    <span>Модуль: Аналитика и отчётность · <?= date('d.m.Y H:i') ?></span>
  </footer>

</div><!-- /.main-wrapper -->

<script>
// ── Тема ────────────────────────────────────────
(function(){
  const html = document.documentElement;
  const btn  = document.getElementById('themeToggle');
  let theme  = localStorage.getItem('svgtk-theme') ||
    (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
  html.setAttribute('data-theme', theme);
  function updateIcon(){
    btn.innerHTML = theme === 'dark'
      ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>'
      : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>';
  }
  updateIcon();
  btn && btn.addEventListener('click', () => {
    theme = theme === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', theme);
    try { localStorage.setItem('svgtk-theme', theme); } catch(e){}
    updateIcon();
  });
})();

// ── Sidebar collapse ─────────────────────────────
(function(){
  const sidebar     = document.getElementById('sidebar');
  const wrapper     = document.getElementById('mainWrapper');
  const toggle      = document.getElementById('sidebarToggle');
  const mobileBtn   = document.getElementById('mobileMenuBtn');

  function isDesktop(){ return window.innerWidth > 768; }

  // desktop collapse
  toggle && toggle.addEventListener('click', () => {
    if (!isDesktop()) return;
    sidebar.classList.toggle('collapsed');
    wrapper.classList.toggle('sidebar-collapsed');
    try { localStorage.setItem('svgtk-sidebar', sidebar.classList.contains('collapsed') ? '1' : '0'); } catch(e){}
  });

  // restore collapsed state on desktop
  try {
    if (isDesktop() && localStorage.getItem('svgtk-sidebar') === '1') {
      sidebar.classList.add('collapsed');
      wrapper.classList.add('sidebar-collapsed');
    }
  } catch(e){}

  // mobile open/close
  mobileBtn && mobileBtn.addEventListener('click', () => {
    sidebar.classList.toggle('mobile-open');
  });

  // close sidebar on outside click (mobile)
  document.addEventListener('click', (e) => {
    if (!isDesktop() && sidebar.classList.contains('mobile-open') &&
        !sidebar.contains(e.target) && e.target !== mobileBtn) {
      sidebar.classList.remove('mobile-open');
    }
  });
})();
</script>
</body>
</html>
