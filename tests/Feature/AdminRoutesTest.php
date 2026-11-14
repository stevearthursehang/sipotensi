<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AdminRoutesTest extends TestCase
{
    public function test_admin_routes_load_successfully(): void
    {
        $user = User::where('email', 'superadmin@example.com')->first();
        $this->assertNotNull($user, 'Superadmin user must exist');

        $firstPotensi = \App\Models\PotensiInvestasi::first();
        $potensiId = $firstPotensi ? $firstPotensi->id : 1;

        $routes = [
            '/admin',
            '/admin/kecamatans',
            '/admin/kecamatans/create',
            '/admin/kelurahans',
            '/admin/kelurahans/create',
            '/admin/infrastrukturs',
            '/admin/infrastrukturs/create',
            '/admin/potensi-investasis',
            '/admin/potensi-investasis/create',
            '/admin/upload-geo-json',
            "/potensi/{$potensiId}",
            "/potensi/{$potensiId}/pdf",
            '/api/cuaca-bahari',
            '/lang/en',
            '/lang/id',
            '/',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get($route);
            if ($response->getStatusCode() >= 400 && isset($response->exception)) {
                dump($route . ' failed with: ' . $response->exception->getMessage() . ' at ' . $response->exception->getFile() . ':' . $response->exception->getLine());
            }
            $this->assertContains(
                $response->getStatusCode(),
                [200, 302],
                "Route {$route} failed with status {$response->getStatusCode()}"
            );
        }
    }
}

