<?php

namespace App\Http\Controllers;

use App\Note;
use App\Http\Requests\NoteRequest;
use App\Panel;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Изменить заметке статус "отмечено"
     *
     * @param             $id
     * @param NoteRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, NoteRequest $request)
    {
        $note = tap(Note::findOrFail($id), function ($note) use ($request) {
            $note->checked = $request->checked;
        });

        $note->save();

        $panel = Panel::with('notes')
            ->where('id', $note->panel_id)
            ->firstOrFail();

        $panel->reCheck();

        return response()->json([
            'updated' => true,
        ]);
    }

    /**
     * Удалить заметку
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        tap(Note::with('panel')->where('id', $id)->first(), function ($note) {
            $note->panel->reCheck();
        })->delete();

        return response()->json([
            'deleted' => true,
        ]);
    }
}
