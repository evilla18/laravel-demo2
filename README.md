# TaskBoard

App de gestion de tareas con autenticacion JWT. Proyecto demo de la capacitacion Claude AI (Dia 2).

## Stack

- **Backend:** Laravel 13 + PHP 8.4 + tymon/jwt-auth + SQLite
- **Frontend:** React 19 + Vite + Tailwind CSS 4 + Axios + React Router
- **Infraestructura:** Docker + Docker Compose

## Levantar el proyecto

### Con Docker (recomendado)

```bash
docker compose up --build
```

- Backend: http://localhost:8000
- Frontend: http://localhost:5173

```bash
# Detener
docker compose down

# Reconstruir desde cero
docker compose down -v
docker compose build --no-cache
docker compose up
```

### Sin Docker (manual)

Requiere PHP 8.4+, Composer 2, Node.js 20+.

```bash
# Backend
php artisan serve

# Frontend (en otra terminal)
cd frontend && npm run dev
```

## Endpoints API

| Metodo | Ruta | Descripcion | Auth |
|--------|------|-------------|------|
| POST | /api/register | Registro (name, email, password, password_confirmation) | No |
| POST | /api/login | Login (email, password) -> token JWT | No |
| POST | /api/logout | Cerrar sesion | Si |
| GET | /api/me | Usuario actual | Si |
| GET | /api/tasks | Listar tareas del usuario | Si |
| POST | /api/tasks | Crear tarea (title, description) | Si |
| PUT | /api/tasks/{id} | Actualizar tarea | Si |
| DELETE | /api/tasks/{id} | Eliminar tarea | Si |

Todas las respuestas siguen el formato: `{ data, message, status }`

Autenticacion via header: `Authorization: Bearer {token}`

## Estructura del proyecto

```
/                     Proyecto Laravel (API)
  app/
    Http/
      Controllers/    AuthController, TaskController
      Requests/       StoreTaskRequest, UpdateTaskRequest
    Models/           User, Task
  config/             auth, jwt, cors, database
  database/
    migrations/       users, tasks
  routes/
    api.php           Endpoints de la API
  Dockerfile          Backend (php:8.4-cli)
  docker-compose.yml  Orquestacion

/frontend/            Proyecto React (SPA)
  src/
    context/          AuthContext (auth state)
    pages/            LoginPage, RegisterPage, DashboardPage
    services/         api.js (axios + JWT interceptor)
  Dockerfile          Frontend (node:22-alpine)
```

## Funcionalidades

- Registro e inicio de sesion con JWT
- CRUD completo de tareas personales
- Tareas: crear, listar, marcar como completada, eliminar
- Rutas protegidas (redirige a login si no hay token)
- Validacion de formularios (cliente y servidor)
- Verificacion de ownership en backend (403 si no es el dueno)
- Dockerizado con hot-reload en desarrollo
