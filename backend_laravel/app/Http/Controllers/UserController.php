<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    public function index()
    {

    }

    public function store(Request $request)
    {

    }

    public function show(string $id)
    {
        //
    }


    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        return response()->json([
            'data' => $user
        ]);
    }


    public function destroy(string $id)
    {

    }
}
