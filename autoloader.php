<?php
    spl_autoload_register(function ($class) {
        $prefix = 'Aliexpresso\\';
        $base_dir = __DIR__ . '/';

        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }

        $relative_class = substr($class, $len);
        
        $parts = explode('\\', $relative_class);
        $parts[0] = strtolower($parts[0]); 
        $relative_path = implode('/', $parts);

        $file = $base_dir . $relative_path . '.php';

        if (file_exists($file)) {
            require $file;
        }
    });
?>