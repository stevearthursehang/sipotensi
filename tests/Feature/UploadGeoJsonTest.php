<?php

namespace Tests\Feature;

use App\Filament\Pages\UploadGeoJson;
use App\Models\Kelurahan;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\TestCase;

class UploadGeoJsonTest extends TestCase
{
    public function test_upload_geojson_page_loads()
    {
        $user = User::where('email', 'superadmin@example.com')->first();
        $this->actingAs($user);

        Livewire::test(UploadGeoJson::class)
            ->assertSuccessful();
    }
}
