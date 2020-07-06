<?php declare(strict_types=1);
/**
 * @package php-playground
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace RabbitMq\SixthTutorial;

/**
 * Class FibonacciService
 */
class FibonacciService
{
    /**
     * @param int $n
     *
     * @return int
     */
    public function calculate(int $n): int
    {
        if ($n == 0) {
            return 0;
        }
        if ($n == 1) {
            return 1;
        }

        return $this->calculate($n - 1) + $this->calculate($n - 2);
    }
}
