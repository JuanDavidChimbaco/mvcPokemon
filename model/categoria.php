<?php

namespace Model;

use PDOException;

include_once "conexion.php";

class Categoria{

    private $id;
    private $nameCat;
    public $con; //* Objeto conexion

    public function __construct(){
        $this->con = new \Conexion();
    }

    public function read(){
        try {
            $request = $this->con->getCon()->prepare("SELECT * FROM categorias");
            $request->execute();
            $result = $request->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return "Error Al consultar roles ". $e->getMessage();
        }
    }

    public function readId(){
        try {
            $request = $this->con->getCon()->prepare("SELECT * FROM categorias WHERE id = :id");
            $request->bindParam(':id',$this->id,\PDO::PARAM_INT);
            $request->execute();
            $result = $request->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return "Error Al Traer el rol". $e->getMessage();
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
     * Get the value of nameCat
     */
    public function getNameCat()
    {
        return $this->nameCat;
    }

    /**
     * Set the value of nameCat
     */
    public function setNameCat($nameCat): self
    {
        $this->nameCat = $nameCat;

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