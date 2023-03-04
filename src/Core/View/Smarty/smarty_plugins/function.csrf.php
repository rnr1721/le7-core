<?php

use App\Core\Security\Csrf;

/**
 * @throws Exception
 */
function smarty_function_csrf(array $params, Smarty_Internal_Template $smarty): string {
    /** @var Csrf $csrf */
    $csrf = $smarty->getTemplateVars('csrf');
    $token = $csrf->toSession();
    $tokenKey = $token['key'];
    $tokenValue = $token['value'];
    $result = "<input type=\"hidden\" name=\"csrf_$tokenKey\" value=\"$tokenValue\">\r\n";
    return $result;
}
