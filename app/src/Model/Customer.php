<?php

namespace Oooiik\Test20240706\Model;

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\DB;

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

    public function customerToManagerAssign(): HasMany
    {
        return $this->hasMany(CustomerToManagerAssign::class, "customer_id", "id")
            ->whereColumn("city_id", "city_id");
    }

    public function managers(): HasManyThrough
    {
        return $this->hasManyThrough(
            Manager::class,
            CustomerToManagerAssign::class,
            "customer_id",
            "id",
            "id",
"manager_id"
        )->whereColumn("customers.city_id", "customer_to_manager_assign.city_id");
    }
}