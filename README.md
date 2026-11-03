# Sistema de Soporte Técnico · IPET N.º 363

Aplicación web para gestionar tickets de problemas tecnológicos del colegio.

## Características

- **Login** de usuarios
- **Dashboard** con resumen visual de estados
- **Listado de tickets** con filtros (estado, prioridad, ubicación, categoría, búsqueda)
- **Alta de tickets** (título, descripción, categoría, prioridad, ubicación, equipo)
- **Edición de tickets** (corregir datos si se cargaron mal)
- **Detalle + historial de intervenciones** (relación 1:N)
- **Cambio de estado** con observación (workflow)
- **Estadísticas** por estado, categoría, ubicación y prioridad

## Estructura de archivos

```
proyecto-soporte/
├── conexion.php              # Conexión PDO + helpers de sesión
├── index.php                 # Login
├── logout.php
├── dashboard.php             # Inicio / panel
├── tickets.php               # Listado + filtros
├── nuevo_ticket.php          # Formulario de alta
├── editar_ticket.php         # Formulario de edición (CRUD completo)
├── detalle_ticket.php        # Detalle + historial + cambio de estado
├── estadisticas.php          # Reportes e indicadores
├── instalar_bd.sql           # Script para crear la BD con datos reales
├── includes/
│   ├── header.php
│   └── footer.php
├── assets/
│   ├── css/estilos.css
│   └── img/logo.png
└── backend/
    └── cambiar_estado.php
```

## Instalación (XAMPP / phpMyAdmin)

1. **Crear la base de datos**
   - Abrí phpMyAdmin → pestaña SQL
   - Copiá y ejecutá todo el contenido de `instalar_bd.sql`

2. **Configurar la conexión**
   - Editá `conexion.php` si tu usuario/contraseña de MySQL no es `root` / vacío.

3. **Copiar el proyecto**
   - Copiá la carpeta `proyecto-soporte` dentro de `htdocs` (XAMPP) o `www` (WAMP).

4. **Abrir en el navegador**
   ```
   http://localhost/proyecto-soporte/
   ```

5. **Credenciales de prueba**
   | Usuario  | Contraseña | Rol              |
   |----------|------------|------------------|
   | tapia    | 123456     | Profesor         |
   | soporte  | 123456     | Soporte técnico  |

## Flujo de trabajo del ticket

```
Pendiente → En revisión → En reparación → Solucionado
```

Cada cambio de estado genera una **intervención** con fecha, usuario y observación. Así se mantiene el historial completo de lo que se le hizo a cada equipo.

## Notas

- Las contraseñas están en texto plano solo para la demo de las pasantías. En un entorno real usar `password_hash()`.
- El diseño visual está inspirado en la maqueta HTML original que tenían.
