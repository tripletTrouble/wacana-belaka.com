<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a category', function () {
    $user = User::factory()->create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
    ]);

    $this->actingAs($user);
    $response = $this->post('/post-categories', [
        'name' => 'Test Category',
    ]);

    $response->assertRedirect('/post-categories');
    $this->assertDatabaseHas('post_categories', ['name' => 'Test Category']);
});

it('can update a category', function () {
    $user = User::factory()->create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
    ]);

    $this->actingAs($user);
    
    $this->post('/post-categories', [
        'name' => 'Original Category Name',
    ]);

    $category = \App\Models\PostCategory::where('name', 'Original Category Name')->first();

    $response = $this->put("/post-categories/{$category->id}", [
        'name' => 'Updated Category Name',
    ]);

    $response->assertRedirect('/post-categories');
    $this->assertDatabaseHas('post_categories', ['name' => 'Updated Category Name']);
});

it('can create category using UI', function () {
    $user = User::factory()->create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
    ]);

    $this->actingAs($user);

    $page = visit('/post-categories')
        ->assertSee('Kategori')
        ->click('Tambah')
        ->assertSee('Buat Kategori')
        ->type('name', 'Test kategori baru')
        ->press('Buat')
        ->assertPathIs('/post-categories');
});