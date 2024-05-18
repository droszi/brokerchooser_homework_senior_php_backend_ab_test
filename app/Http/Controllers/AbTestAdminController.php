<?php

namespace App\Http\Controllers;

use App\Models\AbTest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AbTestAdminController extends Controller
{
    public function __invoke(): View
    {
        $abTests = AbTest::all();

        return view('abtest.admin', [
            'abTests' => $abTests,
        ]);
    }
}
