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
    public function create(){
        try {
            $request = $this->con->getCon()->prepare("INSERT INTO pedidos (codigoPed, fechaPed, idUsu, nombre, direccion, telefono, totalPed, formaPago, fechaEnvpedido ,estadoPedido, estado, usuarioCreacion, usuarioModificacion ) VALUES(:cod,:fec,:idU, :nom, :dir, :tel, :tot, :for, :feE, :estP, :est, :usC, :usM)");
            $request->bindParam(':cod',$this->codigoPed,\PDO::PARAM_INT);
            $request->bindParam(':fec',$this->fechaPed);
            $request->bindParam(':idU',$this->idUsu,\PDO::PARAM_INT);
            $request->bindParam(':nom',$this->nombre);
            $request->bindParam(':dir',$this->direccion);
            $request->bindParam(':tel',$this->telefono);
            $request->bindParam(':tot',$this->totalPed,\PDO::PARAM_INT);
            $request->bindParam(':for',$this->formaPago);
            $request->bindParam(':feE',$this->fechaEnvPedido);
            $request->bindParam(':estP',$this->estadoPedido);
            $request->bindParam(':est',$this->estado);
            $request->bindParam(':usC',$this->usuarioCreacion,\PDO::PARAM_INT);
            $request->bindParam(':usM',$this->usuarioModificacion,\PDO::PARAM_INT);
            $request->execute();
            $ultimoId = $this->con->getCon()->lastInsertId();
            return $ultimoId;
        } catch (PDOException $e) {
            return "Error al crear la compra".$e->getMessage();
        }
    }
    public function read(){
        try {
            $request = $this->con->getCon()->prepare("SELECT * FROM pedidos");
            $request->execute();
            $result = $request->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return "Error Al consultar". $e->getMessage();
        }
    }
    public function readId(){
        try {
            # code...
            $request = $this->con->getCon()->prepare("SELECT * FROM pedidos WHERE id = :id");
            $request->bindParam(':id',$this->id,\PDO::PARAM_INT);
            $request->execute();
            $result = $request->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            # code...
            return "Error Al Traer el usuario". $e->getMessage();
        }
    }
    public function delete(){
        try {
            # code...
            $request = $this->con->getCon()->prepare("DELETE FROM pedidos WHERE id =:id");
            $request->bindParam(':id',$this->id,\PDO::PARAM_INT);
            $request->execute();
            return "Eliminado";
        } catch (PDOException $e) {
            # code...
            return "Error al Eliminar".$e->getMessage();
        }
    }
    public function estado(){
        try {
            //code...
            $request = $this->con->getCon()->prepare("UPDATE pedidos SET `estado`= ? WHERE id = ?");
            $request->bindParam(1,$this->estado);
            $request->bindParam(2,$this->id);
            $request->execute();
            return "Estado Modificado";
        } catch (PDOException $e) {
            //PDOExeption $e;
            return "Error".$e->getMessage();
        }
    }
    public function update(){
        try {
            //code...
            $request = $this->con->getCon()->prepare("UPDATE pedidos SET `codigoPed`=:cod,`fechaPed`=:ide,`nombre`=:n,`idUsu`=:a,`totalPed`=:c,`direccion`=:d,`telefono`=:t,`formaPago`=:g,`fechaEnvpedido`=:ir `estadoPedido`=:estP `usuarioModificacion`=:userM WHERE  `id`= :id ;");
            $request->bindParam(':id',$this->id,\PDO::PARAM_INT);
            $request->bindParam(':cod',$this->codigoPed);
            $request->bindParam(':ide',$this->fechaPed);
            $request->bindParam(':n',$this->nombre);
            $request->bindParam(':a',$this->idUsu);
            $request->bindParam(':c',$this->totalPed);
            $request->bindParam(':d',$this->direccion);
            $request->bindParam(':t',$this->telefono);
            $request->bindParam(':g',$this->formaPago);
            $request->bindParam(':ir',$this->fechaEnvPedido);
            $request->bindParam(':estP',$this->estadoPedido);
            $request->bindParam(':userM',$this->usuarioModificacion);
            $request->execute();
            return "Actualizado";
        } catch (PDOException $e) {
            //Except $e;
            return "Error al actualizar".$e->getMessage();
        }
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
