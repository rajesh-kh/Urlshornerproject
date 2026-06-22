<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\ShortUrl;
use App\Models\User;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortUrlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // seed roles
        collect(['SuperAdmin','Admin','Member','Sales','Manager'])->each(fn($r) => Role::create(['name'=>$r]));
    }

    public function test_admin_cannot_create_short_url()
    {
        $role = Role::where('name', 'Admin')->first();
        $user = User::factory()->create();
        $user->roles()->attach($role);

        $resp = $this->actingAs($user)->postJson('/short-urls', ['original_url' => 'https://example.com']);
        $resp->assertStatus(403);
    }

    public function test_member_cannot_create_short_url()
    {
        $role = Role::where('name', 'Member')->first();
        $user = User::factory()->create();
        $user->roles()->attach($role);

        $resp = $this->actingAs($user)->postJson('/short-urls', ['original_url' => 'https://example.com']);
        $resp->assertStatus(403);
    }

    public function test_superadmin_cannot_create_short_url()
    {
        $role = Role::where('name', 'SuperAdmin')->first();
        $user = User::factory()->create();
        $user->roles()->attach($role);

        $resp = $this->actingAs($user)->postJson('/short-urls', ['original_url' => 'https://example.com']);
        $resp->assertStatus(403);
    }

    public function test_sales_can_create_short_url()
    {
        $role = Role::where('name', 'Sales')->first();
        $user = User::factory()->create();
        $user->roles()->attach($role);

        $resp = $this->actingAs($user)->postJson('/short-urls', ['original_url' => 'https://example.com']);
        $resp->assertStatus(201);
        $this->assertDatabaseCount('short_urls', 1);
    }

    public function test_admin_index_sees_non_company_urls()
    {
        $adminRole = Role::where('name','Admin')->first();
        $company1 = Company::create(['name' => 'One']);
        $company2 = Company::create(['name' => 'Two']);

        $user = User::factory()->create(['company_id' => $company1->id]);
        $user->roles()->attach($adminRole);

        // create urls with company 2 and 1
        ShortUrl::create(['slug'=>'a','original_url'=>'https://a.test','company_id'=>$company2->id,'created_by'=>$user->id]);
        ShortUrl::create(['slug'=>'b','original_url'=>'https://b.test','company_id'=>$company1->id,'created_by'=>$user->id]);

        $resp = $this->actingAs($user)->getJson('/short-urls');
        $resp->assertStatus(200);
        $data = $resp->json();
        $this->assertCount(1, $data);
    }

    public function test_member_index_sees_not_created_by_self()
    {
        $role = Role::where('name','Member')->first();
        $user = User::factory()->create();
        $user->roles()->attach($role);

        ShortUrl::create(['slug'=>'a','original_url'=>'https://a.test','company_id'=>null,'created_by'=>$user->id]);
        $other = User::factory()->create();
        ShortUrl::create(['slug'=>'b','original_url'=>'https://b.test','company_id'=>null,'created_by'=>$other->id]);

        $resp = $this->actingAs($user)->getJson('/short-urls');
        $resp->assertStatus(200);
        $data = $resp->json();
        $this->assertCount(1, $data);
    }

    public function test_resolve_requires_auth()
    {
        $role = Role::where('name','Sales')->first();
        $user = User::factory()->create();
        $user->roles()->attach($role);

        ShortUrl::create(['slug'=>'xyz','original_url'=>'https://example.com','company_id'=>null,'created_by'=>$user->id]);

        $this->get('/s/xyz')->assertStatus(403);
    }
}
