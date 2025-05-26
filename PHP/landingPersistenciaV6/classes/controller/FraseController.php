<?php

class FraseController extends Controller
{
    /**
     * Método que llama a la view, en este metodo se recojen el resultado del select que devuelve la bd y carga los datos con todas las frases del xml,
     * también se pagina en base el numero de frases que tiene y cuantos se quieren mostrat. todo eso se le pasa a la view de frases
     */
    public function show()
    {
        if (isset($_SESSION['registro']) || isset($_SESSION['usuario'])) { 
            $daoFrase = new DaoFrase();
            
            $pagina_actual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
            $frases_por_pagina = 10;
            
            $todas_las_frases = $daoFrase->selectAll();
            $total_frases = count($todas_las_frases);
            $total_paginas = ceil($total_frases / $frases_por_pagina);
            
            $inicio = ($pagina_actual - 1) * $frases_por_pagina;
            
            $frases_paginadas = $daoFrase->selectAllLimit($frases_por_pagina, $inicio);
            
            FraseView::show($frases_paginadas, $pagina_actual, $total_paginas);
        } else {
            header('Location: index.php?Login/show');
        }
    }
    
    /**
     * recibe a través de un post invisble el id de la frase que se quiere eliminar, depués se llama al modelo de la frase y se elimina esa en especifico de la bd
     */
    public function delete()
    {
        if (isset($_POST['id'])) {
            $id = parent::sanitize($_POST['id']);
            $daoFrase = new DaoFrase();
            $daoFrase->deleteFrase($id);
           
            $frases = $daoFrase->selectAll();
            
            $pagina_actual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
            $frases_por_pagina = 10;
            
            $total_frases = count($frases);
            $total_paginas = max(ceil($total_frases / $frases_por_pagina), 1);
            
            if ($pagina_actual > $total_paginas) {
                $pagina_actual = $total_paginas;
            }
            
            $inicio = ($pagina_actual - 1) * $frases_por_pagina;
            $frases_paginadas = array_slice($frases, $inicio, $frases_por_pagina);
            
            FraseView::show($frases_paginadas, $pagina_actual, $total_paginas);
        }
    }
    
    
    /**
     * llama a la view de editar frase para, se hace un select de una farse en especifico para coger sus datos y sobreescribirlos
     * @param int $id id de la frase que se quiere editar
     */
    public function showEdit($id)
    {
        $daoFrase = new DaoFrase();
        $frase = $daoFrase->selectById($id);
        $daoTema = new DaoTema();
        $temas = $daoTema->selectAllThemes();
        
        $daoAutor = new DaoAutor();
        $autores = $daoAutor->selectAll();
        EditView::showFrase($frase, $temas, $autores);
    }
    
    /**
     * llama a la view de crear frase, también se recogen todos los temas y todos los autores que existen para enseñarlos en un select
     */
    public function showCreateFrase()
    {
        $daoTema = new DaoTema();
        $temas = $daoTema->selectAllThemes();
        
        $daoAutor = new DaoAutor();
        $autores = $daoAutor->selectAll();
        CreateView::showCreateFrase($temas, $autores);
    }
    
    /**
     * se validan los datos que se pasan para crear una frase, del autor y del tema controlamos que no se pueda pasar nada en caso que el usuario lo cambie el tipo del field
     * para eso lo hacemos con un método que buscará si existe ese autor en la bd
     */
    public function createFrase()
    {
        $daoFrase = new DaoFrase();
        $daoAutor = new DaoAutor();
        $daoTema = new DaoTema();
        
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $texto = parent::sanitize($_POST['texto']);
            $autor = parent::sanitize($_POST['autor']);
            $tema = parent::sanitize($_POST['tema']);
            
            $errors = [];
            
            if (empty($texto)) {
                $errors['texto'] = "El texto de la frase es obligatorio.";
            }
            
            $autorId = $daoAutor->selectByName($autor);
            if (empty($autor)) {
                $errors['autor'] = "Debe seleccionar un autor.";
            } elseif ($autorId === false) {
                $errors['autor'] = "El autor seleccionado no existe.";
            }
            
            $temaId = $daoTema->selectByName($tema);
            if (empty($tema)) {
                $errors['tema'] = "Debe seleccionar un tema.";
            } elseif ($temaId === false) {
                $errors['tema'] = "El tema seleccionado no existe.";
            }
            
            if (! empty($errors)) {
                $temas = $daoTema->selectAllThemes();
                $autores = $daoAutor->selectAll();
                
                CreateView::showCreateFrase($temas, $autores, $errors);
                return;
            }
            
            $autorId = $daoAutor->selectByName($autor);
            $temaId = $daoTema->selectByName($tema);
            
            $daoFrase->insertFrase($texto, $autorId, $temaId);
            
            header("Location: index.php?Frase/show");
        }
    }
    
    /**
     * hace lo mismo que el create pero a la hora de llamar a la view se le pasan los calores que ya tenia ese autor
     */
    public function update()
    {
        $daoFrase = new DaoFrase();
        $daoAutor = new DaoAutor();
        $daoTema = new DaoTema();
        
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $frase_txt = parent::sanitize($_POST['texto']);
            $id = parent::sanitize($_POST['id']);
            $autor = parent::sanitize($_POST['autor']);
            $tema = parent::sanitize($_POST['tema']);
            
            $errors = [];
            
            if (empty($frase_txt)) {
                $errors['texto'] = "El texto de la frase es obligatorio.";
            }
            
            $autorId = $daoAutor->selectByName($autor);
            if (empty($autor)) {
                $errors['autor'] = "Debe seleccionar un autor.";
            } elseif ($autorId === false) {
                $errors['autor'] = "El autor seleccionado no existe.";
            }
            
            $temaId = $daoTema->selectByName($tema);
            if (empty($tema)) {
                $errors['tema'] = "Debe seleccionar un tema.";
            } elseif ($temaId === false) {
                $errors['tema'] = "El tema seleccionado no existe.";
            }
            
            if (! empty($errors)) {
                $frase = $daoFrase->selectById($id);
                $temas = $daoTema->selectAllThemes();
                $autores = $daoAutor->selectAll();
                
                EditView::showFrase($frase, $temas, $autores, $errors);
                return;
            }
            
            $frase = $daoFrase->selectById($id);
            
            $idAutor = $daoFrase->selectAutor($autor);
            $idTema = $daoFrase->selectTema($tema);
            
            $fraseFinal = empty($frase_txt) || $frase_txt == $frase->texto ? $frase->texto : $frase_txt;
            
            $daoFrase->updateFrase($id, $fraseFinal, $idAutor, $idTema);
            header("Location: index.php?Frase/show");
        }
    }
    
    /**
     * filtra por frase,autor y tema dependiendo de lo que se le pase por el input
     */
    public function filtrar()
    {
        $daoFrase = new DaoFrase();
        
        $frase = isset($_POST['frase']) ? parent::sanitize($_POST['frase']) : '';
        $autor = isset($_POST['autor']) ? parent::sanitize($_POST['autor']) : '';
        $tema = isset($_POST['tema']) ? parent::sanitize($_POST['tema']) : '';
        
        $frases = $daoFrase->filtrar($frase, $autor, $tema);
        
        if (empty($frase) && empty($autor) && empty($tema)) {
            $pagina_actual = isset($_POST['pagina']) ? (int) $_POST['pagina'] : 1;
            $frases_por_pagina = 10;
            
            $total_frases = count($frases);
            $total_paginas = ceil($total_frases / $frases_por_pagina);
            
            $inicio = ($pagina_actual - 1) * $frases_por_pagina;
            $frases_paginadas = array_slice($frases, $inicio, $frases_por_pagina);
            FraseView::show($frases_paginadas, $pagina_actual, $total_paginas);
        } else {
            FraseView::show($frases, 1, 1);
        }
        
    }
    
    
}
