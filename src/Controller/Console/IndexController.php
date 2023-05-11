<?php

namespace Core\Controller\Console;

use Core\Interfaces\Config;

class IndexController extends BaseController
{

    public function indexAction(Config $config): void
    {
        $this->stdout('Please choose controller, action or parametres to run');
        $this->stdout('Example: ./cli mycontroller:myaction --option1:777');
        $this->stdout('Run with --help key for get information about options');
        $this->stdout('');
        $this->stdout('Available controllers:');

        $ds = DIRECTORY_SEPARATOR;
        $commandDirSystem = $config->string('loc.core') . $ds . 'Controller' . $ds . 'System';
        $commandDirUser = $config->string('loc.base') . $ds . str_replace('\\', $ds, $config->string('commandNamespace') ?? '');
        foreach ($this->getCommands([$commandDirSystem, $commandDirUser]) as $item) {
            $this->stdout(' ./cli ' . $item);
        }
    }

    private function getCommands(array $commandDir = []): array
    {
        $result = [];
        foreach ($commandDir as $currentCommandDir) {
            $controllers = glob($currentCommandDir . '/*Controller.php');
            $search = ['Controller', '.php'];
            foreach ($controllers as $controller) {
                $commandName = lcfirst(str_replace($search, '', basename($controller)));
                $result[] = $commandName;
            }
        }
        return $result;
    }

}
