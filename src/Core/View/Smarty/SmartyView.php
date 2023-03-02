<?php

namespace le7\Core\View\Smarty;

use le7\Core\View\ViewInterface;
use \Smarty;
use \SmartyException;
use \Exception;

class SmartyView implements ViewInterface
{

    private Smarty $smarty;

    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
    }

    public function render(string $layout, array $vars = []): string
    {
        try {
            $this->smarty->assign($vars);
            return $rendered = $this->smarty->fetch($layout);
        } catch (SmartyException | Exception $e) {
            $this->log->callError($e);
        }
    }

}
