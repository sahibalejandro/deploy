<?php

namespace App\Http\Controllers;

use App\Database;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreDatabase;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DatabasesController extends Controller
{
    /**
     * Returns a list of databases for the current authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return auth()->user()->databases;
    }

    /**
     * Create a new database for the current authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
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
            $message = "Unable to create database \"{$request->name}\". ({$e->getMessage()})";
            Log::error($message);
            return response()->json(['error' => $message], 500);
        }

        $database = new Database($request->only('name', 'user'));
        auth()->user()->databases()->save($database);
        return response()->json($database, 201);
    }

    /**
     * Drop a database and its related user, this only works for databases that
     * belongs to the current authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Database $database)
    {
        $process = new Process([
            base_path('scripts/delete-database'),
            $database->name,
            $database->user
        ]);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $e) {
            $message = "Unable to delete database \"{$database->name}\". ({$e->getMessage()})";
            Log::error($message);
            return response()->json(['error' => $message], 500);
        }

        $database->delete();
    }
}
