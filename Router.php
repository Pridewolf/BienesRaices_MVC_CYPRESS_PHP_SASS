<?php 

namespace MVC;

class Router{

    public array $rutasGET = [];
    public array $rutasPOST = [];
    //---------------------- METODO GET ----------- //
    public function get($url, $fn){
        $this->rutasGET[$url] = $fn;
    }
    // --------------------- METODO POST ----------- //
    public function post($url, $fn){
        $this->rutasPOST[$url] = $fn;
    }


    // ------------------ ACÁ COMPORBAMOS LAS RUTAS --------- //
    public function comprobarRutas(){
        session_start();
        $auth = $_SESSION['login'] ?? null;

        // Arreglo de rutas protegidas ... 
        $rutas_protegidas = ['/admin', '/propiedades/crear', '/propiedades/actualizar', '/propiedades/eliminar', '/vendedores/crear', '/vendedores/actualizar', '/vendedores/eliminar', '/blogs/crear', '/blogs/actualizar','/blogs/eliminar', '/blogs/index'];


        
        $urlActual = $_SERVER['PATH_INFO'] ?? '/';
        $metodo = $_SERVER['REQUEST_METHOD'];
        
        // debuguear($_SESSION);
        if($metodo === 'GET'){
            $fn = $this->rutasGET[$urlActual] ?? null;
        } else {
            $fn = $this->rutasPOST[$urlActual] ?? null;
        }

        // Proteger las rutas 

        if(in_array($urlActual, $rutas_protegidas) && !$auth){
            header('location: /');
        }

        if($fn){
            // La URL existe y hay una funcion asociada 
            call_user_func($fn, $this);
        } else {
            echo "Página no encontrada";
        }
    }

    // muestra una vista

    public function render($view, $datos = []){

        foreach($datos as $key => $value)
        {
            $$key = $value;
        }

        ob_start(); // Almacenamiento en memoria durante un momento

        include __DIR__ . "/views/$view.php";

        $contenido = ob_get_clean(); // Limpia lo que esta en memoria

        include __DIR__."/views/layout.php";
    }
}

