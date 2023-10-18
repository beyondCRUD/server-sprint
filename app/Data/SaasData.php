<?php

namespace App\Data;

use Spatie\LaravelData\Data;

final class SaasData extends Data
{
    public function __construct(
        public string $client,
        public string $client_name,
        public string $fqdn,
        public string $name,
        public string $email,
        public string $password,
        public ?string $uuid,
    ) {
    }

    public static function mainTenant(?string $fqdn = null): self
    {
        $co = fake()->company();
        $fqdn = $fqdn ?? 'wbsten.test';

        return new self(
            client: $co,
            client_name: $co,
            fqdn: $fqdn,
            name: 'admin',
            email: 'admin@'.$fqdn,
            password: 'secret',
            uuid: 'main'
        );
    }

}
