<?php

namespace App\Http\Controllers;

use App\Database;
use Illuminate\Http\Request;

class DatabasesController extends Controller
{
    public function index()
    {
        return auth()->user()->databases;
    }

    public function store(Request $request)
    {
        $database = new Database($request->only('name', 'user'));
        auth()->user()->databases()->save($database);
        return response()->json($database, 201);
    }
}
