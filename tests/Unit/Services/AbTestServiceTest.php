<?php

namespace Tests\Unit\Services;

use App\Exceptions\AbTestNotFound;
use App\Exceptions\AbTestNotRunnable;
use App\Models\AbTest;
use App\Models\Session;
use App\Services\AbTestService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\AbTestTrait;
use Tests\TestCase;

class AbTestServiceTest extends TestCase
{
    use RefreshDatabase;
    use AbTestTrait;

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

    public function test_startAbTestById_TestDoesNotExists_ExceptionThrown(): void
    {
        $this->expectException(AbTestNotFound::class);
        $this->abTestService->startAbTestById(1);
    }

    public function test_startAbTestById_TestsExistsButNotRunnable_ExceptionThrown(): void
    {
        $this->expectException(AbTestNotRunnable::class);

        $test1 = $this->createAbTestWithVariants(
            'test stopped',
            AbTest::STATUS_STOP,
            [
                'a' => 1,
                'b' => 2,
            ]
        );

        $this->abTestService->startAbTestById($test1->id);

        $test1->refresh();

        $this->assertFalse($test1->isRunning);
    }

    public function test_startAbTestById_RunnableTestsExist_TestsStarted(): void
    {
        $test1 = $this->createAbTestWithVariants(
            'test ready to run',
            AbTest::STATUS_READY_TO_RUN,
            [
                'a' => 1,
                'b' => 2,
            ]
        );

        $test2 = $this->createAbTestWithVariants(
            'other test ready to run',
            AbTest::STATUS_READY_TO_RUN,
            [
                'a' => 1,
                'b' => 2,
            ]
        );

        $this->abTestService->startAbTestById($test1->id);

        $test1->refresh();
        $test2->refresh();

        $this->assertTrue($test1->isRunning);
        $this->assertFalse($test2->isRunning);
    }
}
