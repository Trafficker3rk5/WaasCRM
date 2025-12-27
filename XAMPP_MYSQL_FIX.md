# 🔧 SOLUCIÓN: Error MySQL en XAMPP

## Problema
```
Error: MySQL shutdown unexpectedly.
This may be due to a blocked port, missing dependencies,
improper privileges, a crash, or a shutdown by another method.
```

## SOLUCIONES EN ORDEN DE EFECTIVIDAD

### ✅ SOLUCIÓN 1: Verificar y Cambiar Puerto (Más Común)

El puerto 3306 probablemente está siendo usado por otro servicio.

**Pasos:**

1. **Verificar qué está usando el puerto 3306:**
   - Abre CMD como Administrador
   - Ejecuta:
     ```cmd
     netstat -ano | findstr :3306
     ```
   - Si ves resultados, el puerto está ocupado

2. **Detener el servicio MySQL de Windows:**
   - Presiona `Windows + R`
   - Escribe: `services.msc`
   - Busca servicios con nombres como:
     - `MySQL`
     - `MySQL80`
     - `MariaDB`
   - Click derecho → Detener
   - Click derecho → Propiedades → Tipo de inicio: Manual o Deshabilitado

3. **O cambiar el puerto de XAMPP:**
   - Abre XAMPP Control Panel
   - Click en "Config" botón de MySQL
   - Selecciona "my.ini"
   - Busca la línea: `port=3306`
   - Cámbiala a: `port=3307`
   - Guarda el archivo
   - Reinicia XAMPP

---

### ✅ SOLUCIÓN 2: Reparar Archivos Corruptos de MySQL

Si MySQL se cerró incorrectamente, los archivos pueden estar corruptos.

**Pasos:**

1. **Hacer backup de la carpeta mysql:**
   - Ve a: `C:\xampp\mysql\data`
   - Copia toda la carpeta `data` a un lugar seguro

2. **Eliminar archivos de log problemáticos:**
   - En `C:\xampp\mysql\data\` elimina estos archivos:
     - `ibdata1`
     - `ib_logfile0`
     - `ib_logfile1`
     - `aria_log_control`
     - `aria_log.00000001`

3. **Restaurar desde backup:**
   - Ve a: `C:\xampp\mysql\backup`
   - Copia estos archivos a `C:\xampp\mysql\data\`:
     - `ibdata1`
     - `ib_logfile0`
     - `ib_logfile1`

4. **Reintentar iniciar MySQL en XAMPP**

---

### ✅ SOLUCIÓN 3: Reinstalar MySQL en XAMPP

**Pasos:**

1. **Hacer backup de bases de datos:**
   - Si tienes datos importantes, exporta todo desde phpMyAdmin

2. **Detener todos los servicios de XAMPP**

3. **Eliminar carpeta MySQL:**
   - Ve a: `C:\xampp\mysql`
   - Renombra a: `mysql_old`

4. **Reinstalar XAMPP o MySQL:**
   - Descarga XAMPP nuevamente
   - Ejecuta el instalador
   - Selecciona solo "MySQL" para actualizar

5. **Restaurar bases de datos si es necesario**

---

### ✅ SOLUCIÓN 4: Ejecutar XAMPP como Administrador

**Pasos:**

1. Cierra XAMPP completamente
2. Click derecho en `xampp-control.exe`
3. Selecciona "Ejecutar como administrador"
4. Intenta iniciar MySQL

---

### ✅ SOLUCIÓN 5: Verificar Antivirus/Firewall

A veces el antivirus bloquea MySQL.

**Pasos:**

1. **Agregar excepción en el antivirus:**
   - Agrega estas carpetas a la lista de exclusiones:
     - `C:\xampp\mysql\`
     - `C:\xampp\apache\`

2. **Agregar excepción en el Firewall de Windows:**
   - Panel de Control → Firewall de Windows
   - Configuración avanzada → Reglas de entrada
   - Nueva regla → Puerto
   - TCP → Puerto 3306
   - Permitir conexión

---

## 🔍 VER LOGS DE ERROR

Para identificar el problema exacto:

1. **Abrir log de errores de MySQL:**
   - En XAMPP Control Panel
   - Click en "Logs" botón de MySQL
   - Selecciona el archivo de error
   - Lee el último error

2. **Ubicación del archivo de log:**
   ```
   C:\xampp\mysql\data\mysql_error.log
   ```

3. **Errores comunes y soluciones:**

   **Error:** `Can't start server: Bind on TCP/IP port: Address already in use`
   - **Solución:** Puerto ocupado → Usar Solución 1

   **Error:** `InnoDB: Database page corruption`
   - **Solución:** Archivos corruptos → Usar Solución 2

   **Error:** `Access denied`
   - **Solución:** Permisos → Ejecutar como Administrador (Solución 4)

---

## ⚡ SOLUCIÓN RÁPIDA (Más Efectiva)

Si nada funciona, esta es la solución más rápida:

```bash
# 1. Para todos los servicios de XAMPP

# 2. Abre CMD como Administrador y ejecuta:
net stop mysql
taskkill /F /IM mysqld.exe

# 3. Renombra la carpeta data actual:
# C:\xampp\mysql\data → C:\xampp\mysql\data_old

# 4. Copia la carpeta backup:
# C:\xampp\mysql\backup → C:\xampp\mysql\data

# 5. Copia las bases de datos importantes:
# De C:\xampp\mysql\data_old\tu_base_de_datos
# A C:\xampp\mysql\data\tu_base_de_datos

# 6. Inicia MySQL en XAMPP
```

---

## 📝 DESPUÉS DE SOLUCIONAR MySQL

Si vas a usar MySQL en lugar de PostgreSQL para WaasCRM, necesitas:

### 1. Instalar extensión PDO MySQL en PHP
Verifica en `php.ini` que esté habilitado:
```ini
extension=pdo_mysql
```

### 2. Cambiar configuración de base de datos
En tu archivo `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aqua_crm
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Crear la base de datos
En phpMyAdmin:
- Crear base de datos: `aqua_crm`
- Cotejamiento: `utf8mb4_unicode_ci`

### 4. Ejecutar migraciones
```bash
php artisan migrate
```

---

## 🆘 SI NADA FUNCIONA

1. **Desinstala XAMPP completamente:**
   - Panel de Control → Desinstalar programas
   - Elimina carpeta `C:\xampp` manualmente

2. **Instala versión más reciente de XAMPP:**
   - https://www.apachefriends.org/download.html

3. **O usa alternativa:**
   - **Laragon:** https://laragon.org/ (más moderno y fácil)
   - **WAMP:** https://www.wampserver.com/
