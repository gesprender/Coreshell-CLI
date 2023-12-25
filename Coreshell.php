<?php
namespace Coreshell;
use Coreshell\Commands\Make;
use Coreshell\Commands\Migrations;
use Coreshell\Library\AbstractConsoleLibrary;

abstract class Coreshell extends AbstractConsoleLibrary
{
    private $DB;
    public function init($dotenv, $DB)
    {
        $dotenv->load();
        $this->DB = $DB;
        # We validate if the command exists
        echo " [●] Validando el comando ...\n";
        self::validateArgs($this->DB);
    }

    private static function validateArgs($DB)
    {
        if (!isset($_SERVER['argv'][1])) {
            echo "Usa un comando.";
            die;
        }
        $comand = explode('::', $_SERVER['argv'][1]);

        switch ($comand[0]) {
            case 'Server':
            case 'server':
                echo self::colorText(" [√] ", self::GREEN) . "Encendiendo el servidor GesPrender-Core-Framework \n";
                echo self::colorText(" [√] ", self::GREEN) . "Listen in http://localhost:2024 \n";
                exec("php -S localhost:2024");
                break;
            case 'Make':
                # Posibles comandos Make
                switch ($comand[1]) {
                    case 'build':
                        Make::build();
                        break;
                    case 'project':
                        Make::project();
                        break;
                    default:
                        echo " [x] Comando no reconocido ...";
                        die;
                }
                break;
            case 'Migrations':
                # Posibles comandos Migrations
                switch ($comand[1]) {
                    case 'migrate':
                        Migrations::migrate($DB);
                        break;

                    default:
                        echo " [x] Comando no reconocido ...";
                        die;
                }
                break;
            default:
                echo " [x] Comando no reconocido ...";
                die;
        }
    }
}
