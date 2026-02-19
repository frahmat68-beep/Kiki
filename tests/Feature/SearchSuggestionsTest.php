<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchSuggestionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_suggestions_endpoint_returns_matching_equipments(): void
    {
        $category = Category::create([
            'name' => 'Camera',
            'slug' => 'camera',
        ]);

        Equipment::create([
            'category_id' => $category->id,
            'name' => 'Sony FX3',
            'slug' => 'sony-fx3',
            'description' => 'Full frame cinema camera.',
            'price_per_day' => 850000,
            'stock' => 3,
            'status' => 'ready',
            'image' => null,
        ]);

        Equipment::create([
            'category_id' => $category->id,
            'name' => 'Canon C70',
            'slug' => 'canon-c70',
            'description' => 'Cinema camera.',
            'price_per_day' => 900000,
            'stock' => 2,
            'status' => 'ready',
            'image' => null,
        ]);

        $response = $this->getJson(route('search.suggestions', ['q' => 'sony']));

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.name', 'Sony FX3');
        $response->assertJsonPath('data.0.slug', 'sony-fx3');
        $response->assertJsonPath('data.0.category', 'Camera');
        $response->assertJsonPath('data.0.detail_url', route('product.show', 'sony-fx3'));
    }

    public function test_search_suggestions_returns_empty_result_for_short_query(): void
    {
        $response = $this->getJson(route('search.suggestions', ['q' => 's']));

        $response->assertOk();
        $response->assertExactJson([
            'data' => [],
        ]);
    }
}
