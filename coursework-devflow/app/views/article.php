<a class="back-link" href="<?=h(url('articles'))?>">← Все статьи</a>
<article class="reading-card"><span class="reading-kicker"><?=h($article['category'])?> <span>·</span> <?=(int)$article['minutes']?> минут чтения</span><h1><?=h($article['title'])?></h1>
<div class="article-author"><span class="avatar author-avatar"><?=h(strtoupper(substr($nickname,0,1)))?></span><div><strong><?=h($nickname)?></strong><small>Автор материала · <?=h(date('d.m.Y',strtotime($article['created_at'])))?></small></div></div>
<div class="article-cover"><span>&lt; / &gt;</span><div class="cover-circles"></div></div>
<div class="prose"><?php foreach(preg_split('/\n\s*\n/',(string)$article['body']) as $p): ?><p><?=nl2br(h($p))?></p><?php endforeach;?></div>
<div class="article-end"><span>✦ Понравился материал? Сохраняйте знания и применяйте их на практике.</span><a href="<?=h(url('articles'))?>">Другие статьи →</a></div></article>
