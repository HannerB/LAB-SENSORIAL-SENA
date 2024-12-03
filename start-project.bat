@echo off
echo Iniciando el proyecto Laravel...

REM Verificar si PHP está en ejecución
tasklist /FI "IMAGENAME eq php.exe" 2>NUL | find /I /N "php.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo Deteniendo instancias previas de PHP...
    taskkill /F /IM php.exe
)

REM Verificar si Node está en ejecución
tasklist /FI "IMAGENAME eq node.exe" 2>NUL | find /I /N "node.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo Deteniendo instancias previas de Node...
    taskkill /F /IM node.exe
)

REM Iniciar servicios necesarios de XAMPP
echo Iniciando Apache y MySQL...
start /B "" "C:\xampp\apache_start.bat"
start /B "" "C:\xampp\mysql_start.bat"
timeout /t 5 /nobreak

REM Instalar dependencias si no existen
if not exist vendor (
    echo Instalando dependencias de Composer...
    call composer install
)

if not exist node_modules (
    echo Instalando dependencias de Node...
    call npm install
)

REM Verificar y crear archivo .env si no existe
if not exist .env (
    echo Creando archivo .env...
    copy .env.example .env
    php artisan key:generate
)

REM Ejecutar migraciones
echo Ejecutando migraciones de base de datos...
php artisan migrate

REM Iniciar el servidor de desarrollo y Vite
echo Iniciando servidor de desarrollo...
start /B cmd /c "php artisan serve"
echo Iniciando Vite...
start /B cmd /c "npm run dev"

echo.
echo Proyecto iniciado correctamente!
echo Servidor Laravel: http://localhost:8000
echo Servidor Vite: http://localhost:5173
echo.
echo Presiona Ctrl+C para detener los servicios...

REM Mantener el script en ejecución
:loop
timeout /t 1 /nobreak >nul
goto loop