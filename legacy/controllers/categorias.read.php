<?php
include_once "../model/categoria.php";

$cat = new Model\Categoria();

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($id == false) {
    $result = $cat->read();
    echo json_encode($result);
} else {
    $cat->setId($id);
    $result = $cat->readId();
    echo json_encode($result);
}

unset($cat);
