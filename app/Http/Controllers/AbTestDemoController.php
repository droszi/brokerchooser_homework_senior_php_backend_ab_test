<?php

namespace App\Http\Controllers;

use App;
use App\Models\Session;
use Illuminate\View\View;

class AbTestDemoController extends Controller
{
    public function __invoke(): View
    {
        $session = App::make(Session::class);

        return view('abtest.demo', [
            'session' => $session,
            'testVariants' => $session->abTestVariants,
        ]);
    }
}
