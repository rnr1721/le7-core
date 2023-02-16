<?php

declare(strict_types=1);

namespace le7\Core\Instances;

use le7\Core\Config\ConfigInterface;

class InstanceCliData {

    use RouterTrait;
    
    private ConfigInterface $config;

    public string $language;
    public string $silent;
    public string $controller;
    public string $action;


    public function __construct(ConfigInterface $config) {
        $this->config = $config;
    }
    
    public function getCurrentRoute() : RouteCliInterface {
        
        $options = array(
            'help' => 'no',
            'silent' => 'no'
        );
        
        $longopts = array(
            'l:','c:','a:','p1:','p2:','p3:','p4:','p5:','p6:','p7:','help::','silent::'
        );

        $opts = getopt('h::s::',$longopts);

        if (empty($opts['l'])) {
            $language = $this->config->getDefaultLanguage();
        } else {
            $this->checkDuplicate($opts['l']);
            $language = $opts['l'];
        }
        
        if (!array_key_exists($language, $this->config->getLocales())) {
            $language = $this->config->getDefaultLanguage();
        }
        
        $controller = $this->config->getDefaultController();
        $action = $this->config->getDefaultAction();
        if (!empty($opts['c'])) {
            $this->checkDuplicate($opts['c']);
            $command = explode(':',$opts['c']);
            if (!empty($command[0])) {
                $controller = $command[0];
            }
            if (!empty($command[1])) {
                $action = $command[1];
            }
        }
        
        $pController = $this->getController($controller, 'le7\Controller\Cli','le7\Core\Controllers\System\Cli');
        
        $pAction = $this->getAction($action);
        
        if (isset($opts['h']) or isset($opts['help'])) {
            $options['help'] = 'yes';
        }
        
        if (isset($opts['s']) or isset($opts['silent'])) {
            $options['silent'] = 'yes';
        }

        if ($pController === null) {
            return new RouteCli($this->getNotFound($language, $options));
        }
        
        if (!method_exists($pController['class'], $pAction)) {
            return new RouteCli($this->getNotFound($language, $options));
        }
        
        $params = array();
        for($i=1; $i <= 7; $i++) {
            $key = 'p'.$i;
            if (isset($opts[$key])) {
                $this->checkDuplicate($opts[$key]);
                $params[$key] = $opts[$key];
            }
        }
        
        $route = array(
            'type' => 'cli',
            'case' => 'cli',
            'language' => $language,
            'controller' => $pController['controller'],
            'action' => $action,
            'controllerClass' => $pController['class'],
            'actionMethod' => $pAction,
            'params' => $params,
            'options' => $options
        );
        
        return new RouteCli($route);
        
    }
    
    private function getNotFound(string $language,array $options) : array {
        $nfController = $this->config->getNotfoundController();
        $pController = $this->getController($nfController, 'le7\Controller\Cli', 'le7\Core\Controllers\System\Cli');
        $nfAction = $this->config->getDefaultAction();
        return array(
            'type' => 'cli',
            'case' => 'cli',
            'language' => $language,
            'controller' => $pController['controller'],
            'action' => $nfAction,
            'controllerClass' => $pController['class'],
            'actionMethod' => $this->getAction($nfAction),
            'params' => array(),
            'options' => $options
        );
    }

    public function getAction(string $action) : string {
        return $action.'Action';
    }

        private function checkDuplicate(string|array $data) : void {
        if (is_array($data)) {
            trigger_error('Duplicate ' . implode(',',$data) . ' option',E_USER_ERROR);
            exit;
        }
    }
    
}
