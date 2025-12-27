# 🐬 CONFIGURACIÓN: WaasCRM con MySQL (XAMPP)

Si prefieres usar MySQL de XAMPP en lugar de PostgreSQL, sigue esta guía.

⚠️ **NOTA:** La aplicación está diseñada para PostgreSQL, pero puede funcionar con MySQL con algunas adaptaciones.

---

## 📋 REQUISITOS PREVIOS

- XAMPP instalado con MySQL funcionando
- Si MySQL no arranca, revisa primero: `XAMPP_MYSQL_FIX.md`

---

## 🔧 PASO 1: Verificar que MySQL está Funcionando

### Iniciar MySQL en XAMPP

1. **Abrir XAMPP Control Panel**
2. **Click en "Start" del módulo MySQL**
3. **Verificar que muestre "Running" en verde**

Si no arranca, consulta: `XAMPP_MYSQL_FIX.md`

---

## 🔧 PASO 2: Crear Base de Datos

### Opción A: Usando phpMyAdmin (Recomendado)

1. **Acceder a phpMyAdmin:**
   - Abre el navegador
   - Ve a: http://localhost/phpmyadmin

2. **Crear base de datos:**
   - Click en "Nueva" o "New" en el panel izquierdo
   - Nombre de la base de datos: `aqua_crm`
   - Cotejamiento: `utf8mb4_unicode_ci`
   - Click en "Crear"

### Opción B: Usando línea de comandos

1. **Abrir CMD**

2. **Conectar a MySQL:**
   ```cmd
   cd C:\xampp\mysql\bin
   mysql -u root -p
   ```

3. **Si no tiene contraseña, presiona Enter. Si tiene contraseña, ingrésala.**

4. **Crear base de datos:**
   ```sql
   CREATE DATABASE aqua_crm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

5. **Verificar:**
   ```sql
   SHOW DATABASES;
   ```
   (Deberías ver `aqua_crm` en la lista)

6. **Salir:**
   ```sql
   exit;
   ```

---

## 🔧 PASO 3: Habilitar Extensión PDO MySQL en PHP

Normalmente ya viene habilitada en XAMPP, pero verificamos:

1. **Abrir XAMPP Control Panel**

2. **Click en "Config" del módulo Apache**

3. **Seleccionar "PHP (php.ini)"**

4. **Buscar estas líneas** (Ctrl+F):
   ```ini
   extension=pdo_mysql
   extension=mysqli
   ```

5. **Asegurarse que NO tengan punto y coma (;) al inicio:**
   ```ini
   extension=pdo_mysql
   extension=mysqli
   ```

6. **Si tenían punto y coma, quítalo y guarda**

7. **Reiniciar Apache en XAMPP**

---

## 🔧 PASO 4: Configurar WaasCRM

### Crear archivo .env

1. **Navegar a la carpeta del proyecto:**
   ```cmd
   cd C:\xampp\htdocs\WaasCRM
   ```
   (o donde tengas el proyecto)

2. **Copiar .env.example a .env:**
   ```cmd
   copy .env.example .env
   ```

3. **Editar .env:**
   - Abre el archivo `.env` con un editor de texto
   - Busca la sección de base de datos
   - **CAMBIA** de `pgsql` a `mysql`:

   ```env
   APP_NAME=WaasCRM
   APP_ENV=local
   APP_KEY=
   APP_DEBUG=true
   APP_URL=http://localhost

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=aqua_crm
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   **IMPORTANTE:**
   - Si tu MySQL de XAMPP tiene contraseña, ponla en `DB_PASSWORD`
   - Por defecto XAMPP no tiene contraseña para root

4. **Configurar reCAPTCHA** (si tienes las claves):
   ```env
   RECAPTCHA_SITE_KEY=tu_site_key
   RECAPTCHA_SECRET_KEY=tu_secret_key
   ```

---

## 🔧 PASO 5: Modificar Migraciones para MySQL (IMPORTANTE)

Laravel/PostgreSQL usa algunos tipos de datos que MySQL no soporta igual. Necesitamos adaptarlos:

### Revisar archivo de migración problemático

Abre: `database/migrations/tenant/[fecha]_create_budgets_table.php`

Busca líneas con `jsonb` y cámbialas a `json`:

**ANTES:**
```php
$table->jsonb('details')->nullable();
```

**DESPUÉS:**
```php
$table->json('details')->nullable();
```

Haz esto para TODAS las migraciones que usen `jsonb`.

### Script para revisar automáticamente:

Desde CMD en la carpeta del proyecto:

```cmd
findstr /s /i "jsonb" database\migrations\*.php
```

Esto te mostrará todos los archivos que tienen `jsonb`. Edítalos y cambia `jsonb` a `json`.

---

## 🔧 PASO 6: Instalar Dependencias

### Instalar Composer (si no lo tienes)

1. **Descargar Composer:**
   - https://getcomposer.org/Composer-Setup.exe

2. **Ejecutar instalador:**
   - Durante la instalación, selecciona el PHP de XAMPP:
   - `C:\xampp\php\php.exe`

### Instalar dependencias PHP

```cmd
cd C:\xampp\htdocs\WaasCRM
composer install
```

### Instalar dependencias Node.js

1. **Instalar Node.js** (si no lo tienes):
   - https://nodejs.org/ (versión LTS)

2. **Instalar dependencias:**
   ```cmd
   npm install
   ```

---

## 🔧 PASO 7: Configurar Laravel

### Generar clave de aplicación

```cmd
php artisan key:generate
```

### Publicar configuración de Tenancy

```cmd
php artisan vendor:publish --provider="Stancl\Tenancy\TenancyServiceProvider"
```

---

## 🔧 PASO 8: Ejecutar Migraciones

### Migrar base de datos central

```cmd
php artisan migrate
```

Si te pregunta "Do you really wish to run this command?", escribe `yes` y presiona Enter.

**⚠️ Si hay errores:**
- Probablemente por tipos de datos incompatibles
- Revisa el PASO 5 nuevamente
- Busca el archivo de migración que falla
- Adapta los tipos de datos para MySQL

### Migrar base de datos de tenants

Las migraciones de tenants se ejecutarán automáticamente al crear un tenant.

### Verificar migración

En phpMyAdmin:
- Selecciona base de datos `aqua_crm`
- Deberías ver tablas como: users, domains, tenants, migrations, etc.

---

## 🔧 PASO 9: Compilar Assets

### Para desarrollo:

```cmd
npm run dev
```

Deja esta ventana abierta mientras desarrollas.

### Para producción:

```cmd
npm run build
```

---

## 🚀 PASO 10: Ejecutar la Aplicación

### Opción 1: Usando XAMPP (Recomendado)

1. **Asegurarse que el proyecto está en htdocs:**
   ```
   C:\xampp\htdocs\WaasCRM
   ```

2. **Configurar VirtualHost** (opcional, recomendado):

   Editar: `C:\xampp\apache\conf\extra\httpd-vhost.conf`

   Agregar al final:
   ```apache
   <VirtualHost *:80>
       DocumentRoot "C:/xampp/htdocs/WaasCRM/public"
       ServerName waascrm.local
       <Directory "C:/xampp/htdocs/WaasCRM/public">
           Options Indexes FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

3. **Editar archivo hosts:**

   Abrir como Administrador: `C:\Windows\System32\drivers\etc\hosts`

   Agregar:
   ```
   127.0.0.1  waascrm.local
   ```

4. **Reiniciar Apache en XAMPP**

5. **Acceder a:**
   ```
   http://waascrm.local
   ```
   o
   ```
   http://localhost/WaasCRM/public
   ```

### Opción 2: Usando servidor de desarrollo de PHP

```cmd
cd C:\xampp\htdocs\WaasCRM
php artisan serve
```

Luego accede a: http://localhost:8000

---

## ✅ VERIFICACIÓN

### Verificar que todo funciona:

1. **Verificar conexión a base de datos:**
   ```cmd
   php artisan tinker
   ```

   Luego en tinker:
   ```php
   DB::connection()->getPdo();
   ```

   Si no hay error, la conexión funciona. Sal con `exit`

2. **Verificar extensiones PHP:**
   ```cmd
   php -m | findstr mysql
   ```

   Deberías ver:
   ```
   mysqli
   pdo_mysql
   ```

3. **Verificar Laravel:**
   ```cmd
   php artisan --version
   ```

---

## 🐛 SOLUCIÓN DE PROBLEMAS COMUNES

### Error: "could not find driver"

**Solución:** La extensión PDO MySQL no está habilitada.
```
1. Revisa el PASO 3
2. En php.ini verifica:
   extension=pdo_mysql
   extension=mysqli
3. Reinicia Apache
```

### Error: "SQLSTATE[HY000] [2002] Connection refused"

**Solución:** MySQL no está ejecutándose.
```
1. Abre XAMPP Control Panel
2. Inicia MySQL
3. Si no inicia, consulta XAMPP_MYSQL_FIX.md
```

### Error: "SQLSTATE[42000]: Syntax error or access violation"

**Solución:** Probablemente un tipo de dato incompatible.
```
1. Revisa el error completo
2. Identifica la migración que falla
3. Cambia tipos específicos de PostgreSQL a MySQL:
   - jsonb → json
   - uuid → char(36)
   - timestamptz → timestamp
```

### Error: "Access denied for user 'root'@'localhost'"

**Solución:** Contraseña incorrecta.
```
1. Verifica DB_PASSWORD en .env
2. Por defecto en XAMPP es vacío (sin contraseña)
3. Si configuraste contraseña, ponla en .env
```

### Error de memoria en npm install

**Solución:**
```cmd
npm install --legacy-peer-deps
```

### Error: "Specified key was too long"

**Solución:** Problema de longitud de índice en MySQL.

Editar: `app/Providers/AppServiceProvider.php`

Agregar en el método `boot()`:
```php
use Illuminate\Support\Facades\Schema;

public function boot()
{
    Schema::defaultStringLength(191);
}
```

---

## ⚠️ DIFERENCIAS ENTRE MySQL Y PostgreSQL

Algunas características pueden comportarse diferente:

| Característica | PostgreSQL | MySQL |
|----------------|------------|-------|
| JSON | jsonb (binario) | json (texto) |
| UUID | Nativo | Requiere char(36) |
| Búsqueda | ILIKE | LIKE con LOWER() |
| Arrays | Soporte nativo | Requiere JSON |
| Transacciones | Más robusto | Depende del motor |

Si encuentras errores relacionados con estas diferencias, puede ser necesario adaptar modelos o consultas.

---

## 📝 AJUSTES RECOMENDADOS PARA PRODUCCIÓN

### my.ini de MySQL

Si vas a usar en producción, edita: `C:\xampp\mysql\bin\my.ini`

Ajusta estos valores:
```ini
max_connections = 151
key_buffer_size = 256M
max_allowed_packet = 64M
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
```

Reinicia MySQL después de cambios.

---

## 🎯 SIGUIENTE PASO

Una vez que todo esté funcionando:

1. Accede a la aplicación
2. Crea un usuario administrador
3. Configura los catálogos centrales
4. Crea tu primer tenant

¡Listo! Tu aplicación WaasCRM debería estar funcionando con MySQL.

---

## 💡 RECOMENDACIÓN FINAL

Si tienes problemas persistentes con MySQL o necesitas todas las características de la aplicación, considera cambiar a PostgreSQL usando la guía: `SETUP_POSTGRESQL.md`

PostgreSQL es más compatible con la aplicación y tiene mejor soporte para características avanzadas.
