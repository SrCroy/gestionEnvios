<?php

namespace Tests\Feature\Livewire\Clientes;

use App\Livewire\Clientes\Clientes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ClientesTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(Clientes::class)
            ->assertStatus(200);
    }
}
