<?php

namespace Oooiik\Test20240706\Console;

use Oooiik\Test20240706\Console\Commands\CommadInterface;

class Karnel
{
    protected $path = null;

    public function __construct()
    {
        $this->setPath(__DIR__ . "/Commands");
    }

    public function call($command)
    {
        /** @var CommadInterface $class */
        foreach ($this->collect() as $class) {
            if (in_array(class_basename($class), ["BaseCommand"])) continue;
            $c = new $class();
            if (method_exists($c, "getSignature") && $c->getSignature() == $command) {
                $c->handle();
                return;
            }
        }
        echo "Command not found!\n";
    }

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function collect()
    {
        return $this->getClassNamesFromDirectory($this->path);
    }

    protected function getPhpFiles($dir)
    {
        $phpFiles = [];
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() == 'php') {
                $phpFiles[] = $file->getRealPath();
            }
        }
        return $phpFiles;
    }

    protected function getClassNamesFromDirectory($dir)
    {
        $phpFiles = $this->getPhpFiles($dir);
        $allClasses = get_declared_classes();
        $newClasses = [];

        foreach ($phpFiles as $file) {
            include_once $file;
            $currentClasses = get_declared_classes();
            $diff = array_diff($currentClasses, $allClasses);
            $newClasses = array_merge($newClasses, $diff);
            $allClasses = $currentClasses;
        }

        return $newClasses;
    }
}