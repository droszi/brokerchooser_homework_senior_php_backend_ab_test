<?php

namespace Database\Factories;

use App\Models\AbTest;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbTestFactory extends Factory
{
    protected $model = AbTest::class;

    public function definition(): array
    {
        return [
            'name' => 'test name',
            'status' => AbTest::STATUS_READY_TO_RUN,
        ];
    }
}
