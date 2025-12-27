# 🐘 CONFIGURACIÓN: WaasCRM con PostgreSQL (RECOMENDADO)

La aplicación WaasCRM está diseñada para PostgreSQL. Esta es la configuración recomendada.

---

## 📋 REQUISITOS PREVIOS

- XAMPP instalado (para Apache y PHP)
- PostgreSQL (lo instalaremos)

---

## 🔧 PASO 1: Instalar PostgreSQL

### Descargar e Instalar

1. **Descarga PostgreSQL:**
   - Ve a: https://www.postgresql.org/download/windows/
   - Descarga la versión más reciente (16.x o superior)
   - O descarga directamente: https://www.enterprisedb.com/downloads/postgres-postgresql-downloads

2. **Ejecutar el instalador:**
   - Ejecuta el archivo `.exe` descargado
   - Sigue el asistente de instalación

3. **Configuración durante la instalación:**
   ```
   Puerto: 5432 (dejar por defecto)
   Superusuario: postgres (dejar por defecto)
   Contraseña: [ELIGE UNA CONTRASEÑA SEGURA Y ANÓTALA]
   Locale: Spanish, Spain (o tu preferencia)
   ```

4. **Componentes a instalar:**
   - ✅ PostgreSQL Server
   - ✅ pgAdmin 4 (interfaz gráfica)
   - ✅ Stack Builder (opcional)
   - ✅ Command Line Tools

5. **Finalizar instalación**

---

## 🔧 PASO 2: Habilitar Extensión PDO PostgreSQL en PHP

### Editar php.ini de XAMPP

1. **Abrir XAMPP Control Panel**

2. **Click en "Config" del módulo Apache**

3. **Seleccionar "PHP (php.ini)"**

4. **Buscar estas líneas** (Ctrl+F):
   ```ini
   ;extension=pdo_pgsql
   ;extension=pgsql
   ```

5. **Quitar el punto y coma (;) para habilitarlas:**
   ```ini
   extension=pdo_pgsql
   extension=pgsql
   ```

6. **Guardar el archivo**

7. **Reiniciar Apache en XAMPP**

---

## 🔧 PASO 3: Crear Base de Datos

### Opción A: Usando pgAdmin 4 (Interfaz Gráfica)

1. **Abrir pgAdmin 4:**
   - Busca "pgAdmin 4" en el menú de Windows
   - Ingresa la contraseña maestra (la que configuraste)

2. **Conectar al servidor:**
   - Servers → PostgreSQL 16 (o tu versión)
   - Ingresa la contraseña del usuario postgres

3. **Crear base de datos:**
   - Click derecho en "Databases"
   - Create → Database
   - Nombre: `aqua_crm`
   - Owner: `postgres`
   - Encoding: `UTF8`
   - Click "Save"

### Opción B: Usando línea de comandos

1. **Abrir CMD como Administrador**

2. **Conectar a PostgreSQL:**
   ```cmd
   cd C:\Program Files\PostgreSQL\16\bin
   psql -U postgres
   ```

3. **Ingresa la contraseña cuando te la pida**

4. **Crear base de datos:**
   ```sql
   CREATE DATABASE aqua_crm WITH ENCODING 'UTF8';
   ```

5. **Verificar:**
   ```sql
   \l
   ```
   (Deberías ver `aqua_crm` en la lista)

6. **Salir:**
   ```sql
   \q
   ```

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
   - Configura así:

   ```env
   APP_NAME=WaasCRM
   APP_ENV=local
   APP_KEY=
   APP_DEBUG=true
   APP_URL=http://localhost

   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=aqua_crm
   DB_USERNAME=postgres
   DB_PASSWORD=tu_contraseña_de_postgres
   ```

4. **Configurar reCAPTCHA** (si tienes las claves):
   ```env
   RECAPTCHA_SITE_KEY=tu_site_key
   RECAPTCHA_SECRET_KEY=tu_secret_key
   ```

---

## 🔧 PASO 5: Instalar Dependencias

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

## 🔧 PASO 6: Configurar Laravel

### Generar clave de aplicación

```cmd
php artisan key:generate
```

### Publicar configuración de Tenancy

```cmd
php artisan vendor:publish --provider="Stancl\Tenancy\TenancyServiceProvider"
```

---

## 🔧 PASO 7: Ejecutar Migraciones

### Migrar base de datos central

```cmd
php artisan migrate
```

Si te pregunta "Do you really wish to run this command?", escribe `yes` y presiona Enter.

### Verificar migración

En pgAdmin 4:
- Expande Databases → aqua_crm → Schemas → public → Tables
- Deberías ver tablas como: users, domains, tenants, etc.

---

## 🔧 PASO 8: Compilar Assets

### Para desarrollo:

```cmd
npm run dev
```

### Para producción:

```cmd
npm run build
```

---

## 🚀 PASO 9: Ejecutar la Aplicación

### Opción 1: Usando XAMPP

1. **Mover proyecto a htdocs** (si no está ahí):
   ```
   C:\xampp\htdocs\WaasCRM
   ```

2. **Configurar VirtualHost** (opcional, recomendado):

   Editar: `C:\xampp\apache\conf\extra\httpd-vhost.conf`

   Agregar:
   ```apache
   <VirtualHost *:80>
       DocumentRoot "C:/xampp/htdocs/WaasCRM/public"
       ServerName waascrm.local
       <Directory "C:/xampp/htdocs/WaasCRM/public">
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
   php -m | findstr pgsql
   ```

   Deberías ver:
   ```
   pdo_pgsql
   pgsql
   ```

3. **Verificar Laravel:**
   ```cmd
   php artisan --version
   ```

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### Error: "could not find driver"

**Solución:** La extensión PDO PostgreSQL no está habilitada.
- Revisa el PASO 2
- Verifica que quitaste el `;` de las líneas
- Reinicia Apache

### Error: "SQLSTATE[08006] Connection refused"

**Solución:** PostgreSQL no está ejecutándose.
- Abre services.msc (Windows + R)
- Busca "postgresql-x64-16" (o tu versión)
- Click derecho → Iniciar
- Tipo de inicio → Automático

### Error: "password authentication failed"

**Solución:** Contraseña incorrecta en .env
- Verifica DB_PASSWORD en .env
- Asegúrate que es la misma que configuraste al instalar PostgreSQL

### Error de memoria en npm install

**Solución:**
```cmd
npm install --legacy-peer-deps
```

---

## 🎯 SIGUIENTE PASO

Una vez que todo esté funcionando:

1. Crea un usuario administrador
2. Configura los catálogos centrales
3. Crea tu primer tenant

¡Listo! Tu aplicación WaasCRM debería estar funcionando con PostgreSQL.
