<?php

namespace Oooiik\Test20240706\Model;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    protected $table = "managers";

    protected $fillable = [
        "id",
        "fio",
        "role",
        "efficiency",
        "attached_clients_count",
    ];
}