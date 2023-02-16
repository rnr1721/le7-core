<?php


namespace le7\Core\Helpers;


class ExcelHelper
{

    function createColumnsArray(int $limit=0,string $letter = 'A', string $letterLength = 'AAAAAAA') : array
    {
        $letters = array();
        if (empty($limit)) {
            while ($letter !== $letterLength) {
                $letters[] = $letter++;
            }
        } else {
            $currentCycle = 0;
            while ($letter !== $letterLength) {
                if ($currentCycle === $limit) {
                    return $letters;
                }
                $letters[] = $letter++;
                $currentCycle++;
            }
        }
        return $letters;
    }

}