<?php

namespace Oooiik\Test20240706\Model;

use Illuminate\Database\Eloquent\Model;

class CustomerToManagerAssign extends Model
{
    protected $table = "customer_to_manager_assign";

    protected $fillable = [
        "customer_id",
        "city_id",
        "manager_id",
        "created_at",
        "comment",
    ];
}