<?php

class FraseEditController extends Controller
{

    public function show($id){
        $daoFrase = new DaoFrase();
        $frase = $daoFrase->selectById($id);
        EditView::showFrase($frase);
    }

    public function update($id)
    {}
}

