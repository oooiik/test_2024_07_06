<?php

namespace Oooiik\Test20240706\Console\Commands;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\LazyCollection;
use Oooiik\Test20240706\Model\Customer;
use Oooiik\Test20240706\Model\CustomerToManagerAssign;
use Oooiik\Test20240706\Model\Manager;

class Distribute extends BaseCommand
{
    public const MANAGER_LIMIT = 3000;
    public const REPORT_ON_ATTRACTION_PATH = "Отчет по Менеджерам по привлечению.csv";
    public const REPORT_ON_PERSONAL_PATH = "Отчет по Персональным менеджерам.csv";

    protected $signature = "distribute";

    protected $reportAtt;
    protected $reportPer;

    protected $clientsGotTheMost = 0;
    protected $clientsGotTheMostManagerName = "";

    public function handle(): void
    {
        $this->reportAtt = fopen("storage/" . self::REPORT_ON_ATTRACTION_PATH, 'w');
        fputcsv($this->reportAtt, ["ФИО менеджера", "Кол-во клиентов до распределения", "Кол-во клиентов, которых довел до 1-го заказа", "Кол-во клиентов после распределения"]);

        $this->reportPer = fopen("storage/" . self::REPORT_ON_PERSONAL_PATH, 'w');
        fputcsv($this->reportPer, ["ФИО менеджера", "кол-во клиентов до распределения", "кол-во клиентов после распределения", "прирост Клиентами в штуках"]);

        $customersCount = Customer::query()
            ->where("first_order_date", ">", "0")
            ->whereHas("managers", function (Builder $query) {
                $query->where("role", "Менеджер по привлечению");
            })
            ->whereBetween(
                "first_order_date",
                [
                    Carbon::parse("2024-06-03 00:00:00")->timestamp,
                    Carbon::now()->timestamp,
                ]
            )
            ->count();

        echo "Клиентов было доведено до 1-го заказа всеми «Менеджерами по привлечению» : " . $customersCount . PHP_EOL;

        $customersLazy = $this->customersForTheManagerLazy();
        $managersLazy = $this->getManagersLazy();

        /** @var Customer $customer */
        foreach ($customersLazy as $customer) {
            if ($managersLazy->current()->attached_clients_count = 3000) {
                $this->updateManager($managersLazy->current());
                $managersLazy->next();
                if (is_null($managersLazy->current())) {
                    break;
                }
            }
            $managersLazy->current()->attached_clients_count = $manager->attached_clients_count ?? 0 + 1;

            $customer->customerToManagerAssign()->delete();
            $customer->customerToManagerAssign()->create([
                "city_id" => $customer->city_id,
                "manager_id" => $managersLazy->current()->id
            ]);
        }

        if (!is_null($managersLazy->current())) {
            $this->updateManager($managersLazy->current());
        }

        echo "«Персональному менеджеру» $this->clientsGotTheMostManagerName больше всего досталось $this->clientsGotTheMost Клиентов\r\n";

        fclose($this->reportAtt);
        fclose($this->reportPer);
    }

    protected function customersForTheManagerLazy(): LazyCollection
    {
        return Customer::query()
            ->whereBetween(
                "first_order_date",
                [
                    Carbon::parse("2024-06-03 00:00:00")->timestamp,
                    Carbon::now()->timestamp,
                ]
            )
            ->whereDoesntHave("managers", function (Builder $query) {
                $query->where("role", "Персональный менеджер");
            })
            ->with("customerToManagerAssign")
            ->get()
            ->lazy();
    }

    protected function getManagersLazy()
    {
        $managers = Manager::query()
            ->where("role", "Персональный менеджер")
            ->withCount(["customers as customers_count_with_first_order" => function (Builder $query) {
                $query->where("first_order_date", ">", "0");
            }])
            ->orderBy("efficiency", "desc")
            ->get();

        foreach ($managers as $manager) {
            yield $manager;
        }
    }

    protected function updateManager($manager)
    {
        $countGot = $manager->attached_clients_count - $manager->getOriginal("attached_clients_count");

        if ($countGot > $this->clientsGotTheMost) {
            $this->clientsGotTheMost = $countGot;
            $this->clientsGotTheMostManagerName = $manager->fio;
        }

        fputcsv($this->reportAtt, [
            $manager->fio,
            $manager->getOriginal("attached_clients_count"),
            $manager->customers_count_with_first_order,
            $manager->attached_clients_count,
        ]);

        fputcsv($this->reportPer, [
            $manager->fio,
            $manager->getOriginal("attached_clients_count"),
            $manager->attached_clients_count,
            $countGot,
        ]);

        $manager->save();
    }
}