<?php

namespace App\Contracts;

/** Extension point. Implement only after the company approves materials and formulas. */
interface MaterialCalculator
{
    /** @return array<string, mixed> Material quantities only; no binding price. */
    public function estimate(array $dimensions, array $specifications): array;
}
