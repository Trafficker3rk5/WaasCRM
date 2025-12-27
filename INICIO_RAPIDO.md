# 🚀 GUÍA DE INICIO RÁPIDO - WaasCRM

¡Bienvenido! Esta guía te ayudará a poner en marcha WaasCRM en XAMPP.

---

## ⚡ DECISIÓN IMPORTANTE: ¿PostgreSQL o MySQL?

**WaasCRM está diseñado para PostgreSQL**, pero puede funcionar con MySQL.

### 🎯 ¿Qué debería elegir?

#### ✅ **USA POSTGRESQL SI:**
- Quieres la experiencia completa sin problemas
- Es tu primera vez configurando el proyecto
- No tienes experiencia adaptando aplicaciones Laravel
- Planeas usar en producción
- **RECOMENDADO** 👈

#### ⚠️ **USA MySQL SI:**
- Ya tienes MySQL en XAMPP funcionando
- No quieres instalar software adicional
- Tienes experiencia con Laravel y migraciones
- Es solo para pruebas rápidas
- Estás dispuesto a hacer ajustes si hay problemas

---

## 📁 ARCHIVOS DE AYUDA DISPONIBLES

Este repositorio incluye estas guías:

| Archivo | Descripción |
|---------|-------------|
| **`SETUP_POSTGRESQL.md`** | Guía completa para configurar con PostgreSQL ✅ |
| **`SETUP_MYSQL.md`** | Guía completa para configurar con MySQL en XAMPP |
| **`XAMPP_MYSQL_FIX.md`** | Soluciones si MySQL no arranca en XAMPP |
| **`INICIO_RAPIDO.md`** | Este archivo (inicio rápido) |

---

## 🏃 PASOS RÁPIDOS

### SI ELIGES POSTGRESQL (RECOMENDADO):

```
1. Lee: SETUP_POSTGRESQL.md
2. Instala PostgreSQL
3. Configura .env con pgsql
4. Ejecuta: composer install
5. Ejecuta: npm install
6. Ejecuta: php artisan migrate
7. ¡Listo!
```

### SI ELIGES MySQL:

```
1. Lee primero: XAMPP_MYSQL_FIX.md (si MySQL no arranca)
2. Lee: SETUP_MYSQL.md
3. Configura .env con mysql
4. Adapta migraciones (jsonb → json)
5. Ejecuta: composer install
6. Ejecuta: npm install
7. Ejecuta: php artisan migrate
8. ¡Listo!
```

---

## ❌ PROBLEMA ACTUAL: MySQL no arranca en XAMPP

Veo que tienes este error:
```
Error: MySQL shutdown unexpectedly.
This may be due to a blocked port...
```

### ⚡ SOLUCIÓN RÁPIDA:

1. **Abre CMD como Administrador**

2. **Ejecuta:**
   ```cmd
   netstat -ano | findstr :3306
   ```

3. **Si ves resultados:**
   - El puerto 3306 está ocupado
   - Otro MySQL está corriendo

4. **Para detener el servicio:**
   ```cmd
   net stop mysql
   net stop mysql80
   ```

5. **Reinicia MySQL en XAMPP**

### 📖 Para más soluciones:

Lee el archivo: **`XAMPP_MYSQL_FIX.md`**

Contiene 5 soluciones diferentes para este problema.

---

## 🔧 ESTRUCTURA DE CONFIGURACIÓN

### .env - Configuración Principal

**Para PostgreSQL:**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=aqua_crm
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña
```

**Para MySQL:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aqua_crm
DB_USERNAME=root
DB_PASSWORD=
```

---

## 📋 CHECKLIST ANTES DE INICIAR

Marca lo que ya tienes:

### Software Base:
- [ ] XAMPP instalado
- [ ] Apache funcionando en XAMPP
- [ ] MySQL funcionando en XAMPP (o PostgreSQL instalado)
- [ ] Composer instalado
- [ ] Node.js instalado

### Archivos del Proyecto:
- [ ] Archivo .env creado (copia de .env.example)
- [ ] .env configurado correctamente
- [ ] Base de datos creada (aqua_crm)

### Dependencias:
- [ ] `composer install` ejecutado
- [ ] `npm install` ejecutado
- [ ] `php artisan key:generate` ejecutado

### Configuración:
- [ ] Extensiones PHP habilitadas (pdo_pgsql o pdo_mysql)
- [ ] Apache reiniciado después de cambios en php.ini
- [ ] Migraciones ejecutadas sin errores

### Acceso:
- [ ] Puedes acceder a http://localhost/WaasCRM/public
- [ ] O configuraste un VirtualHost

---

## 🆘 NECESITO AYUDA

### Si MySQL no arranca:
→ Lee: `XAMPP_MYSQL_FIX.md`

### Si prefieres PostgreSQL:
→ Lee: `SETUP_POSTGRESQL.md`

### Si quieres usar MySQL:
→ Lee: `SETUP_MYSQL.md`

### Si todo falla:
1. Revisa los logs de error:
   - Apache: `C:\xampp\apache\logs\error.log`
   - MySQL: `C:\xampp\mysql\data\mysql_error.log`
   - Laravel: `storage\logs\laravel.log`

2. Busca el error específico en las guías

---

## 🎯 MI RECOMENDACIÓN

Basado en tu situación actual:

### OPCIÓN 1: Arreglar MySQL y Continuar (Más Rápido)
```
✅ Ventajas:
- No necesitas instalar nada nuevo
- Conoces XAMPP
- Más rápido si el problema es simple

❌ Desventajas:
- Puede haber problemas de compatibilidad
- Necesitas adaptar migraciones
- Menos soporte nativo de la app
```

**Pasos:**
1. Abre `XAMPP_MYSQL_FIX.md`
2. Aplica "Solución 1" (verificar puerto)
3. Continúa con `SETUP_MYSQL.md`

### OPCIÓN 2: Instalar PostgreSQL (Más Confiable)
```
✅ Ventajas:
- Cero problemas de compatibilidad
- Aplicación diseñada para esto
- Mejor para producción

❌ Desventajas:
- Necesitas instalar PostgreSQL
- Aprender nueva herramienta (pgAdmin)
```

**Pasos:**
1. Abre `SETUP_POSTGRESQL.md`
2. Sigue paso a paso
3. Listo

---

## 💡 CONSEJOS FINALES

1. **Lee las guías completamente antes de empezar**
   - No saltes pasos
   - Entiende qué hace cada comando

2. **Haz backup de tu base de datos antes de migraciones**
   - Exporta desde phpMyAdmin o pgAdmin

3. **Revisa los logs cuando algo falle**
   - Los errores suelen ser claros
   - Busca la última línea del error

4. **No mezcles PostgreSQL y MySQL**
   - Elige uno y quédate con él
   - Cambiar después requiere rehacer migraciones

5. **Ten paciencia en la primera instalación**
   - Es normal que tome tiempo
   - Una vez configurado, funciona sin problemas

---

## 🚀 ¿LISTO PARA EMPEZAR?

### Decisión tomada: PostgreSQL
→ Abre y sigue: **`SETUP_POSTGRESQL.md`**

### Decisión tomada: MySQL
→ Primero arregla MySQL con: **`XAMPP_MYSQL_FIX.md`**
→ Luego sigue: **`SETUP_MYSQL.md`**

---

## 📞 ERRORES COMUNES Y SOLUCIONES RÁPIDAS

### "could not find driver"
- Extensiones PHP no habilitadas
- Revisa php.ini y reinicia Apache

### "Connection refused"
- Base de datos no está corriendo
- Inicia MySQL o PostgreSQL

### "Access denied"
- Contraseña incorrecta en .env
- Verifica DB_PASSWORD

### "Syntax error" en migraciones
- Tipo de dato incompatible con MySQL
- Cambia jsonb a json en migraciones

### "Port already in use"
- Otro servicio usa el puerto
- Revisa con: netstat -ano | findstr :3306

---

¡Éxito con tu instalación! 🎉
