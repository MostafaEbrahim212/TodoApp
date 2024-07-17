<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            if ($request->has('search')) {
                $todos = $user->todos()->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->latest()->get();
                return response()->json($todos, 200);
            } else {
                $todos = $user->todos()->latest()->get();
                return response()->json($todos, 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function show(Todo $todo)
    {
        try {
            return response()->json($todo, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = \Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            $todo = $user->todos()->create($request->all());

            if (!$todo) {
                return response()->json(['message' => 'Error while creating todo', 'statusCode' => 400], 400);
            }

            return response()->json(['message' => 'Todo created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Todo $todo)
    {
        try {
            $user = Auth::user();

            $validator = \Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            $todo->update($request->all());

            return response()->json(['message' => 'Todo updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function destroy(Todo $todo)
    {
        try {
            $user = Auth::user();

            $todo->delete();

            return response()->json(['message' => 'Todo deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
