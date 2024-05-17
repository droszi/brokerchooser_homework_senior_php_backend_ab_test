<?php

namespace App\Services;

use App\Exceptions\AbTestNotFound;
use App\Exceptions\AbTestNotRunnable;
use App\Exceptions\AbTestNotRunning;
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

    public function startAbTestById(int $id): void
    {
        $abTest = AbTest::find($id);

        if (!$abTest) {
            throw new AbTestNotFound();
        }

        if (!$abTest->isRunnable) {
            throw new AbTestNotRunnable();
        }

        $abTest->status = AbTest::STATUS_RUNNING;
        $abTest->save();
    }

    public function stopAbTestById(int $id): void
    {
        $abTest = AbTest::find($id);

        if (!$abTest) {
            throw new AbTestNotFound();
        }

        if (!$abTest->isRunning) {
            throw new AbTestNotRunning();
        }

        $abTest->status = AbTest::STATUS_STOP;
        $abTest->save();
    }
}
