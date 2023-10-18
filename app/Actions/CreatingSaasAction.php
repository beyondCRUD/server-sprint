<?php

namespace App\Actions;

use App\Data\SaasData;
use App\Models\Saas;
use App\Models\Tenancy\Hostname;
use App\Models\Tenancy\Website;
use Hyn\Tenancy\Repositories\HostnameRepository;
use Hyn\Tenancy\Repositories\WebsiteRepository;
use Hyn\Tenancy\Website\Directory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreatingSaasAction
{
    public function __construct(
        public HostnameRepository $hostRepo,
        public WebsiteRepository $webRepo,
    ) {
    }

    /**
     * @return array<string, bool|string>
     */
    public function execute(SaasData $data): array
    {
        $data->password = bcrypt($data->password);
        $data->uuid = $data->uuid ?? Str::uuid()->toString();

        DB::connection('system')->statement('DROP SCHEMA IF EXISTS "' . $data->uuid . '" CASCADE');
        DB::connection('system')->statement('DROP USER IF EXISTS "' . $data->uuid . '"');

        DB::beginTransaction();

        try {
            $web = new Website(['uuid' => $data->uuid]);
            $web = $this->webRepo->create($web);
            $host = new Hostname(['fqdn' => $data->fqdn]);
            $host = $this->hostRepo->create($host);
            $this->hostRepo->attach($host, $web);

            /** @var Directory $dir */
            $dir = app(Directory::class);
            $dir->setWebsite($web);

            Saas::query()->create([
                'client' => $data->client,
                'client_name' => $data->client_name,
                'hostname_id' => $host->id,
            ]);

            DB::commit();

            $result = ['status' => true, 'message' => 'Saas berhasil dibuat.'];
        } catch (\Exception $th) {
            DB::rollBack();
            report($th);

            $result = ['status' => false, 'message' => $th->getMessage(), 'class' => get_class($th)];
        }

        return $result;
    }
}
