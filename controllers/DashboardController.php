<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;
use Model\Proyecto;

class DashboardController {
    public static function index(Router $router){
        session_start();
        isAuth();
        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        $router->render('dashboard/index',[
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos,
        ]);
    }

    public static function crear_proyecto(Router $router){
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = new Proyecto($_POST);
            $alertas = $proyecto->validarProyecto();
            if(empty($alertas)){
                //generar una url unica
                $proyecto->url = md5(uniqid());
                $proyecto->propietarioId = $_SESSION['id'];
                $proyecto->guardar();

                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }

        $router->render('dashboard/crear-proyecto',[
            'titulo' => 'Crear proyecto',
            'alertas'=> $alertas,
        ]);
    }

    public static function proyecto(Router $router){
        session_start();
        isAuth();
        $alertas = [];
        
        $token = $_GET['id'];
        if(!$token) header('Location: /dashboard');
        
        /** @var Proyecto $proyecto */
        $proyecto = Proyecto::where('url', $token);
        if($proyecto->propietarioId !== $_SESSION['id']){
            header('Location: /dashboard');
        }
        $router->render('dashboard/proyecto',[
            'titulo' => $proyecto->proyecto,
            'alertas'=> $alertas,
        ]);
    }

    public static function perfil(Router $router){
        session_start();
        isAuth();
        $router->render('dashboard/perfil',[
            'titulo' => 'Perfil',
        ]);
    }
}