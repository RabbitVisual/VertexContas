<?php

namespace Modules\HomePage\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if the homepage loads successfully.
     */
    public function test_homepage_is_accessible(): void
    {
        $response = $this->get(route('homepage'));
        $response->assertStatus(200);
        $response->assertSee('Vertex');
        $response->assertSee('Contas');
    }

    /**
     * Test if the terms page loads successfully.
     */
    public function test_terms_page_is_accessible(): void
    {
        $response = $this->get(route('terms'));
        $response->assertStatus(200);
        $response->assertSee('Termos de Uso');
    }

    /**
     * Test if the privacy page loads successfully.
     */
    public function test_privacy_page_is_accessible(): void
    {
        $response = $this->get(route('privacy'));
        $response->assertStatus(200);
        $response->assertSee('Política de Privacidade');
    }

    /**
     * Test if the help center page loads successfully.
     */
    public function test_help_center_page_is_accessible(): void
    {
        $response = $this->get(route('help-center'));
        $response->assertStatus(200);
        $response->assertSee('Como podemos');
        $response->assertSee('ajudar?');
        $response->assertSee('Primeiros Passos');
        $response->assertSee('Dúvidas Frequentes');
    }

    /**
     * Test if the FAQ page loads successfully.
     */
    public function test_faq_page_is_accessible(): void
    {
        $response = $this->get(route('faq'));
        $response->assertStatus(200);
        $response->assertSee('Perguntas Frequentes');
        $response->assertSee('Como podemos');
        $response->assertSee('Geral');
        $response->assertSee('Financeiro');
    }

    /**
     * Test if the feature pages load successfully.
     */
    public function test_feature_pages_are_accessible(): void
    {
        $pages = [
            route('features.index') => 'Gestão de Receitas',
            route('features.goals') => 'Metas Financeiras',
            route('features.reports') => 'Distribuição de Gastos',
        ];

        foreach ($pages as $route => $content) {
            $response = $this->get($route);
            $response->assertStatus(200);
            $response->assertSee($content);
            $response->assertSee('Começar Agora'); // Validates Navbar presence
        }
    }

    /**
     * Test if auth pages are accessible to guests.
     */
    public function test_auth_pages_are_accessible(): void
    {
        $this->get(route('login'))->assertStatus(200);
        $this->get(route('register'))->assertStatus(200);
        $this->get(route('password.request'))->assertStatus(200);
    }

    /**
     * Test successful user registration.
     */
    public function test_user_can_register(): void
    {
        $userData = [
            'first_name' => 'João',
            'last_name' => 'Silva',
            'email' => 'joao@exemplo.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'cpf' => '123.456.789-00',
            'birth_date' => '1990-01-01',
            'phone' => '(11) 98765-4321',
        ];

        $response = $this->post(route('register'), $userData);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'email' => 'joao@exemplo.com',
            'cpf' => '123.456.789-00',
        ]);
        $this->assertAuthenticated();
    }

    /**
     * Test failed registration with existing email.
     */
    public function test_user_cannot_register_with_existing_email(): void
    {
        User::factory()->create(['email' => 'duplicate@exemplo.com']);

        $userData = [
            'first_name' => 'Maria',
            'last_name' => 'Oliveira',
            'email' => 'duplicate@exemplo.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test login with email.
     */
    public function test_user_can_login_with_email(): void
    {
        $user = User::factory()->create([
            'email' => 'test@exemplo.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        $response = $this->post(route('login'), [
            'login_type' => 'email',
            'email' => 'test@exemplo.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/user');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test login with CPF.
     */
    public function test_user_can_login_with_cpf(): void
    {
        $user = User::factory()->create([
            'cpf' => '111.222.333-44',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        $response = $this->post(route('login'), [
            'login_type' => 'cpf',
            'cpf' => '111.222.333-44',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/user');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test admin login redirects to admin panel.
     */
    public function test_admin_login_redirects_to_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@exemplo.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);
        $admin->assignRole('admin');

        $response = $this->post(route('login'), [
            'login_type' => 'email',
            'email' => 'admin@exemplo.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
    }

    /**
     * Test support agent login redirects to support panel.
     */
    public function test_support_login_redirects_to_panel(): void
    {
        $agent = User::factory()->create([
            'email' => 'agent@exemplo.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);
        $agent->assignRole('suporte');

        $response = $this->post(route('login'), [
            'login_type' => 'email',
            'email' => 'agent@exemplo.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/support');
        $this->assertAuthenticatedAs($agent);
    }

    /**
     * Test failed login.
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@exemplo.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        $response = $this->post(route('login'), [
            'login_type' => 'email',
            'email' => 'test@exemplo.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test user logout.
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
