<?php

namespace Model;

use PDOException;

include_once "conexion.php";

class Rol{

    private $id;
    private $nameRol;
    private $estadoRol = 'A';
    private $usuarioCreacion;
    private $usuarioModificacion;
    private $fechaModificacion;
    public $con; //* Objeto conexion

    public function __construct(){
        $this->con = new \Conexion();
    }

    public function create(){
        try {
            $request = $this->con->getCon()->prepare("INSERT INTO roles(nombreRol, estado, usuarioCreacion) VALUES(:nombre,:estado, :userC)");
            $request->bindParam(':nombre',$this->nameRol);
            $request->bindParam(':estado',$this->estadoRol);
            $request->bindParam(':userC',$this->usuarioCreacion);
            $request->execute();
            return "Rol Creado";
        } catch (PDOException $e) {
            return "Error al crear Rol ".$e->getMessage();
        }
    }

    public function read(){
        try {
            $request = $this->con->getCon()->prepare("SELECT * FROM roles");
            $request->execute();
            $result = $request->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return "Error Al consultar roles ". $e->getMessage();
        }
    }

    public function readID(){
        try {
            $request = $this->con->getCon()->prepare("SELECT * FROM roles WHERE id = :id");
            $request->bindParam(':id',$this->id,\PDO::PARAM_INT);
            $request->execute();
            $result = $request->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return "Error Al Traer el rol". $e->getMessage();
        }
    }
    public function update(){
        try {
            $request = $this->con->getCon()->prepare("UPDATE roles SET `nombreRol`=:nombreRol, `usuarioModificacion`=:userM, `fechaModificacion`=:fechaM WHERE  `id`= :id;");
            $request->bindParam(':id',$this->id);
            $request->bindParam(':nombreRol',$this->nameRol);
            $request->bindParam(':userM',$this->usuarioModificacion);
            $request->bindParam(':fechaM',$this->fechaModificacion);
            $request->execute();
            return "Rol Actualizado";
        } catch (PDOException $e) {
            return "Error al actualizar Rol".$e->getMessage();
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
    public function estado(){
        try {
            //code...
            $request = $this->con->getCon()->prepare("UPDATE roles SET `estado`= ? WHERE id = ?");
            $request->bindParam(1,$this->estadoRol);
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

    /**
     * Get the value of usuarioCreacion
     */
    public function getUsuarioCreacion()
    {
        return $this->usuarioCreacion;
    }

    /**
     * Set the value of usuarioCreacion
     */
    public function setUsuarioCreacion($usuarioCreacion): self
    {
        $this->usuarioCreacion = $usuarioCreacion;

        return $this;
    }

    /**
     * Get the value of usuarioModificacion
     */
    public function getUsuarioModificacion()
    {
        return $this->usuarioModificacion;
    }

    /**
     * Set the value of usuarioModificacion
     */
    public function setUsuarioModificacion($usuarioModificacion): self
    {
        $this->usuarioModificacion = $usuarioModificacion;

        return $this;
    }

    /**
     * Get the value of fechaModificacion
     */
    public function getFechaModificacion()
    {
        return $this->fechaModificacion;
    }

    /**
     * Set the value of fechaModificacion
     */
    public function setFechaModificacion($fechaModificacion): self
    {
        $this->fechaModificacion = $fechaModificacion;

        return $this;
    }
}