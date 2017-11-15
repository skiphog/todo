<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Panel
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Note[] $notes
 * @property-read \App\User $user
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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

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
