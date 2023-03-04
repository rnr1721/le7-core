<?php

declare(strict_types=1);

namespace App\Core\Instances;

class RouterApi extends Router {

    public function getRoute(string $uri, array $parsedRoute): array {

        $method = $this->request->getMethod();

        $actionPrefix = ucfirst(strtolower($method));
        $actionSuffix = 'Action';

        $result = $this->processRoute($uri,
                $method,
                $parsedRoute,
                $actionPrefix,
                $actionSuffix);

        $language = $this->request->getHeaderLine('Content-Language');
        if (!array_key_exists($language, $this->config->getLocales())) {
            $language = $this->config->getDefaultLanguage();
        }
        $result['language'] = $language;

        return $result;
    }

}
