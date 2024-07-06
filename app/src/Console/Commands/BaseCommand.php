<?php

namespace Oooiik\Test20240706\Console\Commands;

abstract class BaseCommand implements CommadInterface
{
    protected $signature = "";

    public function getSignature(): string
    {
        return $this->signature;
    }

}