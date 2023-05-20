<?php

declare(strict_types=1);

namespace Core\Console;

use Core\Interfaces\ConfigInterface;
use Core\Interfaces\RouteCliInterface;
use Core\Interfaces\LocalesInterface;
use Core\ErrorHandler\ErrorHandlerCli;
use Core\Console\ColorMessage;
use Core\Routing\DispatcherReflection;
use Core\Routing\RunnerTrait;
use Core\Console\ConsoleTrait;
use Psr\Container\ContainerInterface;

class CliHandler
{

    use RunnerTrait;
    use ConsoleTrait;

    protected ColorMessage $color;
    protected ConfigInterface $config;
    protected LocalesInterface $locales;
    protected ErrorHandlerCli $errorHandler;
    protected ContainerInterface $container;
    protected DispatcherReflection $reflection;

    public function __construct(
            ContainerInterface $container,
            ConfigInterface $config,
            LocalesInterface $locales,
            ErrorHandlerCli $errorHandler,
            ColorMessage $color,
            DispatcherReflection $refletction
    )
    {
        $this->config = $config;
        $this->locales = $locales;
        $this->errorHandler = $errorHandler;
        $this->container = $container;
        $this->color = $color;
        $this->reflection = $refletction;
    }

    /**
     * Handle the current CLI route
     * @param RouteCliInterface $route
     * @return void
     */
    public function handle(RouteCliInterface $route): void
    {

        $options = $route->getOptions();

        // Set the current locale
        $this->locales->setLocale($route->getLanguage());

        if (!$options['silent']) {
            $this->showHello($route->getController(), $route->getAction(), $options['help'], $route->getLanguage());
        }

        $props = $this->reflection->getClassProperties($route->getControllerClass(), 'int,float,string,bool');

        if ($options['help']) {
            $this->showHelp();

            $title = $this->color->cyan('Options for controller ' . $route->getController());
            $this->stdout('');
            $this->stdout($title);
            foreach ($props as $prop) {
                if (str_starts_with($prop['name'], 'opt_')) {
                    $option = '--' . str_replace('opt_', '', $prop['name']);
                    $description = $prop['annotation'];
                    $default = (empty($prop['default']) ? ' (required)' : ' (default:' . $prop['default'] . ')');
                    $this->stdout($option . $default . ' ' . $description);
                }
            }
            $this->stdout('');
            $this->stdout("Bye!");
            exit;
        }

        $controller = $this->container->get($route->getControllerClass());

        $params = $route->getParams();

        foreach ($props as $prop) {
            if (str_starts_with($prop['name'], 'opt_')) {
                $propName = str_replace('opt_', '', $prop['name']);
                if (!$prop['default'] && !array_key_exists($propName, $params)) {
                    $this->stderr($this->color->red("Fuck! Required param:--" . $propName));
                    $this->stdout("Please run this command with --help option for more information");
                    exit;
                }
                if (isset($params[$propName])) {
                    if ($prop['type'] === 'string') {
                        $controller->{$prop['name']} = strval($params[$propName]);
                    } elseif ($prop['type'] === 'int') {
                        $controller->{$prop['name']} = intval($params[$propName]);
                    } elseif ($prop['type'] === 'float') {
                        $controller->{$prop['name']} = floatval($params[$propName]);
                    } elseif ($prop['type'] === 'bool') {
                        $controller->{$prop['name']} = boolval($params[$propName]);
                    }
                }
            }
        }

        $this->runAction($controller, $route->getActionMethod());
    }

    /**
     * Show startup message for console command
     * @param string $controller Current controller
     * @param string $action Current action
     * @param bool $isHelp If help option applied
     * @param string $language Language that need
     * @return void
     */
    private function showHello(
            string $controller,
            string $action,
            bool $isHelp,
            string $language
    ): void
    {
        $this->stdout($this->color->green("lame Buddy 7 - (" . $this->config->string('projectName', "My project") . ")"));
        if (!$isHelp) {
            $this->stdout("Available options: -h or --help");
            $this->stdout("Language:" . ' ' . $language);
            $this->stdout("Run controller:" . ' ' . $controller);
            $this->stdout("Run action:" . ' ' . $action);
        }
    }

    /**
     * Base help message for options
     * @return void
     */
    private function showHelp(): void
    {
        $this->stdout("--help         : Show this help message");
        $this->stdout("--silent       : Hide program greeting");
        $this->stdout("--l            : Set language, e.g. \"ru\"");
        $this->stdout("--ownparam     : Set one or more own parameters");
        $this->stdout("Example1: php ./cli.php mygreatclicontroller:mygreatcliaction");
        $this->stdout("Example2: php ./cli.php reports --silent");
        $this->stdout("Example3: php ./cli.php reports:pdf --silent");
        $this->stdout("Example3: php ./cli.php reports:sendxls --silent --email1:admin@gmail.com --email2:director@gmail.com");
    }

}
