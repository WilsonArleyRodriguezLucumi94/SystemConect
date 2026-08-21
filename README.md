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

## prueba 1