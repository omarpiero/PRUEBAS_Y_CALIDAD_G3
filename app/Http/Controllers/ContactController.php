<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre'  => ['required', 'string', 'max:120'],
            'correo'  => ['required', 'email', 'max:120'],
            'tema'    => ['required', 'string', 'max:80'],
            'curso'   => ['nullable', 'string', 'max:120'],
            'mensaje' => ['required', 'string', 'max:2000'],
        ]);

        Contact::create($data);

        return response()->json(['ok' => true]);
    }
}
