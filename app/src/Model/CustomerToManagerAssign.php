<?php

namespace Oooiik\Test20240706\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerToManagerAssign extends Model
{
    protected $table = "customer_to_manager_assign";

    public $timestamps = false;

    protected $fillable = [
        "customer_id",
        "city_id",
        "manager_id",
        "created_at",
        "comment",
    ];

    public function customers(): BelongsTo
    {
        return $this->belongsTo(Customer::class, "customer_id", "id");
    }
    public function managers(): BelongsTo
    {
        return $this->belongsTo(Manager::class, "manager_id", "id");
    }
}