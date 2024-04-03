<?php

namespace Modules\Otp\Console;

use App\Models\User;
use Illuminate\Console\Command;
use Modules\Auth\Enums\AuthEnum;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ClearUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'auth:clear-unverified-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        User::where(AuthEnum::VERIFIED_AT, '<', now()->subWeek())->delete();
    }
}
