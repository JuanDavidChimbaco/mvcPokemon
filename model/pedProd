<?php

namespace Model;

use PDOException;

include_once "conexion.php";
class PedProd {
    private $id;
    private $idPed;
    private $idPro;
    private $cantidadPro;
    private $estado;
    private $usuarioCreacion;
    private $fechaCreacion;
    private $usuarioModificacion;
    private $fechaModificacion;
    public $con; //* Objeto conexion

    // Constructor
    public function __construct() {
        $this->con = new \Conexion();
    }

    // Getters y Setters
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getIdPed() {
        return $this->idPed;
    }

    public function setIdPed($idPed) {
        $this->idPed = $idPed;
    }

    public function getIdPro() {
        return $this->idPro;
    }

    public function setIdPro($idPro) {
        $this->idPro = $idPro;
    }

    public function getCantidadPro() {
        return $this->cantidadPro;
    }

    public function setCantidadPro($cantidadPro) {
        $this->cantidadPro = $cantidadPro;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getUsuarioCreacion() {
        return $this->usuarioCreacion;
    }

    public function setUsuarioCreacion($usuarioCreacion) {
        $this->usuarioCreacion = $usuarioCreacion;
    }

    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;
    }

    public function getUsuarioModificacion() {
        return $this->usuarioModificacion;
    }

    public function setUsuarioModificacion($usuarioModificacion) {
        $this->usuarioModificacion = $usuarioModificacion;
    }

    public function getFechaModificacion() {
        return $this->fechaModificacion;
    }

    public function setFechaModificacion($fechaModificacion) {
        $this->fechaModificacion = $fechaModificacion;
    }
}
