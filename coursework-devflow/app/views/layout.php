<?php $active = $active ?? ''; $title = $title ?? 'DevFlow — пространство разработчика'; $flash = flash_take(); ?>
<!doctype html><html lang="ru"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="DevFlow — учебное веб-приложение для разработчика: задачи, статьи, калькулятор проектов и обратная связь.">
<title><?=h($title)?></title>
<link rel="stylesheet" href="<?=h(url('assets/style.css'))?>?v=5">
<script src="<?=h(url('assets/app.js'))?>?v=5" defer></script>
</head><body>
<div class="app-shell">
<aside class="sidebar" id="sidebar" aria-label="Основное меню">
  <a class="brand" href="<?=h(url())?>"><span class="brand-symbol">D<span>.</span></span><span>devflow<small>developer workspace</small></span></a>
  <div class="nav-section-label">РАБОЧЕЕ ПРОСТРАНСТВО</div>
  <nav class="side-nav">
    <a class="nav-item <?=$active==='home'?'active':''?>" href="<?=h(url())?>"><span class="nav-glyph">◫</span> Обзор <span class="nav-arrow">›</span></a>
    <a class="nav-item <?=$active==='workspace'?'active':''?>" href="<?=h(url('workspace'))?>"><span class="nav-glyph">☷</span> Мои задачи <span class="nav-arrow">›</span></a>
    <a class="nav-item <?=$active==='articles'?'active':''?>" href="<?=h(url('articles'))?>"><span class="nav-glyph">▤</span> База знаний <span class="nav-arrow">›</span></a>
    <a class="nav-item <?=$active==='calculator'?'active':''?>" href="<?=h(url('calculator'))?>"><span class="nav-glyph">⌘</span> Калькулятор <span class="nav-arrow">›</span></a>
  </nav>
  <div class="nav-section-label second-label">КОММУНИКАЦИЯ</div>
  <nav class="side-nav"><a class="nav-item <?=$active==='contact'?'active':''?>" href="<?=h(url('contact'))?>"><span class="nav-glyph">✉</span> Обратная связь <span class="nav-arrow">›</span></a></nav>
  <div class="sidebar-bottom"><div class="side-note"><span class="side-note-icon">✦</span><strong>Ваш следующий шаг</strong><p>Планируйте задачи, делитесь знаниями, создавайте проекты.</p><a href="<?=h(url('workspace'))?>">Открыть пространство →</a></div>
  <div class="side-foot"><span class="online-dot"></span> Учебный проект · PHP + MySQL</div></div>
</aside>
<div class="main-column">
<header class="topbar"><button class="menu-toggle" id="menuToggle" type="button" aria-label="Открыть меню" aria-expanded="false">☰</button>
  <div class="breadcrumbs"><span class="bread-root">DEVFLOW</span><span class="bread-sep">/</span><span><?=h(match($active){'home'=>'Обзор','workspace'=>'Мои задачи','articles'=>'База знаний','calculator'=>'Калькулятор','contact'=>'Обратная связь',default=>'Аккаунт'})?></span></div>
  <div class="top-actions"><span class="system-status"><span class="online-dot"></span> System online</span><span class="top-divider"></span>
  <?php if(auth_id()): ?><span class="user-chip"><span class="avatar"><?=h(strtoupper(substr(user_name(),0,1)))?></span><span><?=h(user_name())?></span></span>
  <form method="post" action="<?=h(url('logout'))?>" class="logout-form"><?=csrf_field()?><button type="submit" class="text-action">Выйти</button></form>
  <?php else: ?><a class="top-login" href="<?=h(url('login'))?>">Войти <span>↗</span></a><?php endif; ?>
  </div></header>
<main class="main-content" id="mainContent">
<?php if($flash): ?><div class="alert <?=$flash['type']==='success'?'alert-success':'alert-danger'?>" role="status"><?=h($flash['message'])?></div><?php endif; ?>
<?=$content?>
</main>
<footer class="footer"><span>© <?=date('Y')?> DevFlow. Курсовой проект по серверной веб-разработке</span><span>Built with <strong>Flight PHP</strong> · MySQL · JavaScript</span></footer>
</div></div></body></html>
