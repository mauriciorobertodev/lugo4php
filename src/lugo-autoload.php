<?php

spl_autoload_register(function ($class) {
    if (strpos($class, 'Lugo\\') === 0) {
        $file = str_replace('/src', '', __DIR__) . '/output/' . str_replace('\\', '/', $class) . '.php';
        
		if (file_exists($file)) {
            require_once $file;
        }
    }

	if (strpos($class, 'GPBMetadata\\') === 0) {
        $file = str_replace('/src', '', __DIR__) . '/output/' . str_replace('\\', '/', $class) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
});
