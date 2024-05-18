<?php

namespace Tests\Unit\Observers;

use App\Models\AbTest;
use App\Models\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\AbTestTrait;
use Tests\TestCase;

class SessionObserverTest extends TestCase
{
    use RefreshDatabase;
    use AbTestTrait;

    public function test_created_NewSession_TestAssigned(): void
    {
        $this->createAbTestWithVariants(
            'test 1',
            AbTest::STATUS_RUNNING,
            [
                'a' => 0,
                'b' => 1,
            ]
        );

        $session = Session::factory()->create();

        $this->assertAssignedVariants(['b'], $session->abTestVariants);
    }
}
