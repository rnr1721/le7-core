<?php

use App\Core\Helpers\UrlHelper;

/**
 * @param array $params
 * @param Smarty_Internal_Template $smarty
 * @return string
 */
function smarty_function_url(array $params, Smarty_Internal_Template $smarty): string {
    /** @var UrlHelper $url */
    $url = $smarty->getTemplateVars('url');

    $lang = ($params['l'] ?? '');
    $action = ($params['a'] ?? '');
    $controller = ($params['c'] ?? '');
    $p = ($params['p'] ?? '');
    $route = ($params['r'] ?? '');

    return $url->get($controller, $action, $p, $route, $lang);
}
