<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApiTestCase extends WebTestCase
{
    /**
     * @param mixed[] $parameters
     */
    protected function apiRequest(
        string $method,
        string $url,
        array $parameters = []
    ): KernelBrowser {
        $client = self::createClient();

        $crawler = $client->request($method, $url, $parameters);

        self::assertResponseIsSuccessful();

        return $client;
    }
}
