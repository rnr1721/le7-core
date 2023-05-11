<?php

declare(strict_types=1);

namespace Core\Routing;

use \Exception;

class DispatcherWeb extends Dispatcher
{

    /**
     * Get route data for WEB routes
     * @param string $uri Current URI
     * @param array $parsedRoute Parsed route
     * @param bool $notFound Predefined notFound
     * @return array
     * @throws Exception
     */
    public function getRoute(string $uri, array $parsedRoute, bool $notFound = true): array
    {

        $method = $this->request->getMethod();
        $lang = null;
        $ajax = $this->request->isAjax();
        // If GET or predefined notfound, methods names methodAction, in
        // other cases - methodPostAction, methodPutAction etc
        $actionPrefix = ($method === 'GET' || $notFound ? '' : ucfirst(strtolower($method)));
        $actionSuffix = 'Action';
        if ($ajax) {
            // Add Ajax suffix to called methods
            $actionSuffix = 'Ajax';
            $lang = $this->request->getHeaderLine('Content-Language');
        } else {
            // If it webpage, non-GET and non-POST requests disabled
            if ($method !== 'GET' && $method !== 'POST') {
                $notFound = true;
            }
        }

        // Process the route
        $result = $this->processRoute(
                $uri,
                $method,
                $parsedRoute,
                $actionPrefix,
                $actionSuffix,
                $notFound
        );

        // If cant detect route
        if ($result === null) {
            throw new Exception("DispatcherWeb::getRoute() route can not be null");
        }

        // If language not detected from URI, set default language
        if (!empty($lang)) {
            if (array_key_exists($lang, $this->locales)) {
                $result['language'] = $lang;
            } else {
                $result['language'] = $this->defaultLanguage;
            }
        }

        return $result;
    }

}
