<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Alter column geom to geometry type
        DB::statement('ALTER TABLE kelurahans ALTER COLUMN geom TYPE geometry(Geometry, 4326) USING ST_GeomFromGeoJSON(geom::json)');
        // Add spatial index
        DB::statement('CREATE INDEX IF NOT EXISTS kelurahans_geom_idx ON kelurahans USING GIST (geom)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop spatial index
        DB::statement('DROP INDEX IF EXISTS kelurahans_geom_idx');
        // Alter back to text
        DB::statement('ALTER TABLE kelurahans ALTER COLUMN geom TYPE text USING ST_AsText(geom)');
    }
};
