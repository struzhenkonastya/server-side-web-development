<?php
declare(strict_types=1);

if (!defined('APP_BOOTSTRAPPED')) {
    http_response_code(404);
    exit;
}

function menu(): string
{
    $activeAction = currentAction();
    $activeSort = currentSort();
    $items = [
        'view' => ['Просмотр', '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18M9 10v10"/>'],
        'add' => ['Добавление записи', '<circle cx="10" cy="8" r="3"/><path d="M3 20v-2a7 7 0 0 1 12-5M19 13v8M15 17h8"/>'],
        'edit' => ['Редактирование записи', '<path d="M12 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h9"/><path d="m15 5 4 4M10 18l-1 3 3-1 9-9a3 3 0 0 0-4-4l-9 9"/>'],
        'delete' => ['Удаление записи', '<path d="M4 7h16M10 3h4M6 7l1 14h10l1-14M10 11v6M14 11v6"/>'],
    ];
    $html = '<nav class="main-menu" aria-label="Основное меню">';
    foreach ($items as $action => [$label, $icon]) {
        $class = 'menu-button' . ($activeAction === $action ? ' active' : '');
        $aria = $activeAction === $action ? ' aria-current="page"' : '';
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $icon . '</svg>';
        $html .= '<a class="' . $class . '" href="index.php?action=' . $action . '"' . $aria . '>' . $svg . '<span>' . e($label) . '</span></a>';
    }
    $html .= '</nav>';

    if ($activeAction === 'view') {
        $html .= '<div class="sort-area"><span class="sort-label">Сортировка контактов</span>';
        $html .= '<nav class="submenu" aria-label="Сортировка записей">';
        foreach ([
            'added' => 'По добавлению',
            'surname' => 'По фамилии',
            'birth' => 'По дате рождения',
        ] as $sort => $label) {
            $class = 'sort-button' . ($activeSort === $sort ? ' active' : '');
            $aria = $activeSort === $sort ? ' aria-current="page"' : '';
            $html .= '<a class="' . $class . '" href="index.php?action=view&amp;sort=' . $sort . '"' . $aria . '>' . e($label) . '</a>';
        }
        $html .= '</nav></div>';
    }
    return $html;
}
