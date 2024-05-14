<?php

it('returns a successful response', function () {
    $response = $this->get('/abtestdemo');

    $response->assertStatus(200);
});
