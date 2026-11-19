(() => {
  'use strict';
  const toggle = document.getElementById('menuToggle');
  const sidebar = document.getElementById('sidebar');
  if (toggle && sidebar) {
    toggle.addEventListener('click', () => {
      const opened = sidebar.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', String(opened));
    });
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') { sidebar.classList.remove('is-open'); toggle.setAttribute('aria-expanded', 'false'); }
    });
    document.addEventListener('click', e => {
      if (sidebar.classList.contains('is-open') && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
        sidebar.classList.remove('is-open'); toggle.setAttribute('aria-expanded', 'false');
      }
    });
  }
  const form = document.getElementById('calcForm');
  if (form) {
    const params = { landing: [20,4], corporate: [44,6], shop: [85,8], webapp: [120,10] };
    const format = n => new Intl.NumberFormat('ru-RU').format(n) + ' ₽';
    const update = () => {
      const type = form.elements.type.value;
      const pages = Number(form.elements.pages.value);
      const integrations = Number(form.elements.integrations.value);
      if (!params[type] || !Number.isInteger(pages) || pages < 1 || pages > 60 || !Number.isInteger(integrations) || integrations < 0 || integrations > 10) return;
      let hours = params[type][0] + params[type][1] * pages + 14 * integrations;
      if (form.elements.design.value === 'custom') hours *= 1.18;
      if (document.getElementById('urgent').checked) hours *= 1.25;
      hours = Math.ceil(hours);
      document.getElementById('livePrice').textContent = format(hours * 1500);
      document.getElementById('liveHours').textContent = hours + ' ч';
    };
    form.addEventListener('input', update); form.addEventListener('change', update); update();
  }
})();
