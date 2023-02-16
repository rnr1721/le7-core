<?php

/**
 * @param array $params
 * @param Smarty_Internal_Template $smarty
 * @return string
 */
function smarty_function_img(array $params, Smarty_Internal_Template $smarty): string
{

    $getOptions = '';
    if (!empty($params['params'])) {
        if (is_array($params['params'])) {
            $getOptions = '?'.http_build_query($params['params']);
        } elseif (is_string($params['params'])) {
            $getOptions = '?'.$params['params'];
        }
    }

    $url = $smarty->getTemplateVars('url');

    $uri = ($params['src'] ?? '');
    $height = (isset($params['h']) ? ' height="'.$params['h'].'"' : '');
    $width = (isset($params['id']) ? ' width="'.$params['w'].'"' : '');
    $alt = (isset($params['a']) ? ' alt="'.$params['a'].'"' : '');
    $title = (isset($params['t']) ? ' title="'.$params['t'].'"' : '');
    $style = (isset($params['s']) ? ' style="'.$params['s'].'"' : '');
    return "<img$style$title$alt$height$width src=\"$url/$uri.$getOptions\" />";

}
