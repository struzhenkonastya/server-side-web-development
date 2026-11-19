<?php
declare(strict_types=1);
final class CalculatorController {
    private const TYPES = [
        'landing'=>['name'=>'Лендинг','base'=>20,'per_page'=>4],
        'corporate'=>['name'=>'Корпоративный сайт','base'=>44,'per_page'=>6],
        'shop'=>['name'=>'Интернет-магазин','base'=>85,'per_page'=>8],
        'webapp'=>['name'=>'Веб-приложение','base'=>120,'per_page'=>10],
    ];
    public static function index(): void {
        render('calculator', ['title'=>'Калькулятор — DevFlow','active'=>'calculator','result'=>null,'types'=>self::TYPES,'inputs'=>['type'=>'corporate','pages'=>5,'integrations'=>1,'design'=>'standard','urgent'=>'no']]);
    }
    public static function calculate(): void {
        verify_csrf();
        $type = post_str('type',30);
        $pages = filter_var($_POST['pages'] ?? null, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1,'max_range'=>60]]);
        $integrations = filter_var($_POST['integrations'] ?? null, FILTER_VALIDATE_INT, ['options'=>['min_range'=>0,'max_range'=>10]]);
        $design = post_str('design',20); $urgent = (($_POST['urgent'] ?? '') === 'yes') ? 'yes' : 'no';
        if (!isset(self::TYPES[$type]) || $pages === false || $integrations === false || !in_array($design,['standard','custom'],true) || !in_array($urgent,['yes','no'],true)) {
            http_response_code(422); render('calculator', ['title'=>'Калькулятор — DevFlow','active'=>'calculator','result'=>null,'types'=>self::TYPES,'error'=>'Проверьте введённые параметры: страницы 1–60, интеграции 0–10.', 'inputs'=>['type'=>'corporate','pages'=>5,'integrations'=>1,'design'=>'standard','urgent'=>'no']]); return;
        }
        $rate = 1500;
        $baseHours = self::TYPES[$type]['base'];
        $pageHours = self::TYPES[$type]['per_page'] * $pages;
        $integrationHours = 14 * $integrations;
        $hours = $baseHours + $pageHours + $integrationHours;
        if ($design === 'custom') $hours *= 1.18;
        if ($urgent === 'yes') $hours *= 1.25;
        $hours = (int)ceil($hours);
        $total = $hours * $rate;
        if (auth_id()) {
            try {
                $q=Database::get()->prepare('INSERT INTO estimates (user_id,project_type,pages,integrations,total_price) VALUES (?,?,?,?,?)');
                $q->execute([auth_id(),$type,$pages,$integrations,$total]);
            } catch (Throwable $e) { error_log('Estimate save: '.$e->getMessage()); }
        }
        render('calculator', ['title'=>'Расчёт проекта — DevFlow','active'=>'calculator','types'=>self::TYPES,'result'=>['total'=>$total,'hours'=>$hours,'rate'=>$rate,'baseHours'=>$baseHours,'pageHours'=>$pageHours,'integrationHours'=>$integrationHours,'typeName'=>self::TYPES[$type]['name']], 'inputs'=>compact('type','pages','integrations','design','urgent')]);
    }
}
