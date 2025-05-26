<?php

class TemaController extends Controller
{
    /**
     * Método que llama a la view, en este metodo se recojen el resultado del select que devuelve la bd y carga los datos con todas los temas del xml,
     * también se pagina en base el numero de temas que tiene y cuantos se quieren mostrat. todo eso se le pasa a la view de temas
     */
    public function show()
    {
        if(isset($_SESSION['registro']) || isset($_SESSION['usuario'])) { 
            $daoTema = new DaoTema();
            
            $pagina_actual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
            $temas_por_pagina = 10;
            
            $total_temas = count($daoTema->selectAll());
            $total_paginas = ceil($total_temas / $temas_por_pagina);
            
            $inicio = ($pagina_actual - 1) * $temas_por_pagina;
            
            $temas_paginados = $daoTema->selectAllLimit($temas_por_pagina, $inicio);
            
            TemaView::show($temas_paginados, $pagina_actual, $total_paginas);
        } else {
            header('Location: index.php?Login/show');
        }
    }
    
    /**
     * recibe a través de un post invisble el id del tema que se quiere eliminar, depués se llama al modelo de la frase y se elimina esa en especifico de la bd
     */
    public function delete()
    {
        if (isset($_POST['id'])) {
            $id = parent::sanitize($_POST['id']);
            $daoTema = new DaoTema();
            $daoTema->deleteTema($id);
            
            $temas = $daoTema->selectAll();
            
            $pagina_actual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
            $temas_por_pagina = 10;
            
            $total_temas = count($temas);
            $total_paginas = max(ceil($total_temas / $temas_por_pagina), 1);
            
            if ($pagina_actual > $total_paginas) {
                $pagina_actual = $total_paginas;
            }
            
            $inicio = ($pagina_actual - 1) * $temas_por_pagina;
            $temas_paginados = array_slice($temas, $inicio, $temas_por_pagina);
            
            TemaView::show($temas_paginados, $pagina_actual, $total_paginas);
        }
    }
    
    /**
     * se busca el tema con un método del modelo y enseña la view
     * @param int $id del tema
     */
    public function showEdit($id)
    {
        $daoTema = new DaoTema();
        $tema = $daoTema->selectById($id);
        
        EditView::showTema($tema);
    }
    
    /**
     * eseña la view del tema
     */
    public function showCreate($id)
    {
        CreateView::showCreateTema();
    }
    
    /**
     * crea un tema en la bd, comprueba que por el post no se pase nada vacio
     */
    public function createTema()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nombre = parent::sanitize($_POST['nombre']);
            $desc = parent::sanitize($_POST['descripcion']);
            
            $errors = [];
            
            if (empty($nombre)) {
                $errors['nombre'] = "El nombre del tema es obligatorio.";
            }
            if (empty($desc)) {
                $errors['descripcion'] = "La descripción del tema es obligatoria.";
            }
            
            if (! empty($errors)) {
                CreateView::showCreateTema($errors);
                return;
            }
            
            $daoTema = new DaoTema();
            $tema = $daoTema->insertTema($nombre, $desc);
            
            header("Location: index.php?Tema/show");
        }
    }
    
    /**
     * hace lo mismo que el crate pero se le pasan los datos que ya tenia el tema a editar
     */
    public function update()
    {
        $daoTema = new DaoTema();
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = parent::sanitize($_POST['id']);
            $nombre = isset($_POST['tema']) ? parent::sanitize($_POST['tema']) : '';
            $descripcion = isset($_POST['descripcion']) ? parent::sanitize($_POST['descripcion']) : '';
            
            $errors = [];
            
            if (empty($nombre)) {
                $errors['tema'] = "El nombre del tema es obligatorio.";
            }
            
            if (empty($descripcion)) {
                $errors['descripcion'] = "La descripción del tema es obligatoria.";
            }
            
            if (! empty($errors)) {
                $tema = $daoTema->selectById($id);
                EditView::showTema($tema, $errors);
                return;
            }
            
            $tema = $daoTema->selectById($id);
            $nombreFinal = empty($nombre) || $nombre == $tema->nombre ? $tema->nombre : $nombre;
            $descripcionFinal = empty($descripcion) || $descripcion == $tema->descripcion ? $tema->descripcion : $descripcion;
            
            $daoTema->updateTema($id, $nombreFinal, $descripcionFinal);
            header("Location: index.php?Tema/show");
            exit();
        }
    }
    
    /**
     * filtra por frase,autor y tema dependiendo de lo que se le pase por el input
     */
    public function filtrar() {
        $daoTema = new DaoTema();
        
        $desc = isset($_POST['desc']) ? parent::sanitize($_POST['desc']) : '';
        $tema = isset($_POST['tema']) ? parent::sanitize($_POST['tema']) : '';
        
        $temas = $daoTema->filtrar($tema, $desc);
        
        if (empty($desc) && empty($tema)) {
            $pagina_actual = isset($_POST['pagina']) ? (int) $_POST['pagina'] : 1;
            $temas_por_pagina = 10;
            
            $total_temas = count($temas);
            $total_paginas = ceil($total_temas / $temas_por_pagina);
            
            $inicio = ($pagina_actual - 1) * $temas_por_pagina;
            $temas_paginadas = array_slice($temas, $inicio, $temas_por_pagina);
            TemaView::show($temas_paginadas, $pagina_actual, $total_paginas);
        } else {
            TemaView::show($temas, 1, 1);
        }
    }
}
