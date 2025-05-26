<?php
class EstudiosController extends Controller
{
    public function show()
    {
        $vEstudios = new EstudiosView();
        $vEstudios->showEstudios();
    }
    
}

