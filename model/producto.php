<?php

namespace Model;

use PDOException;

include_once "conexion.php";

class Producto{
    private $id;
    private $nombrePro;
    private $precioPro;
    private $cantidadPro;
    private $descripPro;
    private $estado = 'A';
    public $con; //* Objeto conexion

    public function __construct(){
        $this->con = new \Conexion();
    }

    public function create(){
        try {
            # code...
            $request = $this->con->getCon()->prepare("INSERT INTO productos(nombrePro,precioPro,cantidadPro,descripPro,estado) VALUES(:nombre, :precio, :cantidad, :descripcion,:estado)");
            $request->bindParam(':nombre',$this->nombrePro);
            $request->bindParam(':precio',$this->precioPro);
            $request->bindParam(':cantidad',$this->cantidadPro);
            $request->bindParam(':descripcion',$this->descripPro);
            $request->bindParam(':estado',$this->estado);
            $request->execute();
            return "Producto Creado";
        } catch (PDOException $e) {
            # code...
            return "Error al crear producto ".$e->getMessage();
        }
    }

    public function read(){
        try {
            //code...
            $request = $this->con->getCon()->prepare("SELECT * FROM productos");
            $request->execute();
            $result = $request->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            //Except $e;
            return "Error Al consultar productos ". $e->getMessage();
        }
    }

    public function readID(){
        try {
            # code...
            $request = $this->con->getCon()->prepare("SELECT * FROM productos WHERE id = :id");
            $request->bindParam(':id',$this->id,\PDO::PARAM_INT);
            $request->execute();
            $result = $request->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            # code...
            return "Error Al Traer el producto". $e->getMessage();
        }
    }
    public function update(){
        try {
            //code...
            $request = $this->con->getCon()->prepare("UPDATE roles SET `nombreRol`=:nombreRol WHERE  `id`= :id ;");
            $request->bindParam(':id',$this->id);
            $request->bindParam(':nombreRol',$this->nombrePro);
            $request->execute();
            return "Rol Actualizado";
        } catch (PDOException $e) {
            //Except $e;
            return "Error al actualizar Rol ".$e->getMessage();
        }
    }
    public function delete(){
        try {
            # code...
            $request = $this->con->getCon()->prepare("DELETE FROM productos WHERE id =:id");
            $request->bindParam(':id',$this->id,\PDO::PARAM_INT);
            $request->execute();
            return "Producto Eliminado";
        } catch (PDOException $e) {
            # code...
            return "Error al Eliminar Producto ".$e->getMessage();
        }
    }
    public function estado(){
        try {
            //code...
            $request = $this->con->getCon()->prepare("UPDATE roles SET `estado`= ? WHERE id = ?");
            $request->bindParam(1,$this->estado);
            $request->bindParam(2,$this->id);
            $request->execute();
            return "Estado Modificado";
        } catch (PDOException $e) {
            //PDOExeption $e;
            return "Error".$e->getMessage();
        }
    }
    

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of nombrePro
     */
    public function getNombrePro()
    {
        return $this->nombrePro;
    }

    /**
     * Set the value of nombrePro
     */
    public function setNombrePro($nombrePro): self
    {
        $this->nombrePro = $nombrePro;

        return $this;
    }

    /**
     * Get the value of precioPro
     */
    public function getPrecioPro()
    {
        return $this->precioPro;
    }

    /**
     * Set the value of precioPro
     */
    public function setPrecioPro($precioPro): self
    {
        $this->precioPro = $precioPro;

        return $this;
    }

    /**
     * Get the value of cantidadPro
     */
    public function getCantidadPro()
    {
        return $this->cantidadPro;
    }

    /**
     * Set the value of cantidadPro
     */
    public function setCantidadPro($cantidadPro): self
    {
        $this->cantidadPro = $cantidadPro;

        return $this;
    }

    /**
     * Get the value of descripPro
     */
    public function getDescripPro()
    {
        return $this->descripPro;
    }

    /**
     * Set the value of descripPro
     */
    public function setDescripPro($descripPro): self
    {
        $this->descripPro = $descripPro;

        return $this;
    }

    /**
     * Get the value of estado
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set the value of estado
     */
    public function setEstado($estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get the value of con
     */
    public function getCon()
    {
        return $this->con;
    }

    /**
     * Set the value of con
     */
    public function setCon($con): self
    {
        $this->con = $con;

        return $this;
    }
}