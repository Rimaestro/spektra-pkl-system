<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\RoleMiddleware;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    private RoleMiddleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new RoleMiddleware();
    }

    /** @test */
    public function it_allows_access_when_user_has_required_role()
    {
        $user = User::factory()->create(['role' => 'admin', 'status' => 'active']);
        $request = Request::create('/test');
        $request->setUserResolver(fn() => $user);

        $response = $this->middleware->handle($request, fn() => response('success'), 'admin');

        $this->assertEquals('success', $response->getContent());
    }

    /** @test */
    public function it_allows_access_when_user_has_one_of_multiple_required_roles()
    {
        $user = User::factory()->create(['role' => 'koordinator', 'status' => 'active']);
        $request = Request::create('/test');
        $request->setUserResolver(fn() => $user);

        $response = $this->middleware->handle($request, fn() => response('success'), 'admin', 'koordinator');

        $this->assertEquals('success', $response->getContent());
    }

    /** @test */
    public function it_denies_access_when_user_does_not_have_required_role()
    {
        $user = User::factory()->create(['role' => 'mahasiswa', 'status' => 'active']);
        $request = Request::create('/test');
        $request->setUserResolver(fn() => $user);

        $response = $this->middleware->handle($request, fn() => response('success'), 'admin');

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
    }

    /** @test */
    public function it_redirects_unauthenticated_users_to_login()
    {
        $request = Request::create('/test');
        $request->setUserResolver(fn() => null);

        $response = $this->middleware->handle($request, fn() => response('success'), 'admin');

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
    }

    /** @test */
    public function it_returns_json_response_for_api_requests()
    {
        $request = Request::create('/api/test');
        $request->headers->set('Accept', 'application/json');
        $request->setUserResolver(fn() => null);

        $response = $this->middleware->handle($request, fn() => response('success'), 'admin');

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    /** @test */
    public function it_handles_comma_separated_roles()
    {
        $user = User::factory()->create(['role' => 'dosen', 'status' => 'active']);
        $request = Request::create('/test');
        $request->setUserResolver(fn() => $user);

        $response = $this->middleware->handle($request, fn() => response('success'), 'admin,koordinator,dosen');

        $this->assertEquals('success', $response->getContent());
    }

    /** @test */
    public function it_allows_access_when_no_roles_specified()
    {
        $user = User::factory()->create(['role' => 'mahasiswa', 'status' => 'active']);
        $request = Request::create('/test');
        $request->setUserResolver(fn() => $user);

        $response = $this->middleware->handle($request, fn() => response('success'));

        $this->assertEquals('success', $response->getContent());
    }
}
