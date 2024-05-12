<?php

namespace Tests\Unit\Services;

use App\Models\AbTest;
use App\Models\Session;
use App\Services\AbTestService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbTestServiceTest extends TestCase
{
    use RefreshDatabase;

    private AbTestService $abTestService;
    private Session $session;

    protected function setUp(): void
    {
        parent::setUp();

        $this->abTestService = new AbTestService();
        $this->session = Session::factory()->create();
    }

    public function test_assignAbTestVariantForSession_NoTestRunning_NothingAssigned(): void
    {
        $this->abTestService->assignAbTestVariantForSession($this->session);

        $this->assertAssignedVariants([], $this->session->abTestVariants);
    }

    public function test_assignAbTestVariantForSession_UnassignableTestsFound_NothingAssigned(): void
    {
        $this->createBrokenAbTests();

        $this->createAbTestWithVariants(
            'test ready to run',
            AbTest::STATUS_READY_TO_RUN,
            [
                'a' => 1,
                'b' => 2,
            ]
        );

        $this->createAbTestWithVariants(
            'test stopped',
            AbTest::STATUS_STOP,
            [
                'a' => 1,
                'b' => 2,
            ]
        );

        $this->abTestService->assignAbTestVariantForSession($this->session);

        $this->assertAssignedVariants([], $this->session->abTestVariants);
    }

    public function test_assignAbTestVariantForSession_OneTestRunning_TestAssigned(): void
    {
        $this->createAbTestWithVariants(
            'test 1',
            AbTest::STATUS_RUNNING,
            [
                'test 1 a' => 0,
                'test 1 b' => 1,
            ]
        );

        $this->abTestService->assignAbTestVariantForSession($this->session);

        $this->assertAssignedVariants(
            ['test 1 b'],
            $this->session->abTestVariants
        );
    }

    public function test_assignAbTestVariantForSession_MoreTestsRunning_RunningTestsAssigned(): void
    {
        $this->createAbTestWithVariants(
            'test 1',
            AbTest::STATUS_RUNNING,
            [
                'test 1 a' => 0,
                'test 1 b' => 1,
            ]
        );

        $this->createBrokenAbTests();

        $this->createAbTestWithVariants(
            'test 2',
            AbTest::STATUS_RUNNING,
            [
                'test 2 a' => 1,
                'test 2 b' => 0,
            ]
        );

        $this->abTestService->assignAbTestVariantForSession($this->session);

        $this->assertAssignedVariants(
            [
                'test 1 b',
                'test 2 a'
            ],
            $this->session->abTestVariants
        );
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

    private function assertAssignedVariants(
        array $expectedVariantNames,
        Collection $assignedVariants
    ): void
    {
        $this->assertSameSize(
            $expectedVariantNames,
            $assignedVariants
        );

        foreach ($expectedVariantNames as $name) {
            $variant = $assignedVariants->find(['name' => $name]);
            $this->assertNotNull($variant, 'variant name not found: ' . $name);
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
