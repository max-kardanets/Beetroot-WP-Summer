<?php
function get_all_actions() {
    global $wp_actions;

    $styles = <<<HTML
<style>
.actions_wrap {
    max-width: 1200px;
    margin: 0 auto;
    font-size: 16px;
}

.actions_message {
    background: #a6d485;
    padding: 20px;
    margin-bottom: 20px;
    color: #000;
}

.actions_table tr.highlight {
    background: #fff4bb;
}

.actions_table tr:hover {
    background: #fff;
}

.actions_table td,
.actions_table th {
    border: 1px solid #ccc;
}

.actions_table th {
    text-align: center;
}

.actions_table .action_description p {
    margin-bottom: 1em;
}

.actions_table .action_description p:last-child {
    margin: 0;
}
</style>
HTML;

    $html = <<<HTML
<table class="actions_table">
<tr>
    <th>Название экшена</th>
    <th>Описание экшена</th>
</tr>
HTML;

    foreach($wp_actions as $action_name => $action_count) {
        $highlight = false;

        switch($action_name) {
            case 'muplugins_loaded':
                $action_description = <<<HTML
<p>Срабатывает после загрузки всех обязательных и активируемых по сети плагинов.</p>
HTML;
                break;
            case 'registered_taxonomy':
                $action_description = 'Срабатывает после регистрации таксономии.';
                break;
            case 'registered_post_type':
                $action_description = 'Срабатывает после регистрации Post Type.';
                break;
            case 'plugins_loaded':
                $action_description = 'Срабатывает сразу после того, как все активированные плагины загрузились.';
                break;
            case 'wp_roles_init':
                $action_description = 'Срабатывает после инициализации ролей, позволяя плагинам добавлять свои собственные роли.';
                break;
            case 'setup_theme':
                $action_description = 'Срабатывает до загрузки темы.';
                break;
            case 'after_setup_theme':
                $highlight = true;
                $action_description = <<<HTML
<p>Срабатывает сразу после того, как тема была инициализирована (когда был подключен functions.php). Обычно используется для того, чтобы установить базовые возможности темы: см. add_theme_support().</p>
<p>В отличии от хука init, на момент срабатывания этого хука, wordpress еще не определил авторизован пользователь или нет.</p>
<p>Если в functions.php темы вы пытаетесь повесить функцию на экшен, который выполняется раньше чем after_setup_theme, то есть вероятность, что ваш код не сработает.</p>
HTML;
                break;
            case 'set_current_user':
                $action_description = 'Срабатывает после установки текущего пользователя.';
                break;
            case 'init':
                $highlight = true;
                $action_description = <<<HTML
<p>Срабатывает после того, как WordPress полностью загружен, но до того, как любые header заголовки (headers_list()) были отправлены.</p>
<p>init - это популярный экшен, обычно используется плагинами для инициализации себя.</p>
<p>К моменту срабатывания init уже установлены все основные глобальные переменные и функции WordPress и активной темы (functions.php).</p>
HTML;
                break;
            case 'wp_loaded':
                $highlight = true;
                $action_description = 'Срабатывает после того, как WP, все плагины и тема были полностью загружены и инициализированы.';
                break;
            case 'wp':
                $highlight = true;
                $action_description = <<<HTML
<p>Срабатывает сразу после того, как глобальный объект WP установлен: определена глобальная переменная \$wp. Хук срабатывает в конце функции wp().</p>
<p>Начиная с этого экшена становятся доступны условные теги (is_*)</p>
HTML;
                break;
            case 'template_redirect':
                $action_description = <<<HTML
<p>Срабатывает перед тем, как WordPress определит какой файл шаблона использовать для вывода контента.</p>
<p>Событие удобно использовать для перенаправления, когда WordPress обработал основной запрос и установил все объекты (\$wp_query, \$post, условные теги (is_*)), но вывод контента на экран еще не произошёл.
<p>Это популярный хук и самое удобное место, когда для принятия решения о перенаправлении нужны все данные о текущем запросе (обрабатываемом объекте WordPress).</p>
HTML;
                break;
            default:
                $action_description = null;
        }

        $css_highlight = ($highlight) ? 'highlight' : null;

        $html .= <<<HTML
<tr class="{$css_highlight}">
    <td>{$action_name} (x{$action_count})</td>
    <td class="action_description">{$action_description}</td>
</tr>
HTML;
    }
    $html .= '</table>';

    $total_actions = count($wp_actions);

    $message = '<div class="actions_message">Для рендеринга этой страницы было задействовано '. $total_actions .' экшенов.</div>';

    echo $styles .'<div class="actions_wrap">'. $message . $html .'</div>';
}
//get_all_actions();
add_action('shutdown', 'get_all_actions');