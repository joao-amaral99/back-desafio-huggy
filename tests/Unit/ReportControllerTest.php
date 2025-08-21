<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\ReportController;
use App\Services\Contracts\ContactServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Mockery;

class ReportControllerTest extends TestCase
{
 
    public function test_contacts_by_city_returns_json_response()
    {
        $mockService = $this->createMock(ContactServiceInterface::class);

        $mockData = Collection::make([
            ['city' => 'São Paulo', 'count' => 2],
            ['city' => 'Rio de Janeiro', 'count' => 1]
        ]);

        $mockService->method('contactsByCity')
                    ->willReturn($mockData);

        $controller = new ReportController($mockService);

        $response = $controller->contactsByCity();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(['data' => $mockData->toArray()], $response->getData(true));
    }

    public function test_contacts_by_state_returns_json_response()
    {
        $mockService = $this->createMock(ContactServiceInterface::class);

        $mockData = Collection::make([
            ['state' => 'SP', 'count' => 2],
            ['state' => 'RJ', 'count' => 1]
        ]);

        $mockService->method('contactsByState')
                    ->willReturn($mockData);

        $controller = new ReportController($mockService);

        $response = $controller->contactsByState();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(['data' => $mockData->toArray()], $response->getData(true));
    }
}