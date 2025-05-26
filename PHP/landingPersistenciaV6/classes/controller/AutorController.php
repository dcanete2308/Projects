<?php

class AutorController extends Controller
{

    /**
     * Método que llama a la view, en este metodo se recojen el resultado del select que devuelve la bd y carga los datos con todas las frases del xml,
     * también se pagina en base el numero de autores que tiene y cuantos se quieren mostrat.
     * todo eso se le pasa a la view del autor
     */
//     public function show()
//     {
//         if(isset($_SESSION['registro']) || isset($_SESSION['usuario'])) { //no deja entrar si no estas           
//             $daoAutor = new DaoAutor();
//             $autores = $daoAutor->selectAll();
            
//             $pagina_actual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
//             $autores_por_pagina = 10;
            
//             $total_autores = count($autores);
//             $total_paginas = ceil($total_autores / $autores_por_pagina);
            
//             $inicio = ($pagina_actual - 1) * $autores_por_pagina;
//             $autores_paginados = array_slice($autores, $inicio, $autores_por_pagina);
            
//             autorFrasesView::show($autores_paginados, $pagina_actual, $total_paginas);
//         } else {
//             header('Location: index.php?Login/show');
//         }
//     }

    public function show()
    {
        if(isset($_SESSION['registro']) || isset($_SESSION['usuario'])) { 
            $daoAutor = new DaoAutor();
            
            $pagina_actual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
            $autores_por_pagina = 10;
            
            $todos_los_autores = $daoAutor->selectAll();
            $total_autores = count($todos_los_autores);
            $total_paginas = ceil($total_autores / $autores_por_pagina);
            
            $inicio = ($pagina_actual - 1) * $autores_por_pagina;
            
            $autores_paginados = $daoAutor->selectAllLimit($autores_por_pagina, $inicio);
            
            autorFrasesView::show($autores_paginados, $pagina_actual, $total_paginas);
        } else {
            header('Location: index.php?Login/show');
        }
    }
    /**
     * recibe a través de un post invisble el id del autor que se quiere eliminar, depués se llama al modelo del autor y se elimina ese en especifico de la bd
     */
    public function delete()
    {
        if (isset($_POST['id'])) {
            $id = parent::sanitize($_POST['id']);
            $daoAutor = new DaoAutor();
            $daoAutor->deleteAuthor($id);
            
            $autores = $daoAutor->selectAll();
            
            $pagina_actual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
            $autores_por_pagina = 10;
            
            $total_autores = count($autores);
            $total_paginas = max(ceil($total_autores / $autores_por_pagina), 1);
            
            if ($pagina_actual > $total_paginas) {
                $pagina_actual = $total_paginas;
            }
            
            $inicio = ($pagina_actual - 1) * $autores_por_pagina;
            $autores_paginados = array_slice($autores, $inicio, $autores_por_pagina);
            
            autorFrasesView::show($autores_paginados, $pagina_actual, $total_paginas);
        }
    }
    

    /**
     * llama a la view de editar autor para, se hace un select de un autor en especifico para coger sus datos y sobreescribirlos
     *
     * @param int $id
     *            id del autor que se quiere editar
     */
    public function showEdit($id)
    {
        $daoAutor = new DaoAutor();
        $autor = $daoAutor->selectById($id);
        EditView::showAutor($autor);
    }

    /**
     * llama a la view de crear autor
     */
    public function showCreateAutor($id)
    {
        CreateView::showCreateAuthor();
    }

    /**
     * metodo que recoge los valores que se han pasado por el formulario de crear autor y los valida, no deja enviarlo si hay errores que se mostraran en el formulario,
     * si no hay errores los inseta en la bd
     */
    public function insert()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nom = parent::sanitize($_POST['nom']);
            $desc = parent::sanitize($_POST['desc']);
            $url = parent::sanitize($_POST['url']);

            $errors = [];

            if (empty($nom)) {
                $errors['nom'] = "El nombre del autor es obligatorio.";
            }
            if (empty($desc)) {
                $errors['desc'] = "La descripción del autor es obligatoria.";
            }
            if (empty($url)) {
                $errors['url'] = "La URL es obligatoria.";
            }

            if (! empty($errors)) {
                CreateView::showCreateAuthor($errors);
                return;
            }

            $daoAutor = new DaoAutor();
            $autorId = $daoAutor->selectByName($nom);

            if ($autorId) {
                $this->update();
            } else {
                $daoAutor->insertAutor($nom, $desc, $url);
            }

            header("Location: index.php?Autor/show");
        }
    }

    /**
     * metodo que recoge los valores que se han pasado por el formulario de editar autor y los valida, no deja enviarlo si hay errores que se mostraran en el formulario,
     * si no hay errores los inseta en la bd
     */
    public function update()
    {
        $daoAutor = new DaoAutor();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = parent::sanitize($_POST['id']);
            $nom = parent::sanitize($_POST['nom']);
            $desc = parent::sanitize($_POST['desc']);
            $url = parent::sanitize($_POST['url']);

            $errors = [];

            if (empty($nom)) {
                $errors['nom'] = "El nombre del autor es obligatorio.";
            }
            if (empty($desc)) {
                $errors['desc'] = "La descripción del autor es obligatoria.";
            }
            if (empty($url)) {
                $errors['url'] = "La URL es obligatoria.";
            }

            if (! empty($errors)) {
                $daoAutor = new DaoAutor();
                $autor = $daoAutor->selectById($id);
                EditView::showAutor($autor, $errors);
                return;
            }

            $autor = $daoAutor->selectById($id);

            $nombreFinal = empty($nom) || $nom == $autor->nom ? $autor->nom : $nom;
            $descFinal = empty($desc) || $desc == $autor->desc ? $autor->desc : $desc;
            $urlFinal = empty($url) || $url == $autor->url ? $autor->url : $url;

            $daoAutor->updateAuthor($id, $nombreFinal, $descFinal, $urlFinal);

            header("Location: index.php?Autor/show");
        }
    }

    /**
     * formulario que te filtra segun lo que le pasas por el input, llama al metodo de filtrar que hace un select de la bd con los valores especificos
     */
    public function filtrar()
    {
        $daoAutor = new DaoAutor();

        $desc = isset($_POST['desc']) ? parent::sanitize($_POST['desc']) : '';
        $autor = isset($_POST['autor']) ? parent::sanitize($_POST['autor']) : '';

        $frases = $daoAutor->filtrar($autor, $desc);

        if (empty($desc) && empty($autor)) {
            $pagina_actual = isset($_POST['pagina']) ? (int) $_POST['pagina'] : 1;
            $frases_por_pagina = 10;

            $total_frases = count($frases);
            $total_paginas = ceil($total_frases / $frases_por_pagina);

            $inicio = ($pagina_actual - 1) * $frases_por_pagina;
            $frases_paginadas = array_slice($frases, $inicio, $frases_por_pagina);
            autorFrasesView::show($frases_paginadas, $pagina_actual, $total_paginas);
        } else {
            autorFrasesView::show($frases, 1, 1);
        }
    }
}
