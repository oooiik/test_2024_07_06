<?php

namespace Oooiik\Test20240706\Model;

use \Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = "customers";

    protected $fillable = [
        "id",
        "city_id",
        "fio",
        "phone",
        "first_order_date",
        "last_order_date",
    ];
}