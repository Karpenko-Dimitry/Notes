<?php

namespace App\Console\Commands;

use App\Models\File;
use Illuminate\Console\Command;

class RemoveUnusedFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:remove-unused';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove unused files';

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
     * @return int
     */
    public function handle()
    {
        foreach (File::all() as $file) {
            $file->deleteUnused();
        }

        return 0;
    }
}
