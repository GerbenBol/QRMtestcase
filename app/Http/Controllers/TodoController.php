<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TodoController extends Controller {
    public function index() {
        return Inertia::render('Todo', [
            'items' => Todo::orderBy('order')->get()
        ]);
    }

    public function create(Request $request) {
        self::validate($request);

        Todo::create([
            'text' => $request->text,
            'order' => Todo::max('order') + 1
        ]);
        return back();
    }

    public function update(Request $request, Todo $item) {
        self::validate($request);
        $item->update(['text' => $request->text]);
        return back();
    }

    public function delete(Todo $item) {
        $item->delete();
        return back();
    }

    public function reorder(Request $request) {
        foreach ($request->input('items', []) as $order => $item) {
            Todo::find($item['id'])->update(['order' => $order + 1]);
        }
        return back();
    }

    private static function validate(Request $request) {
        $request->validate(['text' => 'required|string']);
    }
}
