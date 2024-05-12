<?php

namespace App\Services;

use App\Models\AbTest;
use App\Models\Session;

class AbTestService
{
    public function assignAbTestVariantForSession(Session $session): void
    {
        $abTests = AbTest::running()->get();

        /** @var AbTest $abTest */
        foreach ($abTests as $abTest) {
            $weightedVariants = [];
            foreach ($abTest->variants as $variant) {
                for ($i = 0; $i < $variant->targeting_ratio; $i++) {
                    $weightedVariants[] = $variant;
                }
            }

            if ($weightedVariants) {
                $selectedVariant = $weightedVariants[rand(0, count($weightedVariants) - 1)];

                $session->abTestVariants()->attach($selectedVariant);
            }
        }
    }
}
