<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\TodoCompletedMail;
use App\Mail\TodoNotificationMail;
use App\Mail\TodoUnshareMail;
use App\Models\ToDo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ToDoController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $query = ToDo::where(function ($q) use ($userId) {
            $q->where('user_id', $userId)
            ->orWhereHas('sharedWithUsers', function ($subQuery) use ($userId) {
                $subQuery->where('user_id', $userId);
            });
        });

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('done')) {
            $query->where('done', $request->done);
        }

        $todos = $query->paginate(10);

        return response()->json($todos);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $todo = ToDo::create([
            'name' => $request->name,
            'description' => $request->description,
            'creation_date' => now()->toDate(),
            'creation_time' => now()->toTimeString(),
            'done' => false,
            'category_id' => $request->category_id,
            'user_id'=>Auth::id(),
        ]);

        return response()->json($todo, 201);
    }

    public function update(Request $request, $id)
    {
        $todo = ToDo::find($id);

        if (!$todo) {
            return response()->json(['message' => 'ToDo not found'], 404);
        }

        $wasPreviouslyIncomplete = (bool) $todo->done === false;
        $isNowComplete = (bool) $request->done === true;

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'done' => 'boolean',
            'category_id' => 'nullable|integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $todo->update($request->all());

        if ($wasPreviouslyIncomplete && $isNowComplete) {
            $this->sendCompletedTodoEmail($todo);
            return "Mail should be sent";
        }

        return response()->json($todo);
    }
    public function destroy($id)
    {
        $todo = ToDo::find($id);

        if (!$todo) {
            return response()->json(['message' => 'ToDo not found'], 404);
        }
        if ($todo->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized to delete this ToDo item'], 403);
        }

        $todo->delete();

        return response()->json(['message' => 'ToDo deleted successfully']);
    }

    public function share(Request $request, $todoId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $todo = ToDo::findOrFail($todoId);

        if ($todo->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized to share this ToDo item'], 403);
        }

        $userIdToShare = $request->user_id;

        if ($todo->sharedWithUsers()->where('user_id', $userIdToShare)->exists()) {
            return response()->json(['message' => 'ToDo item is already shared with this user.'], 200);
        }

        $todo->sharedWithUsers()->syncWithoutDetaching($userIdToShare);

        $this->sendTodoEmail($todo);

        return response()->json(['message' => 'ToDo shared successfully']);
    }
    public function unshare(Request $request, $todoId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $todo = ToDo::findOrFail($todoId);

        if ($todo->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized to unshare this ToDo item'], 403);
        }

        $todo->sharedWithUsers()->detach($request->user_id);

        $this->sendUnshareTodoEmail($todo);

        return response()->json(['message' => 'ToDo unshared successfully']);
    }
    public function restore($id)
    {
        $todo = ToDo::onlyTrashed()->findOrFail($id);

        if ($todo->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized to restore this ToDo item'], 403);
        }

        $todo->restore();

        return response()->json(['message' => 'ToDo item restored successfully']);
    }
    public function delete($id)
    {
        $todo = ToDo::find($id);

        if (!$todo) {
            return response()->json(['message' => 'ToDo not found'], 404);
        }
        if ($todo->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized to delete this ToDo item'], 403);
        }

        $todo->forceDelete();

        return response()->json(['message' => 'ToDo deleted successfully']);
    }

    public function sendTodoEmail($todo)
    {
        $user_id = $todo->user_id;
        $user = User::find($user_id);

        Mail::to($user->email)->send(new TodoNotificationMail($todo));
    }
    public function sendUnshareTodoEmail($todo)
    {
        $user_id = $todo->user_id;
        $user = User::find($user_id);
        Mail::to($user->email)->send(new TodoUnshareMail($todo));
    }
    public function sendCompletedTodoEmail($todo)
    {
        $user_id = $todo->user_id;
        $user = User::find($user_id);
        Mail::to($user->email)->send(new TodoCompletedMail($todo));
    }
}
