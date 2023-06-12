<?php

namespace Model;

use PDOException;

include_once "conexion.php";

class Usuario{
    private $id;
    private $tipoDocumento;
    private $identificacion;
    private $nombre;
    private $apellido;
    private $correo;
    private $pass;
    private $direccion;
    private $telefono;
    private $genero;
    private $idRol;
    private $estado = 'A';
    public $con; //* Objeto conexion

    public function __construct(){
        $this->con = new \Conexion;
    }

    public function login(){
        try {
            # code...
            $sql = $this->con->getCon()->prepare('SELECT * FROM usuarios WHERE correo=? AND pass=?');
            $sql->bindParam(1,$this->correo);
            $sql->bindParam(2,$this->pass);
            $sql->execute();
            $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            # code...
            return "Error: ".$e->getMessage();
        }
    }

    public function create(){
        try {
            # code...
            $request = $this->con->getCon()->prepare("INSERT INTO usuarios(tipoDoc,identificacion,nombre,apellido,correo,pass,direccion,telefono,genero,idRol,estado) VALUES(:td,:id,:n,:a,:c,:p,:d,:t,:g,:idRol,:e)");
            $request->bindParam(':td',$this->tipoDocumento);
            $request->bindParam(':id',$this->identificacion);
            $request->bindParam(':n',$this->nombre);
            $request->bindParam(':a',$this->apellido);
            $request->bindParam(':c',$this->correo);
            $request->bindParam(':p',$this->pass);
            $request->bindParam(':d',$this->direccion);
            $request->bindParam(':t',$this->telefono);
            $request->bindParam(':g',$this->genero);
            $request->bindParam(':idRol',$this->idRol);
            $request->bindParam(':e',$this->estado);
            $request->execute();
            return  "Usuario Creado";
        } catch (PDOException $e) {
            # code...
            return "Error al crear usuario ".$e->getMessage();
        }
    }

    public function read(){
        try {
            //code...
            $request = $this->con->getCon()->prepare("SELECT * FROM usuarios");
            $request->execute();
            $result = $request->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            //Except $e;
            return "Error Al consultar usuarios ". $e->getMessage();
        }
    }

    public function readID(){
        try {
            # code...
            $request = $this->con->getCon()->prepare("SELECT * FROM usuarios WHERE id = :id");
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
            $request = $this->con->getCon()->prepare("DELETE FROM usuarios WHERE id =:id");
            $request->bindParam(':id',$this->id,\PDO::PARAM_INT);
            $request->execute();
            return "Usuario Eliminado";
        } catch (PDOException $e) {
            # code...
            return "Error al Eliminar Usuario ".$e->getMessage();
        }
    }

    public function estado(){
        try {
            //code...
            $request = $this->con->getCon()->prepare("UPDATE usuarios SET `estado`= ? WHERE id = ?");
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
     * Get the value of tipoDocumento
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Set the value of tipoDocumento
     */
    public function setTipoDocumento($tipoDocumento): self
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    /**
     * Get the value of identificacion
     */
    public function getIdentificacion()
    {
        return $this->identificacion;
    }

    /**
     * Set the value of identificacion
     */
    public function setIdentificacion($identificacion): self
    {
        $this->identificacion = $identificacion;

        return $this;
    }

    /**
     * Get the value of nombre
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     */
    public function setNombre($nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of apellido
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set the value of apellido
     */
    public function setApellido($apellido): self
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get the value of correo
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set the value of correo
     */
    public function setCorreo($correo): self
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->pass;
    }

    /**
     * Set the value of password
     */
    public function setPassword($password): self
    {
        $this->pass = $password;

        return $this;
    }

    /**
     * Get the value of direccion
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set the value of direccion
     */
    public function setDireccion($direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get the value of telefono
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set the value of telefono
     */
    public function setTelefono($telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get the value of genero
     */
    public function getGenero()
    {
        return $this->genero;
    }

    /**
     * Set the value of genero
     */
    public function setGenero($genero): self
    {
        $this->genero = $genero;

        return $this;
    }

    /**
     * Get the value of idRol
     */
    public function getIdRol()
    {
        return $this->idRol;
    }

    /**
     * Set the value of idRol
     */
    public function setIdRol($idRol): self
    {
        $this->idRol = $idRol;

        return $this;
    }

    /**
     * Get the value of estadoRol
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set the value of estadoRol
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



?>