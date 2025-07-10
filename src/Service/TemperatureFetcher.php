<?php

namespace App\Service;

class TemperatureFetcher
{
    private String $url = "https://svr140.supla.org/direct/114/DbDsmcN3tNxUPvsT/read?format=json";

    public function fetch(): ?float
    {
        $json = file_get_contents($this->url);

        if ($json === false) {
            return null;
        }
        $data = json_decode($json, true);

        if (!$data['connected'] || !isset($data['temperature'])) {
            return null;
        }

        return (float) $data['temperature'];
    }
}
