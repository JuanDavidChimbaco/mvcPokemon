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
            $request = $this->con->getCon()->prepare("INSERT INTO comprod (idCom,idPro,cantidadProCom,usuarioCreacion,usuarioModificacion) VALUES(:idC, :idP, :cant, :userC, :userM)");
            $request->bindParam(':idC',$this->idCom,PDO::PARAM_INT);
            $request->bindParam(':idP',$this->idPro,PDO::PARAM_INT);
            $request->bindParam(':cant',$this->cantidadProCom,PDO::PARAM_INT);
            $request->bindParam(':useC',$this->usuarioCreacion,PDO::PARAM_INT);
            $request->bindParam(':userM',$this->usuarioModificacion,PDO::PARAM_INT);
            $request->execute();
            return  "Compra Registrada";
        } catch (PDOException $e) {
            return "Error al Insertar CompraProducto ".$e->getMessage();
        }
    }

    public function read(){
        try {
            //code...
            $request = $this->con->getCon()->prepare("SELECT p.*, c.nombreCat AS categoria
            FROM productos AS p
            JOIN categorias AS c ON p.categoria = c.id");
            $request->execute();
            $result = $request->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            //Except $e;
            return "Error Al consultar productos ". $e->getMessage();
        }
    }

    public function readID(){
        try {
            # code...
            $request = $this->con->getCon()->prepare("SELECT p.*, c.nombreCat AS categoriaP
            FROM productos AS p
            JOIN categorias AS c ON p.categoria = c.id
            WHERE p.id = :id ;");
            $request->bindParam(':id',$this->id,\PDO::PARAM_INT);
            $request->execute();
            $result = $request->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            # code...
            return "Error Al Traer el producto". $e->getMessage();
        }
    }

    // public function readCat(){
    //     try {
    //         # code...
    //         $request = $this->con->getCon()->prepare("SELECT p.*, c.nombreCat AS categoria
    //         FROM productos AS p
    //         INNER JOIN categorias AS c ON p.categoria = c.id AND categoria = :c;");
    //         $request->bindParam(':c',$this->categoria,\PDO::PARAM_INT);
    //         $request->execute();
    //         $result = $request->fetchAll(\PDO::FETCH_ASSOC);
    //         return $result;
    //     } catch (PDOException $e) {
    //         # code...
    //         return "Error Al Traer el producto". $e->getMessage();
    //     }
    // }
    // public function readPage(){
    //     try {
    //         # code...
    //         $request = $this->con->getCon()->prepare("SELECT p.*, c.nombreCat AS categoria
    //         FROM productos AS p
    //         JOIN categorias AS c ON p.categoria = c.id LIMIT :limits OFFSET :offset");
    //         $request->bindParam(':limits',$this->limit,\PDO::PARAM_INT);
    //         $request->bindParam(':offset',$this->offset,\PDO::PARAM_INT);
    //         $request->execute();
    //         $result = $request->fetchAll(\PDO::FETCH_ASSOC);
    //         return $result;
    //     } catch (PDOException $e) {
    //         # code...
    //         return "Error al paginar producto". $e->getMessage();
    //     }
    // }
    // public function update(){
    //     try {
    //         //code...
    //         $request = $this->con->getCon()->prepare("UPDATE productos SET `nombrePro`=:nombreRol,`precioPro`=:precioPro,`cantidadPro`=:cantidadPro,`categoria`=:categoria, `urlFoto`=:urlF, `fechaModificacion`=:fm  WHERE  `id`= :id ;");
    //         $request->bindParam(':id',$this->id);
    //         $request->bindParam(':nombreRol',$this->nombrePro);
    //         $request->bindParam(':precioPro',$this->precioPro);
    //         $request->bindParam(':cantidadPro',$this->cantidadPro);
    //         $request->bindParam(':categoria',$this->categoria);
    //         $request->bindParam(':fm',$this->fechaModificacion);
    //         $request->bindParam(':urlF',$this->urlFoto);
    //         $request->execute();
    //         return "Rol Actualizado";
    //     } catch (PDOException $e) {
    //         //Except $e;
    //         return "Error al actualizar Rol ".$e->getMessage();
    //     }
    // }
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
            $request = $this->con->getCon()->prepare("UPDATE productos SET `estado`= ? WHERE id = ?");
            $request->bindParam(1,$this->estado);
            $request->bindParam(2,$this->id);
            $request->execute();
            return "Estado Modificado";
        } catch (PDOException $e) {
            return "Error".$e->getMessage();
        }
    }
    public function contador(){
        try {
            $request = $this->con->getCon()->prepare("SELECT COUNT(*) AS total FROM productos");
            $request->execute();
            $result = $request->fetch(\PDO::FETCH_ASSOC);
            return $result;
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