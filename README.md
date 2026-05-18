# Iglesia Eben-Ezer

Sitio web oficial de la **Iglesia Eben-Ezer** — una comunidad de avivamiento y evangelismo.

## ¿Qué es?

Página web institucional de la iglesia con información sobre su historia, ministerios, direcciones, eventos próximos y formularios de contacto. Incluye una sección donde las personas pueden expresar su decisión de entregarse a Cristo o reconciliarse con Dios, y un panel de administración para gestionar todo el contenido sin tocar código.

## Estado actual

### Páginas públicas (13)
| Página | Descripción |
|---|---|
| **Inicio** | Hero, eventos destacados, formulario de contacto, botón de donaciones |
| **Historia** | Reseña histórica de la iglesia |
| **Misión y Visión** | Principios y objetivos |
| **Ministerios** | Listado de todos los ministerios y direcciones |
| **Ministerio de Jóvenes** | Líderes, fotos, videos, actividades |
| **Ministerio de Niños** | Líderes, fotos, videos, actividades |
| **Ministerio de Familia** | Líderes, fotos, videos, actividades |
| **Ministerio de Evangelismo** | Líderes, fotos, videos, actividades |
| **Dirección de Misiones** | Líderes, fotos, videos, actividades |
| **Dirección de Comunicaciones** | Líderes, fotos, videos, actividades |
| **Eventos** | Próximos eventos con fecha |
| **Contacto** | Información de contacto + formulario |
| **Quiero Entregarme a Jesús** | Formulario de decisión espiritual con ubigeo de Perú |

### Panel de administración
- **Dashboard** con estadísticas (eventos, ministerios, decisiones, mensajes)
- **Eventos** — crear, editar, eliminar
- **Ministerios** — editar contenido (líderes, fotos, videos, actividades)
- **Decisiones** — ver registros de personas que se entregaron/reconciliaron
- **Mensajes** — ver y gestionar mensajes del formulario de contacto
- **Configuración** — editar info de la iglesia y cambiar contraseña

### Credenciales por defecto
- Usuario: `admin`
- Contraseña: `admin123`

> ⚠️ **Cambia la contraseña inmediatamente** desde el panel de administración.

## Tecnologías

| Tecnología | Uso |
|---|---|
| **PHP** | Lógica del servidor, templates compartidos |
| **HTML5 + CSS3** | Estructura y estilos |
| **JavaScript (vanilla)** | Dropdowns, modal de donaciones, selects de ubigeo |
| **JSON** | Almacenamiento de datos (sin base de datos) |
| **Ubigeo Perú** | Selects en cascada: departamento → provincia → distrito |

### No requiere
- Base de datos (MySQL, PostgreSQL, etc.)
- Composer ni package manager
- Build tools ni bundlers
- Extensiones PHP adicionales

## Requisitos

- **PHP 7.4+** (funciona con PHP 8.x)
- Cualquier servidor web: Apache, Nginx, o el servidor integrado de PHP

## Instalación

### 1. Clonar el repositorio

```bash
git clone <url-del-repo>
cd Eben-Ezer
```

### 2. Ejecutar el servidor

```bash
php -S localhost:8000
```

### 3. Abrir en el navegador

- **Sitio público:** [http://localhost:8000](http://localhost:8000)
- **Panel admin:** [http://localhost:8000/admin/login.php](http://localhost:8000/admin/login.php)

## Estructura del proyecto

```
Eben-Ezer/
├── css/                        ← Estilos compartidos
│   ├── base.css                ← Variables CSS, reset, tipografía
│   ├── header-nav.css          ← Header, navegación, dropdowns
│   ├── components.css          ← Cards, grids, modales, botones
│   ├── contact.css             ← Sección de contacto + donaciones
│   └── responsive.css          ← Media queries (max-width: 768px)
├── includes/
│   ├── db.php                  ← Capa de datos JSON (auto-crea archivos)
│   ├── header.php              ← Header + nav reutilizable
│   ├── footer.php              ← Footer reutilizable
│   └── ubigeo.json             ← Departamentos, provincias y distritos del Perú
├── admin/                      ← Panel de administración
│   ├── css/admin.css           ← Estilos del admin
│   ├── auth.php                ← Verificación de sesión
│   ├── login.php               ← Login
│   ├── logout.php              ← Logout
│   ├── index.php               ← Dashboard
│   ├── events.php              ← CRUD de eventos
│   ├── ministries.php          ← Edición de ministerios
│   ├── decisions.php           ← Registro de decisiones espirituales
│   ├── messages.php            ← Mensajes de contacto
│   └── settings.php            ← Configuración + cambio de contraseña
├── data/                       ← Archivos JSON (auto-generados, no commitear)
│   ├── admin_users.json
│   ├── contact_messages.json
│   ├── decisiones.json
│   ├── events.json
│   ├── ministry_content.json
│   └── settings.json
├── img/                        ← Imágenes del sitio
├── *.php                       ← 13 páginas públicas
└── .htaccess                   ← Redirección .html → .php (Apache)
```

## Uso del panel de administración

### Eventos
1. Ir a **Admin → Eventos**
2. Llenar título, descripción, fecha e imagen
3. Marcar como activo/inactivo
4. Click en **Crear** o **Editar**

### Ministerios
1. Ir a **Admin → Ministerios**
2. Seleccionar un ministerio del panel izquierdo
3. Editar líderes, actividades, videos (URLs de YouTube), fotos (rutas)
4. Click en **Guardar Cambios**

### Decisiones
- Vista de solo lectura con todas las personas que llenaron el formulario "Quiero entregarme a Jesús"
- Muestra nombre, edad, teléfono, ubicación, tipo de decisión y fecha

### Mensajes
- Ver mensajes del formulario de contacto
- Marcar como leído o eliminar

### Configuración
- Editar nombre de la iglesia, email, teléfono, dirección, horario y URL de Facebook
- Cambiar contraseña de administrador

## Datos iniciales

Al primer acceso, el sistema crea automáticamente:
- **5 eventos** de ejemplo
- **6 ministerios/direcciones** con contenido base
- **1 usuario admin** (admin / admin123)
- **Configuración** con datos de la iglesia

Los archivos JSON se generan en `data/` y no deben incluirse en Git.

## Notas

- Los archivos `.html` antiguos se mantienen por compatibilidad. Se pueden eliminar una vez verificado que todo funciona en `.php`.
- Para producción, cambiar la contraseña del admin y agregar `data/*.json` al `.gitignore`.
- La imagen del QR de Yape en el modal de donaciones usa `img/actividades.jpg` como placeholder. Reemplazar con el QR real.

## Licencia

Todos los derechos reservados — Iglesia Eben-Ezer.
