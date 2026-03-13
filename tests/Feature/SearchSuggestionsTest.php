<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchSuggestionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_suggestions_return_matching_item(): void
    {
        $category = Category::create([
            'name' => 'Audio',
            'slug' => 'audio',
        ]);

        Equipment::create([
            'category_id' => $category->id,
            'name' => 'HT WLAN UHF',
            'slug' => 'wlan-uhf',
            'description' => 'Audio wireless handset',
            'price_per_day' => 10000,
            'stock' => 20,
            'status' => 'ready',
        ]);

        $response = $this->getJson(route('search.suggestions', ['q' => 'wlan']));

        $response->assertOk();
        $response->assertJsonPath('data.0.name', 'HT WLAN UHF');
    }

    public function test_search_suggestions_return_fuzzy_match_for_small_typo(): void
    {
        $category = Category::create([
            'name' => 'Lighting',
            'slug' => 'lighting',
        ]);

        Equipment::create([
            'category_id' => $category->id,
            'name' => 'Aputure NOVA II 2x1 Tunable Color LED Light Panel',
            'slug' => 'aputure-nova-ii-2x1',
            'description' => 'Large lighting panel',
            'price_per_day' => 250000,
            'stock' => 3,
            'status' => 'ready',
        ]);

        $response = $this->getJson(route('search.suggestions', ['q' => 'aputur']));

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.name', 'Aputure NOVA II 2x1 Tunable Color LED Light Panel');
    }

    public function test_search_suggestions_return_empty_when_query_has_no_relevant_match(): void
    {
        $category = Category::create([
            'name' => 'Camera',
            'slug' => 'camera',
        ]);

        Equipment::create([
            'category_id' => $category->id,
            'name' => 'Sony FX3',
            'slug' => 'sony-fx3',
            'description' => 'Cinema camera',
            'price_per_day' => 250000,
            'stock' => 3,
            'status' => 'ready',
        ]);

        $response = $this->getJson(route('search.suggestions', ['q' => 'zz-not-found']));

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }
}
