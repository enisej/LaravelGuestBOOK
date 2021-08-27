<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Message extends Model
{
public function getCreatedAtAttribute($date)
{
return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format("H:i:s / d.m.Y");
}
}