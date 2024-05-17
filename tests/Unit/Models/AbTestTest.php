<?php

namespace Tests\Unit\Models;

use App\Models\AbTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbTestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider notRunnableStatusProvider
     */
    public function test_getIsRunnableAttribute_StatusIsNotReadyToRun_ReturnsFalse(string $status): void
    {
        $abTest = AbTest::factory()->create(['status' => $status]);

        $this->assertFalse($abTest->isRunnable);
    }

    public function test_getIsRunnableAttribute_DoesNotHaveEnoughVariants_ReturnsFalse(): void
    {
        $abTest = AbTest::factory()->create(['status' => AbTest::STATUS_READY_TO_RUN]);
        $abTest->variants()->create(['name' => 'a', 'targeting_ratio' => 1]);

        $this->assertFalse($abTest->isRunnable);
    }

    public function test_getIsRunnableAttribute_TestIsRunnable_ReturnsTrue(): void
    {
        $abTest = AbTest::factory()->create(['status' => AbTest::STATUS_READY_TO_RUN]);
        $abTest->variants()->create(['name' => 'a', 'targeting_ratio' => 1]);
        $abTest->variants()->create(['name' => 'b', 'targeting_ratio' => 2]);

        $this->assertTrue($abTest->isRunnable);
    }

    public static function notRunnableStatusProvider(): array
    {
        return [
            [AbTest::STATUS_RUNNING],
            [AbTest::STATUS_STOP],
        ];
    }
}
