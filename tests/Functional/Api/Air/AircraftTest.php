<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Air;

use App\Tests\Functional\Api\ApiTestCase;

class AircraftTest extends ApiTestCase
{
    public function testAirportSchedule(): void
    {
        $url = '/api/aircraft/airport_schedule';
        $parameters = [
            'tail' => 'TEST-001',
            'from_date' => '2023-04-01T22:00',
            'to_date' => '2023-05-01T00:00',
            'format' => 'json',
        ];

        $client = parent::apiRequest('GET', $url, $parameters);

        $response = $client->getResponse();
        $data = $response->getContent();
        $arr = json_decode($data);

        self::assertLessThan(\count($arr), 0);

        // var_dump(\count($arr));
    }
}
