<?php

namespace App\Livewire\Motoristas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Motoristas extends Component
{
    use WithPagination;

    public $name, $email, $direccion, $telefono, $password, $password_confirmation;
    public $motoristaId;
    public $modalOpen = false;

    protected $paginationTheme = 'bootstrap'; // Para usar estilos de Bootstrap

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
        $this->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|string|min:8|max:15|regex:/^[0-9\-\+\(\)\s]+$/|unique:users,telefono',
                'password' => 'required|string|min:8|confirmed'
            ],
            [
                'name.required' => 'Por favor ingresa el nombre del motorista.',
                'name.max' => 'El nombre no puede tener más de 255 caracteres.',
                'email.required' => 'Por favor ingresa el correo electrónico.',
                'email.email' => 'El correo electrónico debe ser válido.',
                'email.unique' => 'Este correo electrónico ya está registrado.',
                'direccion.required' => 'Por favor ingresa la dirección del motorista.',
                'direccion.max' => 'La dirección no puede tener más de 255 caracteres.',
                'telefono.required' => 'Por favor ingresa el número de teléfono.',
                'telefono.min' => 'El teléfono debe tener al menos 8 dígitos.',
                'telefono.unique' => 'Este teléfono ya está registrado.',
                'telefono.regex' => 'El formato del teléfono no es válido.',
                'password.required' => 'Por favor ingresa una contraseña.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'Las contraseñas no coinciden.'
            ]
        );

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'password' => Hash::make($this->password),
            'rol' => 'Motorista'
        ]);

        $this->dispatch(
            'toast',
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
        $this->password = ''; // No cargar la contraseña
        $this->password_confirmation = '';
        $this->modalOpen = true;
    }

    public function update($id)
    {
        $motorista = User::findOrFail($id);

        $this->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $motorista->id,
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|string|min:8|max:15|regex:/^[0-9\-\+\(\)\s]+$/|unique:users,telefono,' . $motorista->id,
                'password' => 'nullable|string|min:8|confirmed'
            ],
            [
                'name.required' => 'Por favor ingresa el nombre del motorista.',
                'name.max' => 'El nombre no puede tener más de 255 caracteres.',
                'email.required' => 'Por favor ingresa el correo electrónico.',
                'email.email' => 'El correo electrónico debe ser válido.',
                'email.unique' => 'Este correo electrónico ya está registrado.',
                'direccion.required' => 'Por favor ingresa la dirección del motorista.',
                'direccion.max' => 'La dirección no puede tener más de 255 caracteres.',
                'telefono.required' => 'Por favor ingresa el número de teléfono.',
                'telefono.min' => 'El teléfono debe tener al menos 8 dígitos.',
                'telefono.unique' => 'Este teléfono ya está registrado.',
                'telefono.regex' => 'El formato del teléfono no es válido.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'Las contraseñas no coinciden.'
            ]
        );

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion
        ];

        // Solo actualizar contraseña si se proporcionó una nueva
        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $motorista->update($data);

        $this->dispatch(
            'toast',
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

            $this->dispatch(
                'toast',
                message: 'Motorista eliminado correctamente',
                type: 'success'
            );
        } catch (\Exception $e) {
            $this->dispatch(
                'toast',
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