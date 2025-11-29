<?php

namespace App\Livewire\Motoristas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Motoristas extends Component
{
    use WithPagination;

    // NO incluir public $motoristas aquí
    
    public $name, $email, $direccion, $telefono, $password, $password_confirmation;
    public $motoristaId;
    public $modalOpen = false;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $motoristas = User::where('rol', 'Motorista')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('livewire.motoristas.motoristas', [
            'motoristas' => $motoristas
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->modalOpen = true;
    }

    public function store()
    {
        $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|string|min:8|max:255|regex:/^[0-9\-\+\(\)\s]+$/',
                'password' => 'required|string|min:8|confirmed'
            ],
            [
                // Validaciones para nombre
                'name.required' => 'Por favor ingresa el nombre del motorista.',
                'name.string' => 'El nombre debe ser texto válido.',
                'name.max' => 'El nombre no puede tener más de 255 caracteres.',

                // Validaciones para email
                'email.required' => 'Por favor ingresa el correo electrónico.',
                'email.email' => 'El correo electrónico debe ser válido.',
                'email.max' => 'El correo no puede tener más de 255 caracteres.',
                'email.unique' => 'Este correo electrónico ya está registrado.',

                // Validaciones para direccion
                'direccion.required' => 'Por favor ingresa la dirección del motorista.',
                'direccion.string' => 'La dirección debe ser texto válido.',
                'direccion.max' => 'La dirección no puede tener más de 255 caracteres.',

                // Validaciones para telefono
                'telefono.required' => 'Por favor ingresa el número de teléfono.',
                'telefono.string' => 'El teléfono debe ser texto válido.',
                'telefono.min' => 'El teléfono debe tener al menos 8 dígitos.',
                'telefono.max' => 'El teléfono no puede tener más de 15 caracteres.',
                'telefono.regex' => 'El formato del teléfono no es válido.',

                // Validaciones para contraseña
                'password.required' => 'Por favor ingresa una contraseña.',
                'password.string' => 'La contraseña debe ser texto válido.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'Las contraseñas no coinciden'
            ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'password' => Hash::make($this->password),
            'rol' => 'Motorista'
        ]);

        $this->resetPage();
        
        $this->dispatch('toast', 
            message: 'Motorista creado exitosamente',
            type: 'success'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $motorista = User::findOrFail($id);
        $this->motoristaId = $id;
        $this->name = $motorista->name;
        $this->email = $motorista->email;
        $this->direccion = $motorista->direccion;
        $this->telefono = $motorista->telefono;
        $this->password = '';
        $this->password_confirmation = '';
        $this->modalOpen = true;
    }

    public function update($id)
    {
        $motorista = User::findOrFail($id);

        $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $motorista->id,
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|string|min:8',
                'password' => 'nullable|string|min:8|confirmed'
            ],
            [
                // Validaciones para nombre
                'name.required' => 'Por favor ingresa el nombre del motorista.',
                'name.string' => 'El nombre debe ser texto válido.',
                'name.max' => 'El nombre no puede tener más de 255 caracteres.',

                // Validaciones para email
                'email.required' => 'Por favor ingresa el correo electrónico.',
                'email.email' => 'El correo electrónico debe ser válido.',
                'email.max' => 'El correo no puede tener más de 255 caracteres.',
                'email.unique' => 'Este correo electrónico ya está registrado.',

                // Validaciones para direccion
                'direccion.required' => 'Por favor ingresa la dirección del motorista.',
                'direccion.string' => 'La dirección debe ser texto válido.',
                'direccion.max' => 'La dirección no puede tener más de 255 caracteres.',

                // Validaciones para telefono
                'telefono.required' => 'Por favor ingresa el número de teléfono.',
                'telefono.string' => 'El teléfono debe ser texto válido.',
                'telefono.min' => 'El teléfono debe tener al menos 8 dígitos.',
                'telefono.max' => 'El teléfono no puede tener más de 15 caracteres.',
                'telefono.regex' => 'El formato del teléfono no es válido.',

                // Validaciones para contraseña
                'password.string' => 'La contraseña debe ser texto válido.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'Las contraseñas no coinciden'
            ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $motorista->update($data);

        $this->resetPage();

        $this->dispatch('toast', 
            message: 'Motorista actualizado exitosamente',
            type: 'success'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        try {
            User::find($id)->delete();

            $this->resetPage();

            $this->dispatch('toast', 
                message: 'Motorista eliminado correctamente',
                type: 'success'
            );
        } catch (\Exception $e) {
            $this->dispatch('toast', 
                message: 'Error al eliminar el motorista',
                type: 'error'
            );
        }
    }

    public function closeModal()
    {
        $this->modalOpen = false;
        $this->resetValidation();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->direccion = '';
        $this->telefono = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->motoristaId = null;
        $this->resetValidation();
    }
}