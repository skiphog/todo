<?php

namespace App\Http\Controllers;

use App\Note;
use App\Panel;
use Illuminate\Http\Request;
use App\Http\Requests\DashRequest;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Вернуть все списки
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $panels = $request->user()
            ->panels()
            ->with('notes')
            ->orderBy('created_at', 'desc')
            ->paginate(5);


        /** @var Collection $panel */
        $panel = $request->user()->panels()->get();

        $count = $panel->count();
        $complete = $panel->where('checked', true)->count();


        $date = $panel->pluck('created_at');

        return response()->json([
            'panel' => $panels,
            'info'  => [
                'count'      => $count,
                'complete'   => $complete,
                'incomplete' => $count - $complete,
                'date_end'   => $date->reverse()->first()->format('d-m-Y H:s'),
                'date_start' => $date->first()->format('d-m-Y H:s'),
            ]
        ]);
    }

    /**
     * Форма для создания списка
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return response()->json([
            'form' => Panel::form()
        ]);
    }

    /**
     * Форма для редактирования списка
     *
     * @param         $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Обновить список + заметки
     *
     * @param             $id
     * @param DashRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

        tap($panel, function ($panel) use ($request) {
            $panel->title = $request->title;
        })->save();


        Note::whereNotIn('id', $notesUpdate)
            ->where('panel_id', $panel->id)
            ->delete();

        if (\count($notes)) {
            $panel->notes()->saveMany($notes);
        }

        $panel->reCheck();

        return response()->json([
            'saved'   => true,
            'id'      => $panel->id,
            'message' => 'Обновлено'
        ]);
    }

    /**
     * Добавить спискок + заметки
     *
     * @param DashRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DashRequest $request)
    {
        $notes = [];

        foreach ($request->notes as $note) {
            $notes[] = new Note($note);
        }

        $panel = new Panel($request->only('title'));

        $request->user()->panels()->save($panel);

        $panel->notes()->saveMany($notes);

        return response()->json([
            'saved'   => true,
            'id'      => $panel->id,
            'message' => 'Список создан'
        ]);
    }

    /**
     * Удалить список
     *
     * @param         $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id, Request $request)
    {
        $panel = $request->user()->panels()
            ->findOrFail($id);

        tap($panel, function ($panel) {
            Note::where('panel_id', $panel->id)->delete();
        })->delete();

        return response()->json([
            'deleted' => true
        ]);
    }
}
