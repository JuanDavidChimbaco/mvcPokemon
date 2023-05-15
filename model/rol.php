<?php

namespace Model;

use PDOException;

include_once "conexion.php";

class Rol{

    private $id;
    private $nameRol;
    private $estadoRol = 'A';
    public $con; //* Objeto conexion

    public function __construct(){
        $this->con = new \Conexion();
    }

    public function create(){
        try {
            # code...
            $request = $this->con->getCon()->prepare("INSERT INTO roles(nombreRol, estado) VALUES(:nombre,:estado)");
            $request->bindParam(':nombre',$this->nameRol);
            $request->bindParam(':estado',$this->estadoRol);
            $request->execute();
            return "Rol Creado";
        } catch (PDOException $e) {
            # code...
            return "Error al crear Rol ".$e->getMessage();
        }
    }

    public function read(){
        try {
            //code...
            $request = $this->con->getCon()->prepare("SELECT * FROM roles WHERE estado = 'A'");
            $request->execute();
            $result = $request->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            //Except $e;
            return "Error Al consultar roles ". $e->getMessage();
        }
    }

    public function readID(){
        try {
            # code...
            $request = $this->con->getCon()->prepare("SELECT * FROM roles WHERE estado = 'A' AND id = :id");
            $request->bindParam(':id',$this->id,\PDO::PARAM_INT);
            $request->execute();
            $result = $request->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            # code...
            return "Error Al Traer el rol". $e->getMessage();
        }
    }
    public function update(){
        try {
            //code...
            $request = $this->con->getCon()->prepare("UPDATE roles SET `nombreRol`=:nombreRol , `estado`=:estado WHERE  `id`= :id");
            $request->bindParam(':id',$this->id);
            $request->bindParam(':nombreRol',$this->nameRol);
            $request->bindParam(':estado',$this->estadoRol);
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
            $request = $this->con->getCon()->prepare("DELETE FROM roles WHERE id =:id");
            $request->bindParam(':id',$this->id,\PDO::PARAM_INT);
            $request->execute();
            return "Rol Eliminado";
        } catch (PDOException $e) {
            # code...
            return "Error al Eliminar Rol ".$e->getMessage();
        }
    }
    /**
     * Get the value of id
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): self{
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of nameRol
     */
    public function getNameRol(){
        return $this->nameRol;
    }

    /**
     * Set the value of nameRol
     */
    public function setNameRol($nameRol): self
    {
        $this->nameRol = $nameRol;

        return $this;
    }


    /**
     * Get the value of estadoRol
     */
    public function getEstadoRol()
    {
        return $this->estadoRol;
    }

    /**
     * Set the value of estadoRol
     */
    public function setEstadoRol($estadoRol): self
    {
        $this->estadoRol = $estadoRol;

        return $this;
    }
}