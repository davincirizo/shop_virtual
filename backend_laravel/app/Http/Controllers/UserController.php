<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{

    public function index()
    {

    }

    public function store(Request $request)
    {

    }

    public function show(User $user)
    {

    }


    public function update(Request $request, User $user)
    {
        $request->file('file');
        $user->update($request->all());
        return response()->json([
            'data' => $user
        ]);
    }


    public function destroy(string $id)
    {

    }
}
