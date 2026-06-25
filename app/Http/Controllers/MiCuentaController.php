<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class MiCuentaController extends Controller
{
    public function index(): View
    {
        $user        = auth()->user();
        $enrollments = $user->enrollments()->latest()->get();

        return view('mi-cuenta', compact('user', 'enrollments'));
    }
}
