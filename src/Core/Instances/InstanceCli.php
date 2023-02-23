<?php

declare(strict_types=1);

namespace le7\Core\Instances;

use le7\Core\EventDispatcher\EventInvoker;
use le7\Core\Php;
use le7\Core\ErrorHandling\ErrorLogInterface;
use le7\Core\Config\TopologyFsInterface;
use le7\Core\Config\ConfigInterface;
use le7\Core\ErrorHandling\ErrorHandlerCliFactory;
use le7\Core\Traits\ConsoleTrait;
use le7\Core\Helpers\ConsoleHelper;
use le7\Core\Locales\LocalesInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class InstanceCli extends InstanceAbstract implements InstanceInterface {

    use ConsoleTrait;

    private ErrorHandlerCliFactory $errorHandlerCliFactory;
    private RouteRunnerCli $routeRunner;
    private ConsoleHelper $consoleHelper;

    public function __construct(
            ContainerInterface $container,
            ConfigInterface $config,
            TopologyFsInterface $topology,
            LoggerInterface $systemLog,
            ErrorLogInterface $log,
            EventInvoker $eventInvoker,
            Php $php,
            LocalesInterface $locales,
            ErrorHandlerCliFactory $errorHandlerCliFactory,
            RouteRunnerCli $routeRunner,
            ConsoleHelper $consoleHelper
    ) {
        parent::__construct($container, $config, $topology, $systemLog, $log, $eventInvoker, $php, $locales);
        $this->routeRunner = $routeRunner;
        $this->consoleHelper = $consoleHelper;
        $this->errorHandlerCliFactory = $errorHandlerCliFactory;
    }

    public function startInstance(RouteInterface $route): RouteRunnerInterface {

        $options = $route->getOptions();

        $this->locales->setLocale($route->getLanguage());

        if ($options['silent'] !== 'yes') {
            $this->showHello($route->getController(), $route->getAction(), $options['help'], $route->getLanguage());
        }

        if ($options['help'] === 'yes') {
            $this->showHelp();
            exit;
        }

        $this->errorHandlerCliFactory->getErrorHandler();

        $this->eventInvoker->processEvents();

        return $this->routeRunner;
    }

    private function showHello(string $controller, string $action, string $isHelp, string $language) {
        $this->stdout($this->consoleHelper->colorMessage("LameEngine 7 - (" . $this->config->getProjectName() . ") \r\n", "green"));
        if ($isHelp !== 'yes') {
            $this->stdout("Available options: -h or --help \r\n");
            $this->stdout("Language:" . ' ' . $language . "\r\n");
            $this->stdout("Run controller:" . ' ' . $controller . "\r\n");
            $this->stdout("Run action:" . ' ' . $action . "\r\n");
        }
    }

    private function showHelp() {
        $this->stdout("-h or --help   : Show this help message\r\n");
        $this->stdout("-s or --silent : Hide program greeting\r\n");
        $this->stdout("--l            : Set language, e.g. \"ru\"\r\n");
        $this->stdout("--c            : Set command - controller and Action separated by ':'\r\n");
        $this->stdout("--p2           : Set parameter 2\r\n");
        $this->stdout("--p3           : Set parameter 3\r\n");
        $this->stdout("--p4           : Set parameter 4\r\n");
        $this->stdout("--p5           : Set parameter 5\r\n");
        $this->stdout("--p6           : Set parameter 6\r\n");
        $this->stdout("--p7           : Set parameter 7\r\n");
        $this->stdout("Example1: php ./cli.php --c mygreatclicontroller:mygreatcliaction \r\n");
        $this->stdout("Example2: php ./cli.php --c reports -s \r\n");
        $this->stdout("Example3: php ./cli.php --c reports:pdf -s \r\n");
        $this->stdout("Example3: php ./cli.php --c reports:sendxls -s --p2 admin@gmail.com --p3 director@gmail.com \r\n");
        $this->stdout("Bye!\r\n");
        $this->stdout("\r\n");
    }

}
