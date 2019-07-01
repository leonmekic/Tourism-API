<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccommodationTest extends TestCase
{
    use DatabaseTransactions;

    public function testBasicTest()
    {
        $user =  $user = factory(User::class)->make();

        $response = $this->be($user)
             ->get('/api/v1/categories/');
        $response->assertSuccessful();
    }
}
