<?php 

class conexion
{
    private $host = 'localhost';
    private $user = 'root';
    private  $password = "";
    private $database = "pokemon33";
    private $con;

    public function __construct()
    {
        $dsn = "mysql:dbname=$this->database;host=$this->host";
        try {
            $this->con = new PDO($dsn, $this->user, $this->password);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo "Falla en la conexion" . $e -> getMessage();
        }
    }

	/**
	 * @return mixed
	 */
	public function getCon() {
		return $this->con;
	}
}


$conexion = new Conexion();


?>