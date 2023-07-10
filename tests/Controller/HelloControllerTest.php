<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HelloControllerTest extends WebTestCase
{
    public static function provideValidRoutes(): array
    {
        return [
            'default without name' => ['/hello', 200],
            'name "Adrien"' => ['/hello/Adrien', 200],
            'name "Jean-Pierre"' => ['/hello/Jean-Pierre', 200],
        ];
    }

    /**
     * @group smoke-test
     *
     * @dataProvider provideValidRoutes
     */
    public function testRoute(string $uri, int $expectedStatusCode): void
    {
        $client = static::createClient();
        $client->request('GET', $uri);

        $this->assertResponseStatusCodeSame($expectedStatusCode);
    }
}
