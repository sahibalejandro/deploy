<?php

namespace App\Http\Controllers;

use App\Database;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreDatabase;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DatabasesController extends Controller
{
    public function index()
    {
        return auth()->user()->databases;
    }

    public function store(StoreDatabase $request)
    {
        $process = new Process([
            base_path('scripts/create-database'),
            $request->name,
            $request->user,
            $request->password,
        ]);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $e) {
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

