<?php

declare(strict_types=1);

namespace le7\Core\Middleware\System;

use le7\Core\Config\CodePartsFactory;
use le7\Core\Config\PublicEnvFactory;
use le7\Core\Config\UserConfigInterface;
use le7\Core\Locales\LocalesInterface;
use le7\Core\Config\ConfigInterface;
use le7\Core\Request\Request;
use le7\Core\Config\TopologyPublicInterface;
use le7\Core\Helpers\UrlHelper;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class WebpageContentMiddleware implements MiddlewareInterface
{

    private CodePartsFactory $codePartsFactory;
    private LocalesInterface $locales;
    private UserConfigInterface $uconfig;
    private ConfigInterface $config;
    private Request $request;
    private TopologyPublicInterface $topologyWeb;
    private UrlHelper $urlHelper;
    private PublicEnvFactory $publicEnvFactory;

    public function __construct(
            UserConfigInterface $uconfig,
            LocalesInterface $locales,
            ConfigInterface $config,
            Request $request,
            UrlHelper $urlHelper,
            TopologyPublicInterface $topologyWeb,
            PublicEnvFactory $publicEnvFactory,
            CodePartsFactory $codePartsFactory
    )
    {
        $this->config = $config;
        $this->uconfig = $uconfig;
        $this->request = $request;
        $this->urlHelper = $urlHelper;
        $this->topologyWeb = $topologyWeb;
        $this->locales = $locales;
        $this->publicEnvFactory = $publicEnvFactory;
        $this->codePartsFactory = $codePartsFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        /** @var \le7\Core\Instances\RouteHttpInterface $route */
        $route = $request->getAttribute('route');

        $otherLanguages = $this->urlHelper->getLanguageUrlVariants($route);

        $publicEnv = $this->publicEnvFactory->getEnvHtml();

        $webpage = array(
            'config' => $this->config,
            'uconfig' => $this->uconfig,
            'url' => $this->urlHelper,
            'urlLibs' => $this->topologyWeb->getLibsUrl(),
            'urlThemes' => $this->topologyWeb->getThemesUrl(),
            'urlCss' => $this->topologyWeb->getCssUrl(),
            'urlJs' => $this->topologyWeb->getJsUrl(),
            'urlTheme' => $this->topologyWeb->getThemeUrl(),
            'urlImages' => $this->topologyWeb->getImagesUrl(),
            'urlFonts' => $this->topologyWeb->getFontsUrl(),
            'projectName' => $this->config->getProjectName(),
            'lang' => $this->locales->getCurrentLocaleShortname(),
            'languages' => $this->locales->getLocalesByShortname(),
            'otherLanguages' => $otherLanguages,
            'env' => $publicEnv->export(),
            'snippets_top' => $this->codePartsFactory->getStatTop() ?? '',
            'snippets_middle' => $this->codePartsFactory->getStatMiddle() ?? '',
            'snippets_bottom' => $this->codePartsFactory->getStatBottom() ?? ''
        );

        $this->request->setAttribute('cacheLifetime', $this->config->getCacheLifetime());
        $this->request->setAttribute('webpage', $webpage);

        return $response;
    }

}
