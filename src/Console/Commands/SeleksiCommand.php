<?php

namespace Bantenprov\Seleksi\Console\Commands;

use Illuminate\Console\Command;

/**
 * The SeleksiCommand class.
 *
 * @package Bantenprov\Seleksi
 * @author  bantenprov <developer.bantenprov@gmail.com>
 */
class SeleksiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bantenprov:seleksi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Demo command for Bantenprov\Seleksi package';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Welcome to command for Bantenprov\Seleksi package');
    }
}
