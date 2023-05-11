<?php

declare(strict_types=1);

namespace Core\Console;

use Exception;

class CliOptions
{

    private string $paramStart = '--';
    private array $allParams = [];
    private array $options = [];

    public function __construct()
    {

        $this->allParams = $this->processParams();

        $this->options = $this->processOptions($this->allParams);
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getOption(string $name, mixed $default): mixed
    {
        if (array_key_exists($name, $this->options)) {
            return $this->options[$name];
        }
        return $default;
    }

    /**
     * If option with name exists
     * @param string $name Option name
     * @return bool
     */
    public function optionExists(string $name): bool
    {
        if (array_key_exists($name, $this->options)) {
            return true;
        }
        return false;
    }

    private function processParams(): array
    {
        global $argv;
        $params = array_slice($argv, 1);
        return $params;
    }

    public function getByNubmer(int $number, mixed $default = null): mixed
    {
        if (isset($this->allParams[$number])) {
            return $this->allParams[$number];
        }
        return $default;
    }

    public function getByNumberNotOption(int $number, mixed $default = null): mixed
    {
        $potentional = $this->getByNubmer($number);
        if (is_string($potentional)) {
            if (!str_starts_with($potentional, $this->paramStart)) {
                return $potentional;
            }
        }
        return $default;
    }

    public function getAllParams(): array
    {
        return $this->allParams;
    }

    private function processOptions(array $params): array
    {
        $result = [];
        foreach ($params as $param) {
            $current = $this->processOption($param);
            if ($current) {
                if (array_key_exists($current['key'], $result)) {
                    throw new Exception("Duplicate param:" . $current['key'], E_USER_ERROR);
                } else {
                    $result[$current['key']] = $current['value'];
                }
            }
        }
        return $result;
    }

    private function processOption(string $option): array|null
    {
        $result = [
            'key' => '',
            'value' => ''
        ];

        if (str_starts_with($option, $this->paramStart)) {

            $option = substr($option, strlen($this->paramStart));

            $current = explode(':', $option, 2);
            if (isset($current[0])) {
                $result['key'] = $current[0];
            }
            if (isset($current[1])) {
                if ($current[1] === 'true') {
                    $result['value'] = true;
                } else if ($current[1] === 'false') {
                    $result['value'] = false;
                } else if ($current[1] === 'null') {
                    $result['value'] = null;
                } else {
                    $result['value'] = $current[1];
                }
            }
        } else {
            return null;
        }
        return $result;
    }

}
