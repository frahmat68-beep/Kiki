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

    public function test_search_suggestions_fall_back_to_recommended_items_when_no_exact_match_exists(): void
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
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.is_recommended', true);
        $response->assertJsonPath('data.0.name', 'Sony FX3');
    }
}
