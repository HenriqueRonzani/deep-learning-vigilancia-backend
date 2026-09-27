<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_criar_um_usuario()
    {
        $payload = [
            'name' => 'Agente Silva',
            'email' => 'silva@vigilancia.com',
        ];

        $response = $this->postJson('/api/users', $payload);

        // O seu controller retorna HTTP_NO_CONTENT (204) na criação
        $response->assertNoContent();

        $this->assertDatabaseHas('users', [
            'email' => 'silva@vigilancia.com',
            'name' => 'Agente Silva'
        ]);

        // Verifica se a lógica do Str::before($email, '@') funcionou
        $user = User::where('email', 'silva@vigilancia.com')->first();
        $this->assertTrue(Hash::check('silva', $user->password));
    }

    public function test_pode_atualizar_um_usuario()
    {
        $user = User::factory()->create();

        $payload = [
            'name' => 'Nome Atualizado',
            'email' => 'atualizado@teste.com',
        ];

        $response = $this->putJson("/api/users/{$user->id}", $payload);

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Nome Atualizado']);

        $this->assertDatabaseHas('users', ['email' => 'atualizado@teste.com']);
    }

    public function test_pode_atualizar_a_senha_do_usuario()
    {
        $user = User::factory()->create(['password' => bcrypt('SenhaAntiga123')]);

        $payload = [
            // Usando uma senha forte com letras e números para garantir que
            // passe pela validação do Password::defaults() do Laravel
            'password' => 'NovaSenhaSegura123!'
        ];

        $response = $this->patchJson("/api/users/{$user->id}", $payload);

        $response->assertOk();

        // Confirma que a nova senha foi criptografada e salva no banco
        $user->refresh();
        $this->assertTrue(Hash::check('NovaSenhaSegura123!', $user->password));
    }

    public function test_pode_listar_usuarios_paginados()
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'email']
                ],
                'total',
                'per_page'
            ]);
    }

    public function test_pode_buscar_usuario_por_id()
    {
        $user = User::factory()->create(['name' => 'Usuário Específico']);

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Usuário Específico']);
    }

    public function test_pode_deletar_usuario()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertNoContent(); // 204

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
