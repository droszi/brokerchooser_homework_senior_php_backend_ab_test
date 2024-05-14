<?php

namespace Database\Seeders;

use App\Models\AbTest;
use Illuminate\Database\Seeder;
use Tests\Helpers\AbTestTrait;

class DatabaseSeeder extends Seeder
{
    use AbTestTrait;

    public function run(): void
    {
        $this->createAbTestWithVariants(
            'test ready to run',
            AbTest::STATUS_READY_TO_RUN,
            [
                'a' => 1,
                'b' => 2,
            ]

        );
        $this->createAbTestWithVariants(
            'test 1',
            AbTest::STATUS_RUNNING,
            [
                'test 1 a' => 1,
                'test 1 b' => 2,
            ]
        );

        $this->createAbTestWithVariants(
            'test 2',
            AbTest::STATUS_RUNNING,
            [
                'test 2 a' => 2,
                'test 2 b' => 1,
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
    }
}
