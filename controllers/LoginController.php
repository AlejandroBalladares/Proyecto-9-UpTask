<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    public static function login(Router $router){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            }
        $router->render('auth/login',[
            'titulo' => 'Iniciar sesion',
        ]);
    }
    
    public static function logout(){
        
    }

    public static function crear(Router $router){
        $alertas = [];
        $usuario = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $usuario->sincronizar($_POST);
                $alertas = $usuario->validarNuevaCuenta();
                if(empty($alertas)){
                    $existeUsuario = Usuario::where('email', $usuario->email);
                    if($existeUsuario){
                        Usuario::setAlerta('error', 'El usuario ya está registrado');
                        $alertas = Usuario::getAlertas();
                    }
                    else{
                        $usuario->hashPassword();
                        unset($usuario->password2);
                        $usuario->generarToken();
                        //debuguear($usuario);

                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        //debuguear($email);
                        $email->enviarConfirmacion();

                        $resultado = $usuario->guardar();
                        if($resultado){
                            header('Location:/mensaje');
                        }
                    }
                }
            }
        $router->render('auth/crear',[
            'titulo' => 'Crea tu cuenta en UpTask sesion',
            'usuario'=> $usuario,
            'alertas'=> $alertas,
        ]);

    }

    public static function olvide(Router $router){
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::where('email', $usuario->email);
                /** @var Usuario $usuario */
                if($usuario && $usuario->confirmado){
                    $usuario->generarToken();
                    unset($usuario->password2);

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu mail');
                    
                }
                else{
                    Usuario::setAlerta('error', 'El usuario no existe o no está validado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide',[
            'titulo' => 'Olvidé mi password',
            'alertas'=> $alertas,
        ]);
    }
    
    public static function restablecer(Router $router){
        $mostrar = true;
        $token = s($_GET['token']);
        if(!$token){
            header('Location:/');
        }
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
            $mostrar = false;
        }
        /** @var Usuario $usuario */
       

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $usuario->sincronizar($_POST);
                $alertas = $usuario->validarPassword();
                if(empty($alertas)){
                    $usuario->hashPassword();
                    unset($usuario->password2);
                    $usuario->token = null;
                    $resultado = $usuario->guardar();
                    if($resultado){
                        header('Location:/');
                    }
                }
            }
        $alertas = Usuario::getAlertas();
        $router->render('auth/restablecer',[
            'titulo' => 'Restablecer mi password',
            'alertas'=> $alertas,
            'mostrar'=>$mostrar,
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje',[
            'titulo' => 'Cuenta creada exitosamente',
        ]);
    }

    public static function confirmar(Router $router){
        $token = s($_GET['token']);
        if(!$token){
            header('Location:/');
        }
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
        }
        else{
            /** @var Usuario $usuario */
            $usuario->confirmado = "1";
            $usuario->token = "0";
            unset($usuario->password2);
            
            $usuario->guardar();
            Usuario::setAlerta('exito','Cuenta confirmada correctamente');
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar',[
            'titulo' => 'Confirma tu cuenta UpTask',
            'alertas'=> $alertas,
        ]);
    }
}