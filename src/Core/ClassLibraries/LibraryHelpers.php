<?php

namespace le7\Core\ClassLibraries;

use le7\Core\Request\Request;
use le7\Core\Config\TopologyPublicInterface;
use le7\Core\Config\ConfigInterface;
use le7\Core\Locales\LocalesInterface;
use le7\Core\Config\TopologyFsInterface;
use le7\Core\Helpers\ValidationHelperInterface;
use le7\Core\Helpers\ApiRequest;
use le7\Core\Helpers\UrlHelper;
use le7\Core\Helpers\ConsoleHelper;
use le7\Core\Helpers\FilesystemHelper;
use le7\Core\Helpers\StringHelper;
use le7\Core\Helpers\JsonHelper;
use le7\Core\Helpers\DateTimeHelper;
use le7\Core\View\HtmlTemplate;
use le7\Core\Helpers\ValidationHelper;
use le7\Core\Helpers\ExcelHelper;
use le7\Core\ErrorHandling\ErrorLogInterface;
use Psr\Container\ContainerInterface;

class LibraryHelpers implements LibraryHelpersInterface {

    protected Request $request;
    protected TopologyPublicInterface $topologyWeb;
    protected ConfigInterface $config;
    protected LocalesInterface $locales;
    protected TopologyFsInterface $topologyFs;
    protected ErrorLogInterface $log;
    protected ContainerInterface $container;
    
    protected StringHelper $string;
    protected JsonHelper $json;
    protected DateTimeHelper $dateTime;
    protected HtmlTemplate $htmlTemplate;
    protected ValidationHelperInterface $validator;
    protected ExcelHelper $excel;
    protected FilesystemHelper $filesystem;
    protected ConsoleHelper $console;
    protected UrlHelper $url;
    protected ApiRequest $apiRequest;
    
    public function __construct(ErrorLogInterface $log, TopologyFsInterface $topologyFs, TopologyPublicInterface $topologyWeb, LocalesInterface $locales, ConfigInterface $config, Request $request) {
        $this->log = $log;
        $this->topologyFs = $topologyFs;
        $this->topologyWeb = $topologyWeb;
        $this->locales = $locales;
        $this->config = $config;
        $this->request = $request;
    }

    public function getString(): StringHelper {
        try {
            if (empty($this->string)) {
                $this->string = new StringHelper();
            }
            return $this->string;
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            $this->log->callError($e);
        }
    }

    public function getJson(): JsonHelper {
        try {
            if (empty($this->json)) {
                $this->json = new JsonHelper();
            }
            return $this->json;
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            $this->log->callError($e);
        }
    }

    public function getDateTime(): DateTimeHelper {
        try {
            if (empty($this->dateTime)) {
                $this->dateTime = new DateTimeHelper();
            }
            return $this->dateTime;
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            $this->log->callError($e);
        }
    }

    public function getHtmlTemplate(): HtmlTemplate {
        try {
            if (empty($this->htmlTemplate)) {
                $this->htmlTemplate = new HtmlTemplate($this->topology);
            }
            return $this->htmlTemplate;
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            $this->log->callError($e);
        }
    }

    public function getNewHtmlTemplate(): HtmlTemplate {
        try {
            return new HtmlTemplate($this->topologyFs);
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            $this->log->callError($e);
        }
    }
    
    public function getValidator(): ValidationHelperInterface {
        try {
            if (empty($this->validator)) {
                $this->validator = new ValidationHelper();
            }
            return $this->validator;
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            $this->log->callError($e);
        }
    }

    public function getExcel(): ExcelHelper {
        try {
            if (empty($this->excel)) {
                $this->excel = new ExcelHelper();
            }
            return $this->excel;
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            $this->log->callError($e);
        }
    }

    public function getFilesystem(): FilesystemHelper {
        try {
            if (empty($this->filesystem)) {
                $this->filesystem = new FilesystemHelper();
            }
            return $this->filesystem;
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            $this->log->callError($e);
        }
    }

    public function getConsole(): ConsoleHelper {
        try {
            if (empty($this->console)) {
                $this->console = new ConsoleHelper();
            }
            return $this->console;
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            $this->log->callError($e);
        }
    }

    public function getUrl(): UrlHelper {
        try {
            if (empty($this->url)) {
                $this->url = new UrlHelper($this->config, $this->topologyWeb, $this->locales,$this->request);
            }
            return $this->url;
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            $this->log->callError($e);
        }
    }

    public function getApiRequest(): ApiRequest {
        try {
            if (empty($this->apiRequest)) {
                $this->apiRequest = new ApiRequest();
            }
            return $this->apiRequest;
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            $this->log->callError($e);
        }
    }
    
}
