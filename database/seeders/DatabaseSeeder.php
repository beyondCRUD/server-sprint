<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Actions\CreatingSaasAction;
use App\Data\SaasData;
use App\Models\Saas;
use App\Models\User;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Saas::query()->truncate();
        Website::query()->truncate();
        Hostname::query()->truncate();

        /** @var CreatingSaasAction $action */
        $action = app(CreatingSaasAction::class);
        $action->execute(SaasData::mainTenant());
    }
}
