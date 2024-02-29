<?php

namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Employee;
use App\Factory\EmployeeFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class EmployeeTest extends ApiTestCase
{
    use ResetDatabase;
    use Factories;

    public function testCreateEmployee(): void
    {
        $dateTomorrow = new \DateTimeImmutable('tomorrow');
        $response = static::createClient()->request('POST', '/api/employees', ['json' => [
            'firstName' => 'John',
            'lastName' => 'Snow',
            'email' => 'johnsnow@winterfell.xyz',
            'joinedAt' => $dateTomorrow->format(DATE_W3C),
            'salary' => 200,
        ]]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Employee',
            '@type' => 'Employee',
            'firstName' => 'John',
            'lastName' => 'Snow',
            'email' => 'johnsnow@winterfell.xyz',
            'joinedAt' => $dateTomorrow->format(DATE_W3C),
            'salary' => 200,
        ]);
        $this->assertMatchesRegularExpression('~^/api/employees/\d+$~', $response->toArray()['@id']);
    }

    public function testCreateInvalidEmployeeWithBlankFields(): void
    {
        static::createClient()->request('POST', '/api/employees', ['json' => []]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');

        $this->assertJsonContains([
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'firstName: This value should not be blank.
lastName: This value should not be blank.
email: This value should not be blank.
joinedAt: This value should not be blank.
salary: This value should not be blank.',
        ]);
    }

    public function testCreateInvalidEmployeeWithDateInThePast(): void
    {
        static::createClient()->request('POST', '/api/employees', ['json' => [
            'firstName' => 'John',
            'lastName' => 'Snow',
            'email' => 'johnsnow@winterfell.xyz',
            'joinedAt' => '2022-03-02T09:16:00+00:00',
            'salary' => 200,
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');
        $this->assertJsonContains(['hydra:description' => 'joinedAt: This value should not be set in the past']);
    }

    public function testCreateInvalidEmployeeWithSmallSalary(): void
    {
        static::createClient()->request('POST', '/api/employees', ['json' => [
            'firstName' => 'John',
            'lastName' => 'Snow',
            'email' => 'johnsnow@winterfell.xyz',
            'joinedAt' => (new \DateTimeImmutable('tomorrow'))->format(DATE_W3C),
            'salary' => 99,
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');
        $this->assertJsonContains(['hydra:description' => 'salary: This value should be greater than or equal to 100.']);
    }

    public function testUpdateEmployee(): void
    {
        EmployeeFactory::createOne([
            'email' => 'jaime@casterlyrock.xyz',
            'joinedAt' => new \DateTimeImmutable('tomorrow'),
        ]);

        $client = static::createClient();
        $iri = $this->findIriBy(Employee::class, [
            'email' => 'jaime@casterlyrock.xyz',
        ]);

        $client->request('PATCH', $iri, [
            'json' => [
                'lastName' => 'Lannister',
            ],
            'headers' => [
                'Content-Type' => 'application/merge-patch+json',
            ],
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => $iri,
            'email' => 'jaime@casterlyrock.xyz',
            'lastName' => 'Lannister',
        ]);
    }

    public function testDeleteEmployee(): void
    {
        EmployeeFactory::createOne([
            'email' => 'nedstark@winterfell.xyz',
            'joinedAt' => new \DateTimeImmutable('tomorrow'),
        ]);
        $client = static::createClient();
        $iri = $this->findIriBy(Employee::class, ['email' => 'nedstark@winterfell.xyz']);

        $client->request('DELETE', $iri);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
            static::getContainer()->get('doctrine')->getRepository(Employee::class)
                ->findOneBy(['email' => 'nedstark@winterfell.xyz'])
        );
    }
}
