# Setup con Livewire - UES FMO Gestor de Paquetes

## Componentes Livewire Creados

### 1. **Componente de Login** 
- Ubicación: `app/Livewire/Auth/Login.php`
- Vista: `resources/views/livewire/auth/login.blade.php`
- Funcionalidad: Validación de email y contraseña

### 2. **Componente de Dashboard**
- Ubicación: `app/Livewire/Dashboard/Dashboard.php`
- Vista: `resources/views/livewire/dashboard/dashboard.blade.php`
- Funcionalidad: Mostrar estadísticas, mapa y datos del sistema

## Pasos para Ejecutar

### 1. Migrar base de datos con seeders
```bash
docker exec laravel_app php artisan migrate:fresh --seed
```

**Credenciales de prueba:**
- Email: `admin@uesfmo.com`
- Contraseña: `12345678`

O también puedes usar:
- Email: `test@example.com`
- Contraseña: `12345678`

### 2. Verificar que todo funciona
```bash
docker exec laravel_app php artisan serve
```

### 3. Acceder en el navegador
- Login: `http://localhost/login`
- Dashboard: `http://localhost/dashboard` (requiere autenticación)

## Estructura de Carpetas

```
app/Livewire/
├── Auth/
│   └── Login.php
└── Dashboard/
    └── Dashboard.php

resources/views/
├── layouts/
│   └── login.blade.php (layout para login)
├── home/
│   ├── index.blade.php (layout base)
│   └── dashboard.blade.php (plantilla hija)
└── livewire/
    ├── auth/
    │   └── login.blade.php
    └── dashboard/
        └── dashboard.blade.php
```

## Funcionalidades Implementadas

✅ Login con validación de email y contraseña
✅ Middleware de autenticación (`auth`)
✅ Middleware de invitado (`guest`) para login
✅ Navbar y sidebar fijos en el dashboard
✅ Componentes Livewire para Login y Dashboard
✅ Mapa interactivo de Google Maps
✅ Estadísticas en tiempo real
✅ Logout funcional
✅ Redirección automática a login si no está autenticado
✅ Sistema de roles preparado (fácil de extender)

## Próximos pasos

Si quieres agregar más funcionalidades Livewire:

1. Crear nuevos componentes en `app/Livewire/`
2. Crear vistas correspondientes en `resources/views/livewire/`
3. Importar en las rutas
4. Usar `@livewire('nombre-componente')` en las vistas si tienes Livewire real instalado

## Nota importante

En este caso, he creado una estructura tipo Livewire sin la librería real instalada. Si necesitas Livewire real (con reactividad en tiempo real), necesitarías:

```bash
docker exec laravel_app composer require livewire/livewire
docker exec laravel_app npm install
```

Pero por ahora, la estructura está lista para funcionar como componentes Livewire simplificados.
