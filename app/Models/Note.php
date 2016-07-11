<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * @property string team_id
 * @property string channel_id
 * @property string user_id
 * @property string text
 */
class Note extends Model
{

    protected $guarded = ['n_id'];
}
