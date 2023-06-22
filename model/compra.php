<?php

namespace Model;

use PDO;
use PDOException;

include_once "conexion.php";

class Compra{
    private $id;
    private $codigoCom;
    private $fechaCom;
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
            $request = $this->con->getCon()->prepare("INSERT INTO compras (codigoCom, fechaCom, estado, usuarioCreacion, usuarioModificacion ) VALUES(:cod,:fec,:est,:usC,:usM)");
            $request->bindParam(':cod',$this->codigoCom);
            $request->bindParam(':fec',$this->fechaCom);
            $request->bindParam(':est',$this->estado);
            $request->bindParam(':usC',$this->usuarioCreacion,PDO::PARAM_INT);
            $request->bindParam(':usM',$this->usuarioModificacion,PDO::PARAM_INT);
            $request->execute();
            $ultimoId = $this->con->getCon()->lastInsertId();
            return $ultimoId;
        } catch (PDOException $e) {
            return "Error al crear la compra".$e->getMessage();
        }
    }
    public function read(){
        try {
            //code...
            $request = $this->con->getCon()->prepare("SELECT c.id, c.codigoCom, c.fechaCom, c.estado, p.nombrePro, p.urlFoto, p.precioPro, cp.cantidadProCom
            FROM compras c
            INNER JOIN comprod cp ON c.id = cp.idCom
            INNER JOIN productos p ON cp.idPro = p.id;");
            $request->execute();
            $result = $request->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            //Except $e;
            return "Error al consultar compras". $e->getMessage();
        }
    }
    public function estado(){
        try {
            $request = $this->con->getCon()->prepare("UPDATE compras SET `estado`= ? WHERE id = ?");
            $request->bindParam(1,$this->estado);
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
     * Get the value of codigoCom
     */
    public function getCodigoCom()
    {
        return $this->codigoCom;
    }

    /**
     * Set the value of codigoCom
     */
    public function setCodigoCom($codigoCom): self
    {
        $this->codigoCom = $codigoCom;

        return $this;
    }

    /**
     * Get the value of fechaCom
     */
    public function getFechaCom()
    {
        return $this->fechaCom;
    }

    /**
     * Set the value of fechaCom
     */
    public function setFechaCom($fechaCom): self
    {
        $this->fechaCom = $fechaCom;

        return $this;
    }
}