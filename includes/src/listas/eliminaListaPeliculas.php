<?php

    use es\ucm\fdi\aw\listas\Lista;

    //Capturamos el id de la película y el id de la lista
    $idLista = $_POST['lista_id'];

    //Eliminamos la película de la lista que deseamos y redigirimos al usuario a la vista de misListas
    Lista::eliminaListaPeliculas($idLista);
    $relativePath = '/AW/Proyecto-AW/misListas.php';
    header('Location: ' . $relativePath);