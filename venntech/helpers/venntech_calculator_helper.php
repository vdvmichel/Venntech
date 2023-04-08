<?php

class IRRCalculator
{
    const IRR_PRECISION = 11;
    const IRR_INITIAL = 0.1;
    const IRR_MAX_ITERATIONS = 20;

    /**
     * @param int[]      $cashFlow
     * @param float|null $irrInitial
     * @return float|null
     */
    public static function calculateFromCashFlow(array $cashFlow, float $irrInitial = null)
    {
        bcscale(self::IRR_PRECISION);

        $totalCashFlowItems = count($cashFlow);
        $maxIterationCount = self::IRR_MAX_ITERATIONS;
        $absoluteAccuracy = 10 ** -self::IRR_PRECISION;
        $x0 = $irrInitial ?? self::IRR_INITIAL;
        $i = 0;

        while ($i < $maxIterationCount) {
            $fValue = 0;
            $fDerivative = 0;

            for ($k = 0; $k < $totalCashFlowItems; $k++) {
                $fValue = bcadd($fValue, bcdiv($cashFlow[$k], bcpow(bcadd(1.0, $x0), $k)));
                $fDerivative = bcadd($fDerivative, bcdiv(bcmul(-$k, $cashFlow[$k]), bcpow(bcadd(1.0, $x0), bcadd($k, 1, 0))));
            }

            $x1 = bcsub($x0, bcdiv($fValue, $fDerivative));

            if (abs($x1 - $x0) <= $absoluteAccuracy) {
                return $x1;
            }

            $x0 = $x1;
            $i++;
        }

        return null;
    }
}

?>