<?php

namespace Model;

use PDOException;

include_once "conexion.php";

class Impuesto{

    private $id;
    private $nameImp;
    private $percentage;
    private $status = 'A';
    private $userCreated;
    private $userModified;
    public $con; //* Objeto conexion

    public function __construct(){
        $this->con = new \Conexion();
    }

    public function create(){
        try {
            $request = $this->con->getCon()->prepare("INSERT INTO impuestos(nombreImp, porcentaje, estado, usuarioCreacion, usuarioModificacion ) VALUES(:nom,:por,:est,:usC,:usM)");
            $request->bindParam(':nom',$this->nameImp);
            $request->bindParam(':por',$this->percentage);
            $request->bindParam(':est',$this->status);
            $request->bindParam(':usC',$this->userCreated);
            $request->bindParam(':usM',$this->userModified);
            $request->execute();
            return "Impuesto Creado";
        } catch (PDOException $e) {
            return "Error al crear el Impuesto ".$e->getMessage();
        }
    }

    public function read(){
        try {
            //code...
            $request = $this->con->getCon()->prepare("SELECT * FROM impuestos");
            $request->execute();
            $result = $request->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            //Except $e;
            return "Error Al consultar impuestos ". $e->getMessage();
        }
    }

    // public function readID(){
    //     try {
    //         # code...
    //         $request = $this->con->getCon()->prepare("SELECT * FROM roles WHERE id = :id");
    //         $request->bindParam(':id',$this->id,\PDO::PARAM_INT);
    //         $request->execute();
    //         $result = $request->fetch(\PDO::FETCH_ASSOC);
    //         return $result;
    //     } catch (PDOException $e) {
    //         # code...
    //         return "Error Al Traer el rol". $e->getMessage();
    //     }
    // }
    // public function update(){
    //     try {
    //         //code...
    //         $request = $this->con->getCon()->prepare("UPDATE roles SET `nombreRol`=:nombreRol WHERE  `id`= :id ;");
    //         $request->bindParam(':id',$this->id);
    //         $request->bindParam(':nombreRol',$this->nameRol);
    //         $request->execute();
    //         return "Rol Actualizado";
    //     } catch (PDOException $e) {
    //         //Except $e;
    //         return "Error al actualizar Rol ".$e->getMessage();
    //     }
    // }
    // public function delete(){
    //     try {
    //         # code...
    //         $request = $this->con->getCon()->prepare("DELETE FROM roles WHERE id =:id");
    //         $request->bindParam(':id',$this->id,\PDO::PARAM_INT);
    //         $request->execute();
    //         return "Rol Eliminado";
    //     } catch (PDOException $e) {
    //         # code...
    //         return "Error al Eliminar Rol ".$e->getMessage();
    //     }
    // }
    public function estado(){
        try {
            $request = $this->con->getCon()->prepare("UPDATE impuestos SET `estado`= ? WHERE id = ?");
            $request->bindParam(1,$this->status);
            $request->bindParam(2,$this->id);
            $request->execute();
            return "Estado Modificado";
        } catch (PDOException $e) {
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
     * Get the value of nameImp
     */
    public function getNameImp()
    {
        return $this->nameImp;
    }

    /**
     * Set the value of nameImp
     */
    public function setNameImp($nameImp): self
    {
        $this->nameImp = $nameImp;

        return $this;
    }

    /**
     * Get the value of percentage
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * Set the value of percentage
     */
    public function setPercentage($percentage): self
    {
        $this->percentage = $percentage;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of userCreated
     */
    public function getUserCreated()
    {
        return $this->userCreated;
    }

    /**
     * Set the value of userCreated
     */
    public function setUserCreated($userCreated): self
    {
        $this->userCreated = $userCreated;

        return $this;
    }

    /**
     * Get the value of userModified
     */
    public function getUserModified()
    {
        return $this->userModified;
    }

    /**
     * Set the value of userModified
     */
    public function setUserModified($userModified): self
    {
        $this->userModified = $userModified;

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