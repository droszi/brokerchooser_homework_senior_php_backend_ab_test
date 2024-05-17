<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\AbTestNotFound;
use App\Exceptions\AbTestNotRunning;
use App\Exceptions\AbTestNotRunnable;
use App\Http\Controllers\Controller;
use App\Services\AbTestService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AbTestController extends Controller
{
    public function __construct(private readonly AbTestService $abTestService)
    {
    }

    public function start(int $id): Response
    {
        try {
            $this->abTestService->startAbTestById($id);
        } catch (AbTestNotFound $ex) {
            return response('Test not found', 404);
        } catch (AbTestNotRunnable $ex) {
            return response('Test is not runnable', 409);
        }

        return response()->noContent();
    }

    public function stop(int $id): Response
    {
        try {
            $this->abTestService->stopAbTestById($id);
        } catch (AbTestNotFound $ex) {
            return response('Test not found', 404);
        } catch (AbTestNotRunning $ex) {
            return response('Test is not running', 409);
        }

        return response()->noContent();
    }
}
