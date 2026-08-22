# 🌐 Sistema de Gestión ISP (Estilo WispHub)

Este es un sistema minimalista y práctico desarrollado en **Laravel** para la administración de un Proveedor de Servicios de Internet (ISP). Permite registrar planes de velocidad, gestionar clientes, llevar el control de pagos mensuales y visualizar alertas de mora en un panel centralizado.

---

## 🚀 Características Implementadas

*   **Autenticación:** Sistema de login basado en Laravel Breeze (configurado actualmente para acceso por correo electrónico).
*   **Dashboard Financiero:** Panel principal que calcula ingresos del mes actual, cuenta de clientes activos y alertas de pagos vencidos.
*   **Gestión de Planes:** CRUD para crear paquetes de internet, definiendo velocidades de subida/bajada y precio.
*   **Gestión de Clientes:** Registro de clientes asociados a un plan específico, día de facturación y asignación de IP. Generación automática de la primera factura al registrar.
*   **Control de Caja (Pagos):** Listado general de facturas con insignias visuales de estado (Pagado, Pendiente, Atrasado). Procesamiento de pagos con un solo clic.

---

## 🛠️ Requisitos del Sistema

*   PHP 8.2 o superior
*   Composer
*   Node.js y NPM (para compilar Tailwind CSS)
*   Base de datos (MySQL, PostgreSQL o SQLite)

---

## ⚙️ Instrucciones de Instalación y Uso

1. **Clonar o descargar el repositorio.**
2. **Instalar dependencias de PHP:**
   ```bash
   composer install

   Instalar dependencias de Node y compilar estilos:

Bash
npm install
npm run build
Configurar el entorno:
Copia el archivo .env.example a .env y configura tus credenciales de base de datos.

Bash
cp .env.example .env
php artisan key:generate
Ejecutar migraciones:

Bash
php artisan migrate
Levantar el servidor local:

Bash
php artisan serve
🗄️ Estructura de la Base de Datos
El sistema se basa en 4 entidades principales fuertemente relacionadas para mantener la integridad financiera y de los servicios.

Fragmento de código
erDiagram
    USERS {
        id bigint PK
        name varchar
        email varchar "Login"
        password varchar
    }
    
    PLANS {
        id bigint PK
        name varchar "Ej: Básico 50 Megas"
        download_speed int "Mbps"
        upload_speed int "Mbps"
        price decimal "Mensualidad"
    }

    CLIENTS {
        id bigint PK
        document_number varchar "Único"
        full_name varchar
        phone varchar
        address varchar
        ip_address varchar "Para Mikrotik"
        billing_day date "Día de corte"
        status enum "active, suspended"
        plan_id bigint FK
    }

    PAYMENTS {
        id bigint PK
        amount decimal
        due_date date "Vencimiento"
        paid_at datetime "Fecha real de pago"
        status enum "pending, paid, late"
        client_id bigint FK
    }

    PLANS ||--o{ CLIENTS : "asignado a"
    CLIENTS ||--o{ PAYMENTS : "debe/paga"
Notas Importantes para el Futuro:
Mikrotik API: El sistema tiene el terreno preparado para conectar el PaymentController (método markAsPaid) con el servicio de RouterOS, permitiendo reactivar la conexión (ip_address) automáticamente tras un pago.

Facturación Automática: Pendiente configurar el Task Scheduling de Laravel (Comandos Cron) para generar facturas masivas mes a mes basadas en el billing_day del cliente.


***

Con esto tienes documentado todo tu progreso hasta este punto de forma profesional. 

# Guía de Comandos del Sistema

## 1. Comandos Artisan Personalizados (Console & Scheduler)

* `php artisan app:check-daily-invoices`
  * **Descripción:** Revisa el ciclo de facturación de todos los usuarios y genera o marca como pendientes las facturas correspondientes a la fecha actual.
  * **Frecuencia automatizada:** Todos los días a las **00:00 AM**.

* `php artisan isp:corte-mora`
  * **Descripción:** Identifica los clientes con pagos pendientes cuya fecha límite ya venció (`due_date < hoy`). Cambia su estado a `suspended` en Laravel y agrega su IP a la lista `SUSPENDIDOS` en la MikroTik correspondiente.
  * **Frecuencia automatizada:** Todos los días a las **00:05 AM**.

* `php artisan schedule:run`
  * **Descripción:** Ejecuta el programador de tareas de Laravel en segundo plano. Evalúa y dispara las tareas definidas según la hora programada (`00:00` y `00:05`).
  * **Frecuencia recomendada:** Cada minuto (vía Cron Job del servidor).

---

## 2. Mantenimiento y Limpieza de Caché

* `php artisan config:clear`
  * **Descripción:** Elimina la caché de configuración. Necesario cada vez que se modifican las variables en el archivo `.env`.

* `php artisan route:clear`
  * **Descripción:** Limpia el registro de rutas compiladas. Resuelve errores de resolvedores de dependencias y rutas no encontradas.

* `php artisan view:clear`
  * **Descripción:** Elimina la caché de las plantillas Blade compiladas.

---

## 3. Base de Datos y Modelos

* `php artisan make:model Router -m`
  * **Descripción:** Genera el modelo Eloquent `Router` junto con su archivo de migración de base de datos.

* `php artisan migrate`
  * **Descripción:** Ejecuta las migraciones pendientes para crear o modificar tablas en la base de datos MySQL.

---

## 4. Compilación de Assets (Frontend / Vite)

* `npm run build`
  * **Descripción:** Compila y minifica los archivos CSS y JS generados por Vite en la carpeta `public/build/` junto con el archivo `manifest.json`. Requerido antes de subir cambios a producción en hosting compartido.

---

## 5. Configuración de Red en VPS AWS (Port Forwarding IPTables / WireGuard)

* `sudo sysctl -w net.ipv4.ip_forward=1`
  * **Descripción:** Habilita el reenvío de paquetes IP dentro del sistema operativo del servidor Linux en AWS.

* `sudo iptables -t nat -A PREROUTING -p tcp --dport 8001 -j DNAT --to-destination 10.0.0.2:80`
  * **Descripción:** Redirige las peticiones entrantes al puerto público `8001` del VPS hacia la IP interna de WireGuard `10.0.0.2` (Router 1) en el puerto `80`.

* `sudo iptables -t nat -A PREROUTING -p tcp --dport 8002 -j DNAT --to-destination 10.0.0.3:80`
  * **Descripción:** Redirige las peticiones entrantes al puerto público `8002` del VPS hacia la IP interna de WireGuard `10.0.0.3` (Router 2) en el puerto `80`.

* `sudo iptables -t nat -A PREROUTING -p tcp --dport 8003 -j DNAT --to-destination 10.0.0.4:80`
  * **Descripción:** Redirige las peticiones entrantes al puerto público `8003` del VPS hacia la IP interna de WireGuard `10.0.0.4` (Router 3) en el puerto `80`.

* `sudo iptables -t nat -A POSTROUTING -j MASQUERADE`
  * **Descripción:** Aplica enmascaramiento de red NAT para que las respuestas de los MikroTik puedan retornar correctamente a través de la interfaz de WireGuard.

* `sudo netfilter-persistent save`
  * **Descripción:** Guarda las reglas de `iptables` configuradas para que permanezcan activas incluso si el VPS de AWS se reinicia.

---

## 6. Configuración de Cron Job en Servidor (cPanel)

* `* * * * * cd /home/centicca/SystemConectV3 && php artisan schedule:run >> /dev/null 2>&1`
  * **Descripción:** Tarea del sistema operativo Linux en el hosting que ejecuta el planificador de Laravel cada minuto.