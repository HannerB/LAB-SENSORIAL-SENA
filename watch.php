<?php

class ProjectWatcher {
    private $lastCheck;
    private $watchDirs = [
        'app',
        'config',
        'database',
        'resources',
        'routes'
    ];
    
    public function __construct() {
        $this->lastCheck = time();
        echo "Iniciando monitor de cambios...\n";
    }
    
    public function watch() {
        while (true) {
            if ($this->hasChanges()) {
                $this->restartServices();
            }
            sleep(2);
        }
    }
    
    private function hasChanges() {
        foreach ($this->watchDirs as $dir) {
            if ($this->checkDirectory($dir)) {
                return true;
            }
        }
        return false;
    }
    
    private function checkDirectory($dir) {
        if (!is_dir($dir)) return false;
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $mtime = $file->getMTime();
                if ($mtime > $this->lastCheck) {
                    $this->lastCheck = time();
                    echo "Cambios detectados en: " . $file->getPathname() . "\n";
                    return true;
                }
            }
        }
        
        return false;
    }
    
    private function restartServices() {
        echo "Reiniciando servicios...\n";
        
        // En Windows
        exec('taskkill /F /IM php.exe');
        sleep(2);
        
        // Reiniciar servidor Laravel
        pclose(popen('start /B php artisan serve', 'r'));
        
        echo "Servicios reiniciados.\n";
    }
}

$watcher = new ProjectWatcher();
$watcher->watch();