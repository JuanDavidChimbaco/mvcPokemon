<?php

namespace Model;

use PDO;
use PDOException;

include_once "conexion.php";

class CompraProducto{
    private $id;
    private $idCom;
    private $idPro;
    private $cantidadProCom;
    private $estado = 'A';
    private $usuarioCreacion;
    private $usuarioModificacion;
    private $fechaModificacion;
    public $con; //* Objeto conexion

    public function __construct(){
        $this->con = new \Conexion();
    }
    public function create(){
        try {
            $this->con->getCon()->beginTransaction();
    
            $request = $this->con->getCon()->prepare("INSERT INTO comprod (idCom, idPro, cantidadProCom, estado, usuarioCreacion, usuarioModificacion) VALUES(:idC, :idP, :cPC, :est, :usC, :usM)");
            $request->bindParam(':idC', $this->idCom, PDO::PARAM_INT);
            $request->bindParam(':idP', $this->idPro, PDO::PARAM_INT);
            $request->bindParam(':cPC', $this->cantidadProCom, PDO::PARAM_INT);
            $request->bindParam(':est', $this->estado);
            $request->bindParam(':usC', $this->usuarioCreacion, PDO::PARAM_INT);
            $request->bindParam(':usM', $this->usuarioModificacion, PDO::PARAM_INT);
            $request->execute();
    
            $request = $this->con->getCon()->prepare("UPDATE productos SET cantidadPro = cantidadPro - :cPC WHERE id = :idP");
            $request->bindParam(':cPC', $this->cantidadProCom, PDO::PARAM_INT);
            $request->bindParam(':idP', $this->idPro, PDO::PARAM_INT);
            $request->execute();
    
            $this->con->getCon()->commit();
            
            return "CompraProducto Creada";
        } catch (PDOException $e) {
            $this->con->getCon()->rollBack();
            return "Error al crear el CompraProducto: " . $e->getMessage();
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


    /**
     * Get the value of cantidadProCom
     */
    public function getCantidadProCom()
    {
        return $this->cantidadProCom;
    }

    /**
     * Set the value of cantidadProCom
     */
    public function setCantidadProCom($cantidadProCom): self
    {
        $this->cantidadProCom = $cantidadProCom;

        return $this;
    }

    /**
     * Get the value of idPro
     */
    public function getIdPro()
    {
        return $this->idPro;
    }

    /**
     * Set the value of idPro
     */
    public function setIdPro($idPro): self
    {
        $this->idPro = $idPro;

        return $this;
    }

    /**
     * Get the value of idCom
     */
    public function getIdCom()
    {
        return $this->idCom;
    }

    /**
     * Set the value of idCom
     */
    public function setIdCom($idCom): self
    {
        $this->idCom = $idCom;

        return $this;
    }
}