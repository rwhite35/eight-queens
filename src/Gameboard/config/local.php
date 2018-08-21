<?php
namespace Gameboard;

class Module
{
    public $configs;
    
    public function getConfig()
    {
        $config = [
            'router' => [
                'routes' => [
                    'board' => [
                        'route' => "/board",
                        'defaults' => [
                            'model'         => "Gameboard/Model/Board",
                            'view'          => "",
                            'controller'    => "Gameboard/BoardController"
                        ],
                    ],
                    'validate' => [
                        'route' => "/validate",
                        'defaults'  => [
                            'model'         => "Gameboard/Model/Validate",
                        ],
                    ],
                    'solution' => [
                        'route' => "/solution",
                        'defaults' => [
                            'view'          => "Gameboard/Solution",
                            
                        ],
                    ],
                    'calcdiagonals' => [
                        'route' => "/calcdiagonals",
                        'defaults' => [
                            'interface'     => "Gameboard/CalcDiagonalsInterface",
                            'controller'    => "Gameboard/CalcDiagonals"
                        ],
                    ],
                ],
            ],
            
            'basePath' => __DIR__,
            
        ];
        
        return $config;
    }
}
?>