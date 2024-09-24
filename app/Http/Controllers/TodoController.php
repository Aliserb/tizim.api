<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Auth::user()->todos;
        return response()->json($todos);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'boolean',
        ]);

        $todo = Auth::user()->todos()->create($validatedData);

        return response()->json($todo, 201);
    }

    public function show(Todo $todo)
    {
        if (Auth::id() !== $todo->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($todo);
    }

    public function update(Request $request, Todo $todo)
    {
        if (Auth::id() !== $todo->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'completed' => 'boolean',
        ]);

        $todo->update($validatedData);

        return response()->json($todo);
    }

    public function destroy(Todo $todo)
    {
        if (Auth::id() !== $todo->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $todo->delete();

        return response()->json(['message' => 'Todo deleted']);
    }
}