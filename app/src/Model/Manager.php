<?php

namespace Oooiik\Test20240706\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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

    public function customerToManagerAssign(): HasMany
    {
        return $this->hasMany(
            CustomerToManagerAssign::class,
            "manager_id",
            "id"
        );
    }

    public function customers(): HasManyThrough
    {
        return $this->hasManyThrough(
            Customer::class,
            CustomerToManagerAssign::class,
            "manager_id",
            "id",
            "id",
            "customer_id",
        )->whereColumn("customers.city_id", "customer_to_manager_assign.city_id");
    }
}