# Iglesia Eben-Ezer — Huánuco

Sitio web oficial de la **Iglesia Eben-Ezer** — comunidad de avivamiento y evangelismo.
Listo para producción gratuita en **Render (web) + Neon (Postgres)**.

## Qué incluye

Página institucional con historia, misión y visión, ministerios, direcciones,
eventos, contacto y formulario de decisión espiritual con ubigeo del Perú.
Panel de administración con dashboard, eventos, ministerios, decisiones,
mensajes y configuración. Donaciones por Yape.

### Páginas públicas (clean URLs vía `public/index.php`)

| URL | Vista | Descripción |
|---|---|---|
| `/` | `views/pages/home.php` | Hero, eventos destacados, contacto, donaciones |
| `/history` | `views/pages/history.php` | Reseña histórica |
| `/mission-vision` | `views/pages/mission-vision.php` | Principios y objetivos |
| `/ministries` | `views/pages/ministries.php` | Listado de ministerios y direcciones |
| `/directions` | `views/pages/directions.php` | Direcciones y coordinación |
| `/ministry-youth` | `views/pages/ministry-youth.php` | Jóvenes: líderes, fotos, videos |
| `/ministry-kids` | `views/pages/ministry-kids.php` | Niños |
| `/ministry-family` | `views/pages/ministry-family.php` | Familia |
| `/ministry-evangelism` | `views/pages/ministry-evangelism.php` | Evangelismo |
| `/direction-missions` | `views/pages/direction-missions.php` | Misiones |
| `/direction-communications` | `views/pages/direction-communications.php` | Comunicaciones, redes, vivos, portafolio |
| `/events` | `views/pages/events.php` | Próximos eventos |
| `/contact` | `views/pages/contact.php` | Info + formulario |
| `/decision` | `views/pages/decision.php` | Entrega / reconciliación + ubigeo |
| `/daily-reading` | `views/pages/daily-reading.php` | Playlist YouTube |

URLs antiguas (`historia.php`, `eventos.php`, etc.) redirigen vía
`public/.htaccess` a la URL limpia (ver reglas 301).

### Panel admin (`admin/` = controladores, `views/admin/` = vistas)

| URL | Controlador | Vista |
|---|---|---|
| `/admin/login.php` | `admin/login.php` | `views/admin/login.php` |
| `/admin/index.php` | `admin/index.php` | `views/admin/dashboard.php` |
| `/admin/events.php` | `admin/events.php` | `views/admin/events.php` |
| `/admin/ministries.php` | `admin/ministries.php` | `views/admin/ministries.php` |
| `/admin/decisions.php` | `admin/decisions.php` | `views/admin/decisions.php` |
| `/admin/messages.php` | `admin/messages.php` | `views/admin/messages.php` |
| `/admin/settings.php` | `admin/settings.php` | `views/admin/settings.php` |

Credenciales iniciales desde env `ADMIN_USER` / `ADMIN_PASSWORD`
(default `admin` / `admin123` — cámbialas en producción).

## Estructura de carpetas (nombres en inglés)

```
├── public/             ← DocumentRoot (front controller, health, .htaccess)
│   ├── index.php       ← router → routes/web.php → views/pages/*
│   └── health.php      ← /health.php para Render
├── views/
│   ├── pages/          ← 15 vistas públicas (home, history, mission-vision, ...)
│   ├── partials/       ← header.php, footer.php
│   └── admin/          ← 7 vistas del panel (dashboard, events, ...)
├── routes/
│   ├── web.php         ← mapa URL limpia → vista
│   └── admin.php       ← mapa admin → controlador + vista
├── styles/             ← CSS (base, header-nav, components, contact, communications, responsive, admin)
├── assets/
│   ├── images/         ← fotos y logos
│   └── data/
│       └── ubigeo.json ← departamentos/provincias/distritos Perú
├── src/
│   ├── config.php      ← rutas, BASE_URL, timezone
│   ├── database.php    ← PDO Postgres (Neon) + fallback SQLite, API db_*
│   ├── auth.php        ← guard de sesión con cookies seguras
│   └── helpers.php     ← e(), redirect(), csrf_*(), json_col()
├── admin/              ← controladores del panel (lógica + require a views/admin)
├── db/
│   └── schema.sql      ← esquema Postgres (psql $DATABASE_URL -f db/schema.sql)
├── scripts/
│   └── migrate.php     ← importa data/*.json legacy a la DB
├── data/               ← solo local: app.db SQLite (gitignored) + JSON legacy para migrar
├── Dockerfile          ← php:8.2-apache + pdo_pgsql, DocumentRoot public/
├── render.yaml         ← blueprint Render (web Docker free + env)
└── .dockerignore
```

## Tecnologías

PHP 8.2, PDO Postgres + SQLite fallback, HTML5 + CSS3, JS vanilla,
sin composer ni build. Docker para Render.

## Desarrollo local

Requisitos: PHP 8.1+ con extensiones `pdo_pgsql` y `pdo_sqlite`.

```bash
# 1. Sembrar SQLite local + migrar JSON legacy
php -d extension=pdo_sqlite -d extension=sqlite3 scripts/migrate.php

# 1b. Solo en Windows (crear enlaces locales a styles/assets/admin).
# En Linux/Mac usa: ln -s ../styles public/styles && ln -s ../assets public/assets && ln -s ../admin public/admin
# En PowerShell admin no requerido (junctions):
# New-Item -ItemType Junction -Path "$((Get-Location).Path)/public/styles" -Target "$((Get-Location).Path)/styles"
# New-Item -ItemType Junction -Path "$((Get-Location).Path)/public/assets" -Target "$((Get-Location).Path)/assets"
# New-Item -ItemType Junction -Path "$((Get-Location).Path)/public/admin" -Target "$((Get-Location).Path)/admin"

# 2. Servir con public/ como DocumentRoot (única entrada: public/index.php)
php -d extension=pdo_sqlite -d extension=sqlite3 -S localhost:8000 -t public public/index.php

# 3. Abrir
# Sitio:  http://localhost:8000/  o  http://localhost:8000/history
# Admin:  http://localhost:8000/admin/login.php  (admin / admin123)
# Health: http://localhost:8000/health.php
```

## Despliegue Render + Neon (gratis)

1. Crea proyecto en [Neon](https://neon.tech), copia la **pooled connection string**
   (`postgres://...?sslmode=require`).
2. Crea las tablas: `psql "$DATABASE_URL" -f db/schema.sql`
   (o deja que `src/database.php` las cree solo al primer arranque).
3. Migra datos legacy una vez: `DATABASE_URL="..." php scripts/migrate.php`.
4. En Render → New → Web Service → Docker, apunta a este repo.
   Health check: `/health.php`. Variables:
   - `DATABASE_URL` = URL pooled de Neon (secret, no commitear)
   - `ADMIN_USER` = admin
   - `ADMIN_PASSWORD` = clave fuerte (secret)
   - `BASE_URL` = vacío
5. Dockerfile expone `public/` como DocumentRoot y enlaza
   `public/styles → ../styles`, `public/assets → ../assets`,
   `public/admin → ../admin`. Apache escucha en `$PORT`.

Notas gratis: Render free se duerme a los 15 min (cold start ~30 s),
Neon escala a cero a los 5 min (wakeup ~500 ms). Para una iglesia con
tráfico bajo entran holgados en 100 CU-hours/mes y 0.5 GB de Neon.

## Seguridad

Sesiones con `httponly + samesite=Lax + secure` en HTTPS (`src/auth.php`),
regeneración de ID al login, delay anti-fuerza-bruta, `htmlspecialchars`
en vistas, bloqueo Apache de `src/ views/ routes/ db/ scripts/ data/`
y de `*.db`/`.env`. Cambia `ADMIN_PASSWORD` y no commitees `data/*.json`.

## Licencia

Todos los derechos reservados — Iglesia Eben-Ezer.
