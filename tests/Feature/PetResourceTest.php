<?php

use App\Models\User;
use App\Filament\Owner\Resources\PetResource;
use App\Models\Pet;

use function Pest\Laravel\get;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed();
    $this->ownerUser = User::whereName('Owner 1')->first();
    actingAs($this->ownerUser);
});

it('renders the index page', function () {
    get(PetResource::getUrl('index', panel: 'owner'))
        ->assertOk();
});

it('renders the create page', function () {
    get(PetResource::getUrl('create', panel: 'owner'))
        ->assertOk();
});

it('renders the edit page', function () {
    $pet = Pet::factory()
        ->for($this->ownerUser, relationship: 'owner')
        ->create();

    get(PetResource::getUrl('edit', ['record' => $pet], panel: 'owner'))
        ->assertOk();
});

it('cannot edit pets that do not belong to the owner', function () {
    $pet = Pet::factory()->create();
    get(PetResource::getUrl('edit', ['record' => $pet], panel: 'owner'))
        ->assertStatus(403);
});

it('can list pets', function () {
    $pets = Pet::factory(3)
        ->for($this->ownerUser, relationship: 'owner')
        ->create();

    Livewire::test(PetResource\Pages\ListPets::class)
        ->assertCanSeeTableRecords($pets)
        ->assertSeeText([
            $pets[0]->name,
            $pets[0]->date_of_birth->format(config('app.date_format')),
            $pets[0]->type->name,

            $pets[1]->name,
            $pets[1]->date_of_birth->format(config('app.date_format')),
            $pets[1]->type->name,

            $pets[2]->name,
            $pets[2]->date_of_birth->format(config('app.date_format')),
            $pets[2]->type->name,
        ]);
});


it('only show pets for the current owner', function () {
    $myPet = Pet::factory()
        ->for($this->ownerUser, relationship: 'owner')
        ->create();

    $otherOwner = User::factory()->role('owner')->create();

    $otherPet = Pet::factory()
        ->for($otherOwner, relationship: 'owner')
        ->create();

    Livewire\Livewire::test(PetResource\Pages\ListPets::class)
        ->assertSeeText($myPet->name)
        ->assertDontSeeText($otherPet->name);

});

it('can create pet', function () {
    $newPet = Pet::factory()
        ->for($this->ownerUser, relationship: 'owner')
        ->make();

    \Livewire\Livewire::test(PetResource\Pages\CreatePet::class)
        ->fillForm([
            'name' => $newPet->name,
            'date_of_birth' => $newPet->date_of_birth,
            'type' => $newPet->type,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Pet::class, [
        'name' => $newPet->name,
        'date_of_birth' => $newPet->date_of_birth,
        'type' => $newPet->type,
    ]);
});

it('validate form errors on create', function (Pet $newPet) {
    \Livewire\Livewire::test(PetResource\Pages\CreatePet::class)
        ->fillForm([
            'name' => $newPet->name,
            'date_of_birth' => $newPet->date_of_birth,
            'type' => $newPet->type,
        ])
        ->call('create')
        ->assertHasFormErrors();

    $this->assertDatabaseMissing(Pet::class, [
        'name' => $newPet->name,
        'date_of_birth' => $newPet->date_of_birth,
        'type' => $newPet->type,
    ]);
})->with([
    [fn() => Pet::factory()->state(['name' => null])->make(), 'Missing name'],
    [fn() => Pet::factory()->state(['date_of_birth' => null])->make(), 'Missing date of birth'],
    [fn() => Pet::factory()->state(['type' => null])->make(), 'Missing type'],
]);

it('can retrieve the pet data for edit', function () {
    $pet = Pet::factory()
        ->for($this->ownerUser, relationship: 'owner')
        ->create();

    \Livewire\Livewire::test(PetResource\Pages\EditPet::class, [
        'record' => $pet->getRouteKey(),
    ])
        ->assertFormSet([
            'name' => $pet->name,
            'date_of_birth' => $pet->date_of_birth->format(config('app.date_format')),
            'type' => $pet->type->value,
        ]);
});

it('can update the pet', function () {
    $pet = Pet::factory()
        ->for($this->ownerUser, relationship: 'owner')
        ->create();

    $newPetData = Pet::factory()
        ->state([
            'name' => fake()->name,
            'date_of_birth' => fake()->date()
        ])
        ->for($this->ownerUser, relationship: 'owner')
        ->make();

    \Livewire\Livewire::test(PetResource\Pages\EditPet::class, [
        'record' => $pet->getRouteKey(),
    ])
        ->fillForm([
            'name' => $newPetData->name,
            'date_of_birth' => $newPetData->date_of_birth->format(config('app.date_format')),
            'type' => $newPetData->type->value,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($pet->refresh())
        ->name->toBe($newPetData->name)
        ->date_of_birth->format(config('app.date_format'))->toBe($newPetData->date_of_birth->format(config('app.date_format')))
        ->type->value->toBe($newPetData->type->value);
});

it('validate form errors on edit', function (Pet $updatedPet) {
    $pet = Pet::factory()
        ->for($this->ownerUser, relationship: 'owner')
        ->create();


    \Livewire\Livewire::test(PetResource\Pages\EditPet::class, [
        'record' => $pet->getRouteKey()
    ])
        ->fillForm([
            'name' => $updatedPet->name,
            'date_of_birth' => $updatedPet->date_of_birth,
            'type' => $updatedPet->type,
        ])
        ->call('save')
        ->assertHasFormErrors();

})->with([
    [fn() => Pet::factory()->state(['name' => null])->make(), 'Missing name'],
    [fn() => Pet::factory()->state(['date_of_birth' => null])->make(), 'Missing date of birth'],
    [fn() => Pet::factory()->state(['type' => null])->make(), 'Missing type'],
]);

it('can delete a pet from the edit pet form', function () {
    $pet = Pet::factory()
        ->for($this->ownerUser, relationship: 'owner')
        ->create();

    \Livewire\Livewire::test(PetResource\Pages\EditPet::class, [
        'record' => $pet->getRouteKey(),
    ])
    ->callAction(\Filament\Actions\DeleteAction::class);

    $this->assertModelMissing($pet);
});

it('can delete a pet from the list of pets', function () {
    $pet = Pet::factory()
        ->for($this->ownerUser, relationship: 'owner')
        ->create();

    \Livewire\Livewire::test(PetResource\Pages\ListPets::class)
        ->assertTableActionVisible('delete', $pet)
        ->callTableAction(\Filament\Actions\DeleteAction::class, $pet);

    $this->assertModelMissing($pet);
});
