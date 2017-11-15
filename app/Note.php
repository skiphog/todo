<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Note
 *
 * @property int    $id
 * @property int    $panel_id
 * @property int    $checked
 * @property string $content
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereChecked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note wherePanelId($value)
 * @mixin \Eloquent
 */
class Note extends Model
{
    protected $fillable = [
        'content',
        'checked'
    ];

    public $timestamps = false;

    public static function form()
    {
        return [
            'content' => ''
        ];
    }

    public function panel()
    {
        return $this->belongsTo(Panel::class);
    }
}
