<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ContactService;
use App\Models\Contact;
use App\Http\Requests\StoreContactRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Illuminate\Support\Collection;

class ContactServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ContactService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ContactService::class);
    }

    public function test_create_contact()
    {
        $data = [
            'name' => 'Teste',
            'email' => 'teste@email.com',
            'phone' => '12345678',
            'mobile' => '999999999',
            'address' => 'Rua Teste',
            'district' => 'Centro',
            'state' => 'SP'
        ];

        $service = app(ContactService::class);

        $contact = $service->create($data);

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertDatabaseHas('contacts', [
            'email' => 'teste@email.com',
            'name' => 'Teste'
        ]);
    }

    public function test_find_by_id_returns_contact_when_exists()
    {
        $contact = Contact::factory()->create();

        $service = app(ContactService::class);

        $found = $service->findById($contact->id);

        $this->assertInstanceOf(Contact::class, $found);
        $this->assertEquals($contact->id, $found->id);
        $this->assertEquals($contact->email, $found->email);
    }

    public function test_update_contact()
    {
        $contact = Contact::factory()->create([
            'name' => 'Antigo Nome',
            'email' => 'antigo@email.com',
        ]);

        $service = app(ContactService::class);

        $data = [
            'name' => 'Novo Nome',
            'email' => 'novo@email.com',
            'phone' => '12345678',
            'mobile' => '999999999',
            'address' => 'Rua Nova',
            'district' => 'Centro',
            'state' => 'SP'
        ];

        $updated = $service->update($contact->id, $data);

        $this->assertInstanceOf(Contact::class, $updated);
        $this->assertEquals('Novo Nome', $updated->name);
        $this->assertEquals('novo@email.com', $updated->email);
        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'name' => 'Novo Nome',
            'email' => 'novo@email.com'
        ]);
    }

    public function test_get_all_returns_all_contacts()
    {
        $contacts = Contact::factory()->count(3)->create();

        $service = app(ContactService::class);

        $allContacts = $service->getAll();

        $this->assertCount(3, $allContacts);
        $this->assertInstanceOf(Collection::class, $allContacts);

        foreach ($contacts as $contact) {
            $this->assertTrue($allContacts->contains('id', $contact->id));
        }
    }

    public function test_delete_contact()
    {
        $contact = Contact::factory()->create();

        $service = app(ContactService::class);      

        $service->delete($contact->id);

        $this->assertDatabaseMissing('contacts', [
            'id' => $contact->id
        ]);
    }

    public function test_find_by_any_returns_contact_by_email()
    {
        $contact = Contact::factory()->create(['email' => 'teste@email.com']);
        $found = $this->service->findByAny(['email' => 'teste@email.com']);
        $this->assertNotNull($found);
        $this->assertEquals($contact->id, $found->id);
    }

    public function test_find_by_any_returns_contact_by_mobile()
    {
        $contact = Contact::factory()->create(['mobile' => '999999999']);
        $found = $this->service->findByAny(['mobile' => '999999999']);
        $this->assertNotNull($found);
        $this->assertEquals($contact->id, $found->id);
    }

    public function test_find_by_any_returns_contact_by_phone()
    {
        $contact = Contact::factory()->create(['phone' => '888888888']);
        $found = $this->service->findByAny(['phone' => '888888888']);
        $this->assertNotNull($found);
        $this->assertEquals($contact->id, $found->id);
    }

    public function test_find_by_any_returns_contact_by_name()
    {
        $contact = Contact::factory()->create(['name' => 'John Doe']);
        $found = $this->service->findByAny(['name' => 'John Doe']);
        $this->assertNotNull($found);
        $this->assertEquals($contact->id, $found->id);
    }

    public function test_find_by_any_returns_null_when_not_found()
    {
        $found = $this->service->findByAny(['email' => 'johndoe@email.com']);
        $this->assertNull($found);
    }
}