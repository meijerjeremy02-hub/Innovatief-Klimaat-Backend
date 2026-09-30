<?php

namespace App\Tests\Functional;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CrudApiTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->client->disableReboot();

        $entityManager = static::getContainer()->get('doctrine')->getManager();
        $schemaTool = new SchemaTool($entityManager);
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    public function testCrudEndpointsRequireAuthenticationAndAllowAdminToManageResources(): void
    {
        $this->client->request(
            'POST',
            '/api/categories',
            [],
            [],
            ['CONTENT_TYPE' => 'application/ld+json'],
            json_encode(['name' => 'Unauthorized category'], JSON_THROW_ON_ERROR),
        );
        self::assertResponseStatusCodeSame(401);

        $category = $this->createResource('/api/categories', ['name' => 'Functional test category']);
        $categoryIri = $category['@id'];

        $this->client->request('DELETE', $categoryIri);
        self::assertResponseStatusCodeSame(401);

        $team = $this->createResource('/api/teams', [
            'name' => 'Functional test team',
            'code' => 'PHPUNIT01',
            'category' => $categoryIri,
        ]);
        $dimension = $this->createResource('/api/dimensions', ['name' => 'Functional test dimension']);
        $question = $this->createResource('/api/questions', [
            'title' => 'Functional test question',
            'dimension' => $dimension['@id'],
        ]);

        $this->client->request(
            'GET',
            '/api/teams',
            [],
            [],
            ['HTTP_ACCEPT' => 'application/ld+json'],
        );
        self::assertResponseIsSuccessful();

        $this->deleteResource($question['@id']);
        $this->deleteResource($dimension['@id']);
        $this->deleteResource($team['@id']);
        $this->deleteResource($categoryIri);
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<string, mixed>
     */
    private function createResource(string $uri, array $payload): array
    {
        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/ld+json',
                'HTTP_ACCEPT' => 'application/ld+json',
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW' => 'test-admin-password',
            ],
            json_encode($payload, JSON_THROW_ON_ERROR),
        );

        self::assertResponseStatusCodeSame(201);

        return json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }

    private function deleteResource(string $iri): void
    {
        $this->client->request(
            'DELETE',
            $iri,
            [],
            [],
            [
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW' => 'test-admin-password',
            ],
        );

        self::assertResponseStatusCodeSame(204);
    }
}
