# COLEGIADOS SaaS

Plataforma multiinstitucion para la gestion regional de registros profesionales, gobierno institucional, control etico, convenios y actividades academicas de colegios de enfermeria.

## Alcance funcional

El sistema incluye los 17 registros solicitados:

- Enfermeras colegiadas
- Enfermeras con maestria
- Enfermeras con doctorado
- Enfermeras con segunda especialidad
- Enfermeras auditoras
- Asociaciones cientificas
- Auspicios
- Procesos disciplinarios y sanciones eticas
- Denuncias por ejercicio ilegal
- Autoridades por periodo
- Distinciones honorificas
- Defunciones de miembros del orden
- Beneficiarios del FAM
- Conciliadores extrajudiciales
- Cesantes y jubiladas
- Actividades investigativas
- Convenios

Ademas incorpora base SaaS:

- Multi tenancy por institucion
- Membresias por usuario e institucion
- Suscripciones y planes
- Soft deletes en tablas de negocio
- Catalogos reutilizables para universidades, centros laborales y organizaciones

## Stack

- Laravel 11
- Livewire 3
- Tailwind CSS
- PostgreSQL como base de datos objetivo

## Puesta en marcha

1. Instalar dependencias:

```bash
composer install
npm install
```

2. Copiar entorno y ajustar PostgreSQL:

```bash
cp .env.example .env
php artisan key:generate
```

3. Configurar en `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=colegiados
DB_USERNAME=postgres
DB_PASSWORD=tu_clave
DB_SCHEMA=public
DB_SSLMODE=prefer
```

4. Levantar esquema, datos demo y frontend:

```bash
php artisan migrate --seed
npm run build
```

5. Iniciar aplicacion:

```bash
php artisan serve
```

## Credenciales demo

- Usuario: `admin@colegiados.test`
- Clave: `secret123`

## Estructura principal

- `database/migrations`: esquema SaaS y registros institucionales
- `app/Models`: modelo multiinstitucion y entidades de dominio
- `app/Services/RegistryModuleService.php`: definicion central de modulos, formularios, validaciones y persistencia
- `app/Http/Livewire`: dashboard, catalogo, CRUD generico por registro, perfil e institucion
- `resources/views/livewire`: vistas responsivas para operacion diaria

## Validacion realizada

Se verifico localmente:

- `php artisan migrate:fresh --seed`
- `php artisan test`
- `php artisan view:cache`
- `npm run build`

Si vas a desplegar con PostgreSQL, apunta el `.env` a tu instancia y ejecuta nuevamente `php artisan migrate --seed` en ese entorno.
