<?php

namespace App\Core\Log;

use DateTime;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

abstract class LoggerRoute extends AbstractLogger implements LoggerInterface
{

    protected bool $isEnable = true;

    public function __construct(array $attributes = array())
    {
        foreach ($attributes as $attribute => $value)
        {
            if (property_exists($this, $attribute))
            {
                $this->{$attribute} = $value;
            }
        }
    }

    /**
     * Date format
     */
    protected string $dateFormat = 'Y-m-d H:i';

    /**
     * Current date
     * @return string
     */
    protected function getDate() : string
    {
        return (new DateTime())->format($this->dateFormat);
    }

    /**
     * Преобразование $context в строку
     *
     * @param array $context
     * @return string
     */
    protected function toString(array $context = []) : string
    {
        return !empty($context) ? json_encode($context) : '';
    }

    /**
     * If route enabled
     * @return bool
     */
    public function isEnable() : bool {
        return $this->isEnable;
    }

}
