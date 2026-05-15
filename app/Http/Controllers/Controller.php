<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    protected function resolvePerPageLimit(Request $request, int $default = 10): int
    {
        $limit = (int) $request->input('limit', $default);

        return in_array($limit, [10, 25, 50, 100], true) ? $limit : $default;
    }
}
