<?php

declare(strict_types=1);

namespace Core\Routing;

class DispatcherApi extends Dispatcher
{

    /**
     * Get route data for API routes
     * @param string $uri Current URI
     * @param array $parsedRoute Parsed route
     * @param bool $notFound Predefined notFound
     * @return array
     */
    public function getRoute(string $uri, array $parsedRoute, bool $notFound = true): array
    {

        $method = $this->request->getMethod();

        // If GET or notfound (predefined), empty or methodPostAction,methodPutAction etc
        $actionPrefix = ($method === 'GET' || $notFound ? '' : ucfirst(strtolower($method)));
        $actionSuffix = 'Action';

        // Process the route
        $result = $this->processRoute($uri,
                $method,
                $parsedRoute,
                $actionPrefix,
                $actionSuffix,
                $notFound
        );

        // Try to detect language from request headers
        $language = $this->request->getHeaderLine('Content-Language');
        if (!array_key_exists($language, $this->locales)) {
            $language = $this->defaultLanguage;
        }
        $result['language'] = $language;

        return $result;
    }

}
