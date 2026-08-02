<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LayoutNavigationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that student routes render the user navbar and hide the admin sidebar.
     */
     public function test_student_routes_render_user_navbar_and_hide_admin_sidebar(): void
     {
         $user = User::factory()->create([
             'name' => 'Test Student',
             'email' => 'student@test.com',
             'role' => 'student',
             'english_level' => 'B2'
         ]);

        $this->actingAs($user);

        $studentPaths = [
            '/',
            '/vocabulary',
            '/grammar',
            '/listening',
            '/document',
            '/reading-analyzer',
            '/assessments',
            '/profile',
        ];

        foreach ($studentPaths as $path) {
            $response = $this->get($path);
            $response->assertStatus(200);
            $response->assertSee('user-navbar');
            $response->assertDontSee('sidebar-nav-wrapper');
        }
    }

    /**
     * Test that admin routes render the admin sidebar and hide the user navbar.
     */
    public function test_admin_routes_render_admin_sidebar_and_hide_user_navbar(): void
    {
        $user = User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'english_level' => 'C1'
        ]);

        $this->actingAs($user);

        $adminPaths = [
            '/vocabulary-admin',
            '/grammar-admin',
            '/listening-admin',
            '/document-admin',
            '/assessments-admin',
            '/achievements-admin',
        ];

        foreach ($adminPaths as $path) {
            $response = $this->get($path);
            $response->assertStatus(200);
            $response->assertSee('sidebar-nav-wrapper');
            $response->assertDontSee('user-navbar');
        }
    }

    /**
     * Test that student accounts are restricted from accessing admin routes.
     */
    public function test_student_accounts_are_restricted_from_admin_routes(): void
    {
        $user = User::factory()->create([
            'name' => 'Test Student Restricted',
            'email' => 'student-restricted@test.com',
            'role' => 'student',
            'english_level' => 'B1'
        ]);

        $this->actingAs($user);

        $adminPaths = [
            '/vocabulary-admin',
            '/grammar-admin',
            '/listening-admin',
            '/document-admin',
            '/assessments-admin',
            '/achievements-admin',
        ];

        foreach ($adminPaths as $path) {
            $response = $this->get($path);
            // Should redirect to profile index with unauthorized error message
            $response->assertStatus(302);
            $response->assertRedirect('/profile');
            $response->assertSessionHas('error', 'Bạn không có quyền truy cập vào khu vực Quản trị!');
        }
    }
}
