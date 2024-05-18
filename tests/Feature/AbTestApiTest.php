<?php

use App\Models\AbTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\AbTestTrait;

uses(
    AbTestTrait::class,
    RefreshDatabase::class
);

it('start returns 404 on a missing test', function () {
    $response = $this->put("/api/v1/abtest/0/start");

    $response->assertStatus(404);
});

it('start returns 409 on test not in runnable state', function () {
    $abTest = $this->createAbTestWithVariants(
        'test ready to run',
        AbTest::STATUS_STOP,
        []
    );

    $response = $this->put("/api/v1/abtest/{$abTest->id}/start");

    $response->assertStatus(409);
});

it('starts a test', function () {
    $abTest = $this->createAbTestWithVariants(
        'test ready to run',
        AbTest::STATUS_READY_TO_RUN,
        [
            'a' => 1,
            'b' => 2,
        ]
    );

    $response = $this->put("/api/v1/abtest/{$abTest->id}/start");

    $abTest->refresh();

    $response->assertStatus(204);
    $this->assertEquals(AbTest::STATUS_RUNNING, $abTest->status);
});

it('stop returns 404 on a missing test', function () {
    $response = $this->put("/api/v1/abtest/0/stop");

    $response->assertStatus(404);
});

it('stop returns 409 on test not in running state', function () {
    $abTest = $this->createAbTestWithVariants(
        'test ready to run',
        AbTest::STATUS_STOP,
        []
    );

    $response = $this->put("/api/v1/abtest/{$abTest->id}/stop");

    $response->assertStatus(409);
});

it('stops a test', function () {
    $abTest = $this->createAbTestWithVariants(
        'test ready to run',
        AbTest::STATUS_RUNNING,
        []
    );

    $response = $this->put("/api/v1/abtest/{$abTest->id}/stop");

    $abTest->refresh();

    $response->assertStatus(204);
    $this->assertEquals(AbTest::STATUS_STOP, $abTest->status);
});
