<?php

declare(strict_types=1);

namespace le7\Core\Instances;

class RouterWeb extends Router {

    public function getRoute(string $uri, array $parsedRoute): array {

        $lang = null;
        $ajax = $this->request->isAjax();
        $actionPrefix = '';
        $actionSuffix = 'Action';
        if ($ajax) {
            $actionPrefix = ucfirst(strtolower($this->request->getMethod()));
            $actionSuffix = 'Ajax';
            $lang = $this->request->getHeaderLine('Content-Language');
        }

        $method = $this->request->getMethod();

        $result = $this->processRoute(
                $uri,
                $method,
                $parsedRoute,
                $actionPrefix,
                $actionSuffix);

        if (!empty($lang)) {
            if (array_key_exists($lang, $this->config->getLocales())) {
                $result['language'] = $lang;
            } else {
                $result['language'] = $this->config->getDefaultLanguage();
            }
        }

        return $result;
    }

}
