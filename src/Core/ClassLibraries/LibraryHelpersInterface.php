<?php

namespace le7\Core\ClassLibraries;

use le7\Core\Helpers\ApiRequest;
use le7\Core\Helpers\UrlHelper;
use le7\Core\Helpers\ConsoleHelper;
use le7\Core\Helpers\FilesystemHelper;
use le7\Core\Helpers\StringHelper;
use le7\Core\Helpers\JsonHelper;
use le7\Core\Helpers\DateTimeHelper;
use le7\Core\View\HtmlTemplate;
use le7\Core\Helpers\ValidationHelperInterface;
use le7\Core\Helpers\ExcelHelper;

interface LibraryHelpersInterface {
    public function getString(): StringHelper;

    public function getJson(): JsonHelper;

    public function getDateTime(): DateTimeHelper;

    public function getHtmlTemplate(): HtmlTemplate;

    public function getValidator(): ValidationHelperInterface;

    public function getExcel(): ExcelHelper;

    public function getFilesystem(): FilesystemHelper;

    public function getConsole(): ConsoleHelper;

    public function getUrl(): UrlHelper;

    public function getApiRequest(): ApiRequest;
    
}
