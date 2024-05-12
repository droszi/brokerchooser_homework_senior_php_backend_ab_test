<?php

namespace Tests\Helpers;

use App\Models\AbTest;
use Illuminate\Database\Eloquent\Collection;

trait AbTestTrait
{

    private function assertAssignedVariants(
        array $expectedVariantNames,
        Collection $assignedVariants
    ): void
    {
        $this->assertSameSize(
            $expectedVariantNames,
            $assignedVariants,
            'assigned test count does not match expected'
        );

        foreach ($expectedVariantNames as $name) {
            $variant = $assignedVariants->find(['name' => $name]);
            $this->assertNotNull($variant, 'variant name not found: ' . $name);
        }
    }

    private function createAbTestWithVariants(
        string $testName,
        string $status,
        array $variants
    ): void
    {
        $abTest = AbTest::factory()->create([
            'name' => $testName,
            'status' => $status,
        ]);

        foreach ($variants as $variantName => $targetingRatio) {
            $abTest->variants()->create([
                'name' => $variantName,
                'targeting_ratio' => $targetingRatio,
            ]);
        }
    }

    private function createBrokenAbTests(): void
    {
        $this->createAbTestWithVariants(
            'test has no variants',
            AbTest::STATUS_RUNNING,
            []
        );

        $this->createAbTestWithVariants(
            'test has variants with zero probabilities',
            AbTest::STATUS_RUNNING,
            [
                'a' => 0,
                'b' => 0,
            ]
        );
    }
}
