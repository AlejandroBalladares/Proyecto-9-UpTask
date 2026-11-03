<?php

namespace Controllers;

use MVC\Router;

class LoginController{
    public static function login(Router $router){
        if($_SERVER['REQUEST_METHOD'] === '´POST'){

            }
        $router->render('auth/login',[
            'titulo' => 'Iniciar sesion',
        ]);
    }
    
    public static function logout(){
        
    }

    public static function crear(Router $router){
        
        if($_SERVER['REQUEST_METHOD'] === '´POST'){

            }
        $router->render('auth/crear',[
            'titulo' => 'Crea tu cuenta en UpTask sesion',
        ]);

    }

    public static function olvide(Router $router){
        
        if($_SERVER['REQUEST_METHOD'] === '´POST'){

            }
        $router->render('auth/olvide',[
            'titulo' => 'Olvidé mi password',
        ]);
    }
    
    public static function restablecer(Router $router){
        
        if($_SERVER['REQUEST_METHOD'] === '´POST'){

            }
        $router->render('auth/restablecer',[
            'titulo' => 'Restablecer mi password',
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje',[
            'titulo' => 'Cuenta creada exitosamente',
        ]);
    }

    public static function confirmar(Router $router){
        $router->render('auth/confirmar',[
            'titulo' => 'Confirma tu cuenta UpTask',
        ]);
    }
}