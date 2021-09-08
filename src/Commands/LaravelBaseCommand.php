<?php

namespace Rawilk\LaravelBase\Commands;

use Illuminate\Console\Command;

class LaravelBaseCommand extends Command
{
    public $signature = 'laravel-base';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
