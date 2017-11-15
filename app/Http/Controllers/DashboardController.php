<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashRequest;
use App\Note;
use App\Panel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {


        $panels = Panel::with('notes')
            ->where('user_id', \Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return response()->json([
            'panel' => $panels
        ]);
    }

    public function create()
    {
        return response()->json([
            'form' => Panel::form()
        ]);
    }

    public function edit($id, Request $request)
    {
        $form = $request->user()
            ->panels()
            ->with('notes')
            ->findOrFail($id, ['id', 'title']);

        return response()->json([
            'form' => $form
        ]);
    }

    public function update($id, DashRequest $request)
    {
        $panel = $request->user()->panels()->findOrFail($id);

        $notes = [];
        $notesUpdate = [];

        foreach ($request->notes as $note) {
            if (isset($note['id'])) {
                Note::where('panel_id', $panel->id)
                    ->where('id', $note['id'])
                    ->update($note);

                $notesUpdate[] = $note['id'];
            } else {
                $notes[] = new Note($note);
            }
        }

        $panel->title = $request->title;
        $panel->save();

        Note::whereNotIn('id', $notesUpdate)
            ->where('panel_id', $panel->id)
            ->delete();

        if (count($notes)) {
            $panel->notes()->saveMany($notes);
        }

        return response()
            ->json([
                'saved'   => true,
                'id'      => $panel->id,
                'message' => 'Обновлено'
            ]);
    }

    public function store(DashRequest $request)
    {
        $notes = [];

        foreach ($request->notes as $note) {
            $notes[] = new Note($note);
        }

        $panel = new Panel($request->only('title'));

        $request->user()->panels()
            ->save($panel);

        $panel->notes()
            ->saveMany($notes);
        return response()
            ->json([
                'saved'   => true,
                'id'      => $panel->id,
                'message' => 'Список создан'
            ]);
    }
}
