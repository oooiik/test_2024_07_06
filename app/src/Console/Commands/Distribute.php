<?php

namespace Oooiik\Test20240706\Console\Commands;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Oooiik\Test20240706\Model\Customer;
use Oooiik\Test20240706\Model\CustomerToManagerAssign;
use Oooiik\Test20240706\Model\Manager;

class Distribute extends BaseCommand
{
    public const MANAGER_LIMIT = 3000;

    protected $signature = "distribute";

    public function handle(): void
    {
        $this->decoupling();
        $this->coupling();
    }

    protected function decoupling()
    {
        $assign = CustomerToManagerAssign::query()
            ->whereHas('customers', function (Builder $query) {
                $query->whereBetween(
                    "first_order_date",
                    [
                        Carbon::parse("2024-06-03 00:00:00")->timestamp,
                        Carbon::now()->timestamp,
                    ]
                );
            })
            ->whereHas("managers", function (Builder $query) {
                $query->whereIn("role", ["Персональный менеджер", "Менеджер по привлечению"]);
            });

        echo "count assign: " . $assign->count() . "\n";
        $assign->delete();
    }

    public function coupling()
    {
        $managersQuery = Manager::query()
            ->where("role", "=", "Персональный менеджер")
            ->withCount("customers");

        $managersLazy = $managersQuery
            ->orderBy("efficiency", "desc")
            ->lazy(100);

        /** @var Manager $manager */
        foreach ($managersLazy as $manager) {
//            var_dump($manager);
            echo "manager customer count: " . $manager->customers_count . PHP_EOL;


            $customersQuery = Customer::query()
                ->whereBetween(
                    "first_order_date",
                    [
                        Carbon::parse("2024-06-03 00:00:00")->timestamp,
                        Carbon::now()->timestamp,
                    ]
                )->doesntHave("managers");

            $customersCount = $customersQuery->count();
            echo "customers count to the manager: " . $customersCount . PHP_EOL;
            if ($customersCount) {
                $makeAssign = $customersQuery
                    ->limit(self::MANAGER_LIMIT)
                    ->get()
                    ->map(function ($item) use ($manager) {
                        /** @var Customer $item */
                        return [
                            "customer_id" => $item->id,
                            "city_id" => $item->city_id,
                            "manager_id" => $manager->id
                        ];
                    });
//                    var_dump($makeAssign);
                echo "make assign count: " . $makeAssign->count() . PHP_EOL;
                CustomerToManagerAssign::query()->insert($makeAssign->toArray());
            } else {
                return;
            }
        }

    }
}