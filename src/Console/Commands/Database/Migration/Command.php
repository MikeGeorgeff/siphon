<?php

namespace Siphon\Console\Commands\Database\Migration;

class Command extends \Siphon\Console\Command
{
    /**
     * Get the path to the migrations directory
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        return $this->siphon->databasePath().DIRECTORY_SEPARATOR.'migrations';
    }
}