<?php

namespace App\Http\Requests;

use App\Note;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class NoteRequest
 *
 * @property bool checked
 *
 * @package App\Http\Requests
 */
class NoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $note = Note::findOrFail($this->route()->parameter('note'));

        return $note->panel->user_id === \Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
