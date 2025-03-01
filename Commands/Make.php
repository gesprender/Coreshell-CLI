<?php

namespace Coreshell\Commands;

use Coreshell\Library\AbstractConsoleLibrary;

class Make extends AbstractConsoleLibrary
{

    public static function project()
    {
        $estructura = [
            "Project" => [
                "src" => [
                    "TestModule" => [
                        "Test.php" => "<?php\n// #GesPrender Core Framework\n",
                        "Migrations.sql" => "",
                        "TestRepository.php" => "<?php\n// #GesPrender Core Framework\n",
                        "TestRequestController.php" => "<?php\n// #GesPrender Core Framework\n"
                    ],
                ],
                "theme" => [
                    "default" => ["App.jsx" => "//GesPrender Core Framework\n"],
                    "dist" => []
                ],
                "Endpoints.php" => "<?php\n// #GesPrender Core Framework\n"
            ]
        ];
        echo self::colorText(" [●] Creando estructura...\n", self::GREEN);
        self::createStructure('.', $estructura);
        echo self::colorText(" [●] Estructura creada ...\n", self::GREEN);
    }

    private static function createStructure($path, $estructura)
    {
        foreach ($estructura as $key => $value) {
            $currentPath = $path . '/' . $key;
            if (!is_array($value) || (is_numeric($key) && is_string($value))) {
                // Es un archivo
                $filePath = is_numeric($key) ? $path . '/' . $value : $currentPath;
                $contenido = is_numeric($key) ? '' : $value;
                // $pathFileCopy = "./config/Commands/Models/$key";
                // $pathFileCopy = str_replace('.php', '.txt', $pathFileCopy);

                // if (file_exists($pathFileCopy)) {
                //     $contenido = file_get_contents($pathFileCopy);
                // }

                file_put_contents($filePath, $contenido); // Crea un archivo con contenido
            } else {
                // Es un directorio
                if (!file_exists($currentPath)) {
                    mkdir($currentPath, 0777, true);
                }
                self::createStructure($currentPath, $value); // Recursividad para subdirectorios
            }
        }
    }

    public static function module($moduleName)
    {
        // first letter of module in to upper case
        $moduleName = ucfirst($moduleName);
        // Create folders
        mkdir("Backoffice/src/Modules/$moduleName", );
        mkdir("Backoffice/src/Modules/$moduleName/Application", 777);
        mkdir("Backoffice/src/Modules/$moduleName/Design", 777);
        mkdir("Backoffice/src/Modules/$moduleName/Design/CoreHooks", 777);
        mkdir("Backoffice/src/Modules/$moduleName/Design/Components", 777);
        mkdir("Backoffice/src/Modules/$moduleName/Design/Store", 777);
        mkdir("Backoffice/src/Modules/$moduleName/Domain", 777);
        mkdir("Backoffice/src/Modules/$moduleName/Infrastructure", 777);
        mkdir("Backoffice/src/Modules/$moduleName/Infrastructure/Migrations", 777);

        $estructura = [
            "Backoffice" => [
                "src" => [
                    "Modules" => [
                        $moduleName => [
                            "Application" => [
                                "GetById.php" => SetNameSpace($moduleName, "Application") . setContentApplication("GetById", $moduleName, 'GET'),
                                "GetAll.php" => SetNameSpace($moduleName, "Application") . setContentApplication("GetAll", $moduleName, 'GET'),
                                "SetElement.php" => SetNameSpace($moduleName, "Application") . setContentApplication("SetElement", $moduleName, 'POST'),
                                "RemoveElement.php" => SetNameSpace($moduleName, "Application") . setContentApplication("RemoveElement", $moduleName, 'DELETE'),
                                "UpdateElement.php" => SetNameSpace($moduleName, "Application") . setContentApplication("UpdateElement", $moduleName, 'PUT'),
                            ],
                            "Design" => [
                                "Store" => [
                                    "serviceStore$moduleName.js" => generateServiceZustand($moduleName),
                                ],
                                "Sidebar.jsx" => sidebarJSX($moduleName),
                                "routes.jsx" => generateRoute($moduleName),
                                "$moduleName.jsx" => moduleJSX($moduleName),
                                "$moduleName.scss" => "",
                            ],
                            "Domain" => [
                                ".gitignore" => "",
                            ],
                            "Infrastructure" => [
                                "Migrations" => [
                                    "Install.sql" => "INSERT INTO `modules` (`id`,`module_dependency`,`module_name`,`name`,`description`,`premium`,`count`,`is_website`,`active`, `view_in_front`) VALUES (NULL,NULL,'$moduleName','$moduleName','$moduleName','0','0','0','1', '1');",
                                    "Uninstall.sql" => "",
                                ],
                                $moduleName."Repository.php" => SetNameSpace($moduleName, "Infrastructure").setRepository($moduleName),
                            ],
                        ],
                    ]
                ]
            ]
        ];
        echo self::colorText(" [●] Creando estructura...\n", self::GREEN);
        self::createStructure('.', $estructura);
        echo self::colorText(" [●] Estructura creada ...\n", self::GREEN);
    }
}



function SetNameSpace($module, $end) {
    return "<?php #GesPrender Core Framework\ndeclare(strict_types=1);\nnamespace Backoffice\Modules\\" . $module ."\\".$end . ";";
}

function setContentApplication($file, $module, $method = 'GET') {
    return "\n
use Backoffice\Modules\\".$module."\Infrastructure\\". $module ."Repository;
use Core\Services\JsonResponse;

final class $file extends ".$module."Repository
{
    public function __construct() {
        ".'$this->run();'."
    }

    # [Route('/".strtolower($module)."/".strtolower($file)."', name: '$file', methods: '$method')]
    # useMiddleware
    public function run(): JsonResponse
    {
        try {
            #code :)

            return new JsonResponse([
                'status' => true,
                'message' => 'Successfully',
                'data' => []
            ], 200);
        } catch (\Throwable \$th) {
            return self::ExceptionResponse(\$th, '$file:action');
        }
    }
}";
}

function setRepository($module) {
    return "\n
use Core\Contracts\RepositoryAbstract;

class $module"."Repository extends RepositoryAbstract {

    
}";
}

function moduleJSX($module) {
    return "import React from 'react'

export default function $module() {
  return (
    <div>$module</div>
  )
}";
}

function sidebarJSX($module) {

    return "import React from 'react'
import { useNavigate } from 'react-router-dom';

const Sidebar = () => {
  const navigate = useNavigate();

  return (
    <a onClick={(e) => { navigate('/$module') }} data-bs-toggle='tooltip' className='mt-2 cursor-pointer'>
      <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' viewBox='0 0 20 20'>
        <path d='M3.112 3.645A1.5 1.5 0 0 1 4.605 2H7a.5.5 0 0 1 .5.5v.382c0 .696-.497 1.182-.872 1.469a.5.5 0 0 0-.115.118l-.012.025L6.5 4.5v.003l.003.01q.005.015.036.053a.9.9 0 0 0 .27.194C7.09 4.9 7.51 5 8 5c.492 0 .912-.1 1.19-.24a.9.9 0 0 0 .271-.194.2.2 0 0 0 .039-.063v-.009l-.012-.025a.5.5 0 0 0-.115-.118c-.375-.287-.872-.773-.872-1.469V2.5A.5.5 0 0 1 9 2h2.395a1.5 1.5 0 0 1 1.493 1.645L12.645 6.5h.237c.195 0 .42-.147.675-.48.21-.274.528-.52.943-.52.568 0 .947.447 1.154.862C15.877 6.807 16 7.387 16 8s-.123 1.193-.346 1.638c-.207.415-.586.862-1.154.862-.415 0-.733-.246-.943-.52-.255-.333-.48-.48-.675-.48h-.237l.243 2.855A1.5 1.5 0 0 1 11.395 14H9a.5.5 0 0 1-.5-.5v-.382c0-.696.497-1.182.872-1.469a.5.5 0 0 0 .115-.118l.012-.025.001-.006v-.003a.2.2 0 0 0-.039-.064.9.9 0 0 0-.27-.193C8.91 11.1 8.49 11 8 11s-.912.1-1.19.24a.9.9 0 0 0-.271.194.2.2 0 0 0-.039.063v.003l.001.006.012.025c.016.027.05.068.115.118.375.287.872.773.872 1.469v.382a.5.5 0 0 1-.5.5H4.605a1.5 1.5 0 0 1-1.493-1.645L3.356 9.5h-.238c-.195 0-.42.147-.675.48-.21.274-.528.52-.943.52-.568 0-.947-.447-1.154-.862C.123 9.193 0 8.613 0 8s.123-1.193.346-1.638C.553 5.947.932 5.5 1.5 5.5c.415 0 .733.246.943.52.255.333.48.48.675.48h.238zM4.605 3a.5.5 0 0 0-.498.55l.001.007.29 3.4A.5.5 0 0 1 3.9 7.5h-.782c-.696 0-1.182-.497-1.469-.872a.5.5 0 0 0-.118-.115l-.025-.012L1.5 6.5h-.003a.2.2 0 0 0-.064.039.9.9 0 0 0-.193.27C1.1 7.09 1 7.51 1 8s.1.912.24 1.19c.07.14.14.225.194.271a.2.2 0 0 0 .063.039H1.5l.006-.001.025-.012a.5.5 0 0 0 .118-.115c.287-.375.773-.872 1.469-.872H3.9a.5.5 0 0 1 .498.542l-.29 3.408a.5.5 0 0 0 .497.55h1.878c-.048-.166-.195-.352-.463-.557-.274-.21-.52-.528-.52-.943 0-.568.447-.947.862-1.154C6.807 10.123 7.387 10 8 10s1.193.123 1.638.346c.415.207.862.586.862 1.154 0 .415-.246.733-.52.943-.268.205-.415.39-.463.557h1.878a.5.5 0 0 0 .498-.55l-.001-.007-.29-3.4A.5.5 0 0 1 12.1 8.5h.782c.696 0 1.182.497 1.469.872.05.065.091.099.118.115l.025.012.006.001h.003a.2.2 0 0 0 .064-.039.9.9 0 0 0 .193-.27c.14-.28.24-.7.24-1.191s-.1-.912-.24-1.19a.9.9 0 0 0-.194-.271.2.2 0 0 0-.063-.039H14.5l-.006.001-.025.012a.5.5 0 0 0-.118.115c-.287.375-.773.872-1.469.872H12.1a.5.5 0 0 1-.498-.543l.29-3.407a.5.5 0 0 0-.497-.55H9.517c.048.166.195.352.463.557.274.21.52.528.52.943 0 .568-.447.947-.862 1.154C9.193 5.877 8.613 6 8 6s-1.193-.123-1.638-.346C5.947 5.447 5.5 5.068 5.5 4.5c0-.415.246-.733.52-.943.268-.205.415-.39.463-.557z'/>
      </svg>
      <div className='name_module'>$module</div>
    </a>
  )
}

export default {
  Role: 'default',
  Permission: '$module',
  Module: '$module',
  Component: Sidebar,
  Position: 'Up'
};";
}

function generateRoute($module) {
    return "import { Layout } from '../../../Theme/Layout/Layout';
import $module from './$module';

const PrincipalComponent = <Layout children={<$module />} />

const routes = [
  {
    path: '$module',
    element: PrincipalComponent,
  },
];

export default routes;";
}

function generateServiceZustand($module) {
    return "import { create } from 'zustand';
import { BackendRequest } from '../../../Connector';

export const serviceStore$module = create((set, get) => ({
  response: {},
  
  getAll: async () => {
    const request = await BackendRequest.Get('/$module/getall');

    if(request?.data){
      set({ response: request.data });
    }
  },

  
  
}));

// const data = serviceStore$module((state) => state.response)
// const { getAll } = serviceStore$module();";
}