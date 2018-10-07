<?php

namespace App\Http\Controllers;

use App\Database;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class DatabasesController extends Controller
{
    public function index()
    {
        return auth()->user()->databases;
    }

    public function store(Request $request)
    {
        $process = new Process([
            './scripts/create-database',
            $request->name,
            $request->user,
            $request->password,
        ]);

        try {
            $process->mustRun();
        } catch (\Exception $e) {
            return response()->json(
                ['error' => "Unable to create database. ({$e->getMessage()})"],
                500
            );
        }

        $database = new Database($request->only('name', 'user'));
        auth()->user()->databases()->save($database);
        return response()->json($database, 201);
    }
}
