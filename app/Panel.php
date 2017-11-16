<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Panel
 *
 * @property int                                                       $id
 * @property int                                                       $user_id
 * @property string                                                    $title
 * @property \Carbon\Carbon|null                                       $created_at
 * @property \Carbon\Carbon|null                                       $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Note[] $notes
 * @property-read \App\User                                            $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Panel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Panel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Panel whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Panel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Panel whereUserId($value)
 * @mixin \Eloquent
 */
class Panel extends Model
{
    protected $fillable = [
        'title',
        'checked'
    ];

    /**
     * Связь с юзером  "Один ко многим" - реверс
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Свзять с заметками "Один ко многим"
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Обновляет свой статус в зависимости от статуса заметок
     */
    public function reCheck()
    {
        $checked = !$this->notes()->get()->filter(function ($val) {
            return !$val->checked;
        })->count();

        $this->update(['checked' => $checked]);
    }

    /**
     * Форма для создания списка
     *
     * @return array
     */
    public static function form()
    {
        return [
            'title' => '',
            'notes' => [
                Note::form()
            ]
        ];
    }
}
