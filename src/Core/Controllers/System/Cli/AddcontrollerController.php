<?php

namespace le7\Core\Controllers\System\Cli;

use le7\Core\Controllers\Main\Cli;

class AddcontrollerController extends Cli {

    public function indexAction() {
        $this->stdout('usage: addcontroller:web --p1 <MyControllerName> --p2 <php|smarty>' . "\r\n");
        $this->stdout('usage: addcontroller:api --p1 <MyControllerName> --p2 <apiVersionNumber>' . "\r\n");
        $this->stdout('usage: addcontroller:cli --p1 <MyControllerName>');
    }

    public function webAction($params) {

        $controllerName = $this->getControllerName($params->p1);

        $allowEngines = array(
            'php', 'smarty'
        );

        if (empty($params->p2)) {
            $viewEngine = 'smarty';
        } else {
            $viewEngine = $params->p2;
            if (!in_array($viewEngine, $allowEngines)) {
                trigger_error(_('Allowed engines:') . '--p2 <php>,<smarty>', E_USER_ERROR);
                exit;
            }
        }

        $data = $this->getTemplateData('ControllerWeb');

        $search = array('{controllerName}', '{viewEngine}');
        $replace = array($controllerName, ucfirst($viewEngine));

        $dataFinal = str_replace($search, $replace, $data);
        $this->stdout($dataFinal);
        $this->putController($controllerName, $this->topologyFs->getControllerWebDir(), $dataFinal);
    }

    public function apiAction($params) {

        $controllerName = $this->getControllerName($params->p1);

        if (empty($params->p2)) {
            $apiVersion = 'v1';
        } else {
            $apiVersion = 'v' . $params->p2;
        }

        $apiControllerFolder = $this->topologyFs->getControllerApiDir() . '/' . $apiVersion;
        if (!file_exists($apiControllerFolder)) {
            trigger_error(_('Folder not exists') . $apiControllerFolder, E_USER_ERROR);
            exit;
        }

        $data = $this->getTemplateData('ControllerApi');

        $search = array('{controllerName}', '{apiVersion}');
        $replace = array($controllerName, $apiVersion);

        $dataFinal = str_replace($search, $replace, $data);
        $this->stdout($dataFinal);
        $this->putController($controllerName, $apiControllerFolder, $dataFinal);
    }

    public function cliAction($params) {

        $controllerName = $this->getControllerName($params->p1);

        $data = $this->getTemplateData('ControllerCli');

        $search = array('{controllerName}');
        $replace = array($controllerName);

        $dataFinal = str_replace($search, $replace, $data);
        $this->stdout($dataFinal);
        $this->putController($controllerName, $this->topologyFs->getControllerCliDir(), $dataFinal);
    }

    private function getTemplateData(string $controllerType): string {
        $codeTemplatesDir = $this->topologyFs->getCodeTemplatesDir();
        $file = $codeTemplatesDir . DIRECTORY_SEPARATOR . $controllerType . '.txt';
        $data = file_get_contents($file);
        return $data;
    }

    private function putController(string $controllerName, string $dir, string $data) {
        $fileName = $dir . '/' . $controllerName . '.php';
        if (file_exists($fileName)) {
            trigger_error(_('File exists:') . $fileName, E_USER_ERROR);
            exit;
        }
        file_put_contents($fileName, $data);
    }

    private function getControllerName(string $controllerName) {
        if (empty($controllerName)) {
            trigger_error(_('Required parameter missing:') . '--p2 <controller_name>', E_USER_ERROR);
            exit;
        }
        return ucfirst($controllerName) . 'Controller';
    }

}
