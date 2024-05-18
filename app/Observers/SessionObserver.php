<?php

namespace App\Observers;

use App\Models\Session;
use App\Services\AbTestService;

class SessionObserver
{
    public function __construct(
        private readonly AbTestService $abTestService
    ) {}

    public function created(Session $session): void
    {
        $this->abTestService->assignAbTestVariantForSession($session);
    }
}
