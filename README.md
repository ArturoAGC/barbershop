# ✂ BarberBook

Sistema web de reservas para barberías.

## 📋 Descripción

BarberBook permite a los clientes de una barbería reservar citas en línea de forma rápida y sencilla. El sistema gestiona la disponibilidad de estilistas, los servicios ofrecidos y los horarios de atención.

## 👥 Roles del sistema

| Rol | Acceso |
|---|---|
| **Cliente** | Reservar citas, ver historial, cancelar reservas |
| **Administrador** | Gestionar servicios, estilistas y todas las reservas |

## 🚀 Tecnologías

- **Backend:** Laravel + PHP
- **Frontend:** Blade + Tailwind CSS + Alpine.js
- **Base de datos:** MySQL
- **Deploy:** Railway

## 📁 Estructura del proyecto
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/           ← Panel de administración
│   │   ├── Client/          ← Portal del cliente
│   │   └── AuthController   ← Autenticación
│   └── Middleware/
│       └── IsAdmin.php      ← Control de acceso por rol
└── Models/
    ├── User.php
    ├── Service.php
    ├── Barber.php
    └── Reservation.php
```


## 🌿 Ramas del proyecto

| Rama | Rol | Descripción |
|---|---|---|
| `main` | Tech Lead | Rama principal, código estable |
| `feature/backend-auth` | Backend | Autenticación y roles |
| `feature/backend-services` | Backend | CRUD de servicios |
| `feature/backend-barbers` | Backend | CRUD de estilistas |
| `feature/backend-reservations` | Backend | Lógica de reservas |
| `feature/frontend-layouts` | Frontend | Layout y estilos base |
| `feature/frontend-admin` | Frontend | Vistas del panel admin |
| `feature/frontend-client` | Frontend | Vistas del portal cliente |
| `feature/qa-testing` | QA | Pruebas y corrección de bugs |

## 📅 Plan de sprints

| Sprint | Descripción | Fecha |
|---|---|---|
| Sprint 1 | Setup, migraciones, modelos, rutas | 7 abril 2026 |
| Sprint 2 | Autenticación con roles | 7 abril 2026 |
| Sprint 3 | CRUD servicios y estilistas | 7 abril 2026 |
| Sprint 4 | Portal cliente y reservas | 7 abril 2026 |
| Sprint 5 | Dashboard admin y estadísticas | 14 abril 2026 |
| Sprint 6 | Testing, bugs, deploy en Railway | 14 abril 2026 |

## 👨‍💻 Desarrollador

**Arturo Arath Gonzalez Chavez**  
Residencia Profesional — Ingeniería en Software
Dev-Binary-x © 2026