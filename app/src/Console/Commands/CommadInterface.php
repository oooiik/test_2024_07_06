<?php

namespace Oooiik\Test20240706\Console\Commands;

interface CommadInterface
{
    public function getSignature(): string;

    public function handle(): void;
}