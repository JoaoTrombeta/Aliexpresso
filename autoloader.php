<?php
    spl_autoload_register(function ($class) {
        // Prefixo do namespace do projeto
        $prefix = 'Aliexpresso\\';

        // Diretório base para os arquivos
        $base_dir = __DIR__ . '/';

        // Verifica se a classe usa o prefixo do namespace
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // Se não, passa para o próximo autoloader
            return;
        }

        // Obtém o nome relativo da classe (ex: Model\Produtos\CafeEmGraos)
        $relative_class = substr($class, $len);

        // Substitui os separadores de namespace por separadores de diretório
        $path = str_replace('\\', '/', $relative_class);

        // Separa o caminho do diretório do nome do arquivo da classe
        $last_slash_pos = strrpos($path, '/');
        if ($last_slash_pos === false) {
            // Se não houver subdiretório
            $directory_path = '';
            $class_file_name = $path;
        } else {
            $directory_path = substr($path, 0, $last_slash_pos);
            $class_file_name = substr($path, $last_slash_pos + 1);
        }
        
        // Constrói o caminho final, convertendo o diretório para minúsculas
        $file = $base_dir . strtolower($directory_path) . '/' . $class_file_name . '.php';

        // Se o arquivo existir, carrega-o
        if (file_exists($file)) {
            require $file;
        }
    });
?>