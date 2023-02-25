<?php

declare(strict_types=1);

namespace le7\Core\Controllers\Main;

use le7\Core\View\Php\PhpViewFactory;
use le7\Core\View\Php\PhpEngine;

class WebPhp extends Web {

    protected PhpEngine $phpView;

    public function __construct(
            PhpViewFactory $phpViewFactory
    ) {
        parent::__construct();
        $this->phpView = $phpViewFactory->getPhpView();
    }

    /**
     * Assign variable in template
     * @param array|string|object $key
     * @param string|null $value
     * @return $this
     */
    public function assign(array|string|object $key, string $value = null): self {
        if (is_array($key) || is_object($key)) {
            foreach ($key as $k => $v) {
                $this->vars[$k] = $v;
            }
        } elseif (is_string($key)) {
            $this->vars[$key] = $value;
        }
        return $this;
    }

    /**
     * Render the template
     * @param string $template
     * @param array $data
     */
    public function render(string $template = 'layout.phtml', array $data = array(), int|null $cacheTimeSec = null) {
        $this->preRender();

        // if cached
        if ($cacheTimeSec !== null) {
            $this->tryEmitFromCache();
        }

        $this->assign($data);
        $rendered = $this->phpView->render($template, $this->vars);

        // if cache
        $this->tryAddToCache($rendered, $cacheTimeSec);

        $this->response->html->emit($rendered);
    }

}
