<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteRequest;
use App\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function update($id, NoteRequest $request)
    {
        $note = Note::findOrFail($id);

        $note->checked = $request->checked;
        $note->save();

        return response()->json([
            'updated' => true
        ]);
    }

    public function destroy($id)
    {
        $note = Note::findOrFail($id);
        $note->delete();

        return response()->json([
            'deleted' => true
        ]);
    }
}
