<?php

namespace Model;

use PDOException;

include_once "conexion.php";
class Pedido {
    private $id;
    private $codigoPed;
    private $fechaPed;
    private $idUsu;
    private $nombre;
    private $direccion;
    private $telefono;
    private $totalPed;
    private $formaPago;
    private $fechaEnvPedido;
    private $estadoPedido;
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

    public function getCodigoPed() {
        return $this->codigoPed;
    }

    public function setCodigoPed($codigoPed) {
        $this->codigoPed = $codigoPed;
    }

    public function getFechaPed() {
        return $this->fechaPed;
    }

    public function setFechaPed($fechaPed) {
        $this->fechaPed = $fechaPed;
    }

    public function getIdUsu() {
        return $this->idUsu;
    }

    public function setIdUsu($idUsu) {
        $this->idUsu = $idUsu;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function getTotalPed() {
        return $this->totalPed;
    }

    public function setTotalPed($totalPed) {
        $this->totalPed = $totalPed;
    }

    public function getFormaPago() {
        return $this->formaPago;
    }

    public function setFormaPago($formaPago) {
        $this->formaPago = $formaPago;
    }

    public function getFechaEnvPedido() {
        return $this->fechaEnvPedido;
    }

    public function setFechaEnvPedido($fechaEnvPedido) {
        $this->fechaEnvPedido = $fechaEnvPedido;
    }

    public function getEstadoPedido() {
        return $this->estadoPedido;
    }

    public function setEstadoPedido($estadoPedido) {
        $this->estadoPedido = $estadoPedido;
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
