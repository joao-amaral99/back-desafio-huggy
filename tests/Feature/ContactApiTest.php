<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Contact;

class ContactApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_contact_api()
    {
        $payload = [
            'name' => 'Maria Oliveira',
            'email' => 'maria.oliveira@email.com',
            'phone' => '11223344',
            'mobile' => '11987654321',
            'address' => 'Rua das Flores, 123',
            'district' => 'Centro',
            'state' => 'SP'
        ];

        $response = $this->postJson('/api/contacts', $payload);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'Contato criado com sucesso!',
                'data' => [
                    'name' => 'Maria Oliveira',
                    'email' => 'maria.oliveira@email.com',
                ]
            ]);

        $this->assertDatabaseHas('contacts', [
            'email' => 'maria.oliveira@email.com',
            'name' => 'Maria Oliveira'
        ]);
    }

    public function test_update_contact_api()
    {
        $contact = Contact::factory()->create([
            'name' => 'Antigo Nome',
            'email' => 'antigo@email.com',
        ]);

        $payload = [
            'name' => 'Nome Atualizado',
            'email' => 'atualizado@email.com',
            'phone' => '40028922',
            'mobile' => '988888888',
            'address' => 'Rua Atualizada',
            'district' => 'Novo Bairro',
            'state' => 'BA'
        ];

        $response = $this->putJson("/api/contacts/{$contact->id}", $payload);

        $response
        ->assertStatus(200)
        ->assertJson([
            'message' => 'Contato atualizado com sucesso!',
            'data' => [
                'name' => 'Nome Atualizado',
                'email' => 'atualizado@email.com',
            ]
        ]);

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'name' => 'Nome Atualizado',
            'email' => 'atualizado@email.com'
        ]);
    }

    public function test_get_contact_by_id_api()
    {
        $contact = Contact::factory()->create([
            'name' => 'Contato Teste',
            'email' => 'contato@email.com',
        ]);

        $response = $this->getJson("/api/contacts/{$contact->id}");

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $contact->id,
                    'name' => 'Contato Teste',
                    'email' => 'contato@email.com',
                ]
            ]);
    }

    public function test_get_all_contacts_api()
    {
        $contacts = Contact::factory()->count(3)->create();

        $response = $this->getJson('/api/contacts');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ]);

        foreach ($contacts as $contact) {
            $response->assertJsonFragment([
                'id' => $contact->id,
                'name' => $contact->name,
                'email' => $contact->email
            ]);
        }
    }

    public function test_get_all_contacts_with_search_filter_api()
    {
        Contact::factory()->create(['name' => 'Alice', 'email' => 'alice@example.com']);
        Contact::factory()->create(['name' => 'Bob', 'email' => 'bob@test.com']);
        Contact::factory()->create(['name' => 'Charlie', 'city' => 'New York']);

        $response = $this->getJson('/api/contacts?search=alice');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => 'Alice']);

        $response = $this->getJson('/api/contacts?search=york');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => 'Charlie']);

        $response = $this->getJson('/api/contacts?search=nonexistent');
        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    public function test_delete_contact_api()
    {
        $contact = Contact::factory()->create();

        $response = $this->deleteJson("/api/contacts/{$contact->id}");

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Contato deletado com sucesso!'
            ]);

        $this->assertDatabaseMissing('contacts', [
            'id' => $contact->id
        ]);
    }
}