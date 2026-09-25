<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SupabaseService
{
    private string $url;
    private string $key;

    public function __construct()
    {
        $this->url = rtrim(config('services.supabase.url'), '/');
        $this->key = config('services.supabase.key');
    }

    public function get(string $table, array $query = [])
    {
        return Http::withHeaders([
            'apikey' => $this->key,
        ])->get(
            $this->url . '/rest/v1/' . $table,
            $query
        );
    }
}