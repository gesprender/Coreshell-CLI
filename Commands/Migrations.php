<?php

namespace Coreshell\Commands;

use Coreshell\Library\AbstractConsoleLibrary;

class Migrations extends AbstractConsoleLibrary
{

    public static function migrate($DB)
    {
        # Load migrations if backend client exist
        $loadFolderMigrations = 'Project/backend/Endpoints.php';
        if (file_exists($loadFolderMigrations)) {
            echo " [●] Buscando migraciones del backend del cliente \n";
            $rutaDirectorio = 'Project/backend/Modules'; // Ruta del directorio principal 'Modules'
            $arrayQueries = [];
            self::buscarArchivoMigrations($rutaDirectorio, $arrayQueries); // Llamar a la función para buscar el archivo 'Migrations.sql' dentro del directorio
            if (!$arrayQueries) {
                echo " [x] No se encontraron migraciones \n";
            } else {
                echo " [●] Ejecutando migraciones \n";
                foreach ($arrayQueries as $keyPath => $queries) {
                    echo "      [►] Ejecutando migraciones de $keyPath \n";
                    foreach ($queries as $query) {
                        if (!$query || empty($query)) continue;

                        $status = (bool)$DB::query(trim($query));
                        if ($status) {
                            echo "          [√] " . substr(trim(str_replace(array("\r", "\n"), '', $query)), 0, 70) . " ... \n";
                        } else {
                            echo "          [x] " . substr(trim(str_replace(array("\r", "\n"), '', $query)), 0, 70) . " ... \n";
                        }
                    }
                }
            }
        }

        # Load backoffice migrations
        echo " [●] Ejecutando migraciones de BackOffice \n";
        $rutaDirectorio = 'Backoffice/src/Migrations'; // Ruta del directorio principal 'Modules'
        $arrayQueries = [];
        self::buscarArchivoMigrations($rutaDirectorio, $arrayQueries); // Llamar a la función para buscar el archivo 'Migrations.sql' dentro del directorio
        if (!$arrayQueries) {
            echo " [x] No se encontraron migraciones \n";
        } else {
            echo " [●] Ejecutando migraciones \n";
            foreach ($arrayQueries as $keyPath => $queries) {
                echo "      [►] Ejecutando migraciones de $keyPath \n";
                foreach ($queries as $query) {
                    if (!$query || empty($query)) continue;

                    $status = (bool)$DB::query(trim($query));
                    if ($status) {
                        echo "          [√] " . substr(trim(str_replace(array("\r", "\n"), '', $query)), 0, 70) . " ... \n";
                    } else {
                        echo "          [x] " . substr(trim(str_replace(array("\r", "\n"), '', $query)), 0, 70) . " ... \n";
                    }
                }
            }
        }
    }

    public static function buscarArchivoMigrations($directorio, array &$arrayQueries)
    {
        // Verificar si el directorio existe y se puede abrir
        if (is_dir($directorio) && $gestor = opendir($directorio)) {
            while (false !== ($elemento = readdir($gestor))) {
                // Ignorar los directorios . y ..
                // var_dump('entra1');die;
                if ($elemento != "." && $elemento != "..") {
                    if (is_dir($directorio . DIRECTORY_SEPARATOR . $elemento) && $elemento != 'themes') {
                        // Si es un directorio, realizar la búsqueda recursiva
                        self::buscarArchivoMigrations($directorio . DIRECTORY_SEPARATOR . $elemento, $arrayQueries);
                    } elseif ($elemento == 'Migrations.sql') {
                        // Si se encuentra el archivo 'Migrations.sql', imprimir la ruta
                        $MigrationSqlFolder["$directorio"] = self::sqlObtein(file_get_contents($directorio . DIRECTORY_SEPARATOR . $elemento));
                        $arrayQueries = array_merge($arrayQueries, $MigrationSqlFolder);
                    }
                }
            }
            closedir($gestor);
        }
    }

    private static function sqlObtein(string $string)
    {
        return explode(";", $string);
    }
}
