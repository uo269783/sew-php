<?php
session_start(); 

if(!isset($_SESSION['bd']))
{
    $_SESSION['bd']= new BaseDatos();
    $bd= $_SESSION['bd'];
}
else{
    $bd= $_SESSION['bd'];
}

class BaseDatos{

    protected $server = "localhost";
    protected $user = "DBUSER2022";
    protected $pass = "DBPSWD2022";
    protected $nombre;
    protected $resultado;
    

    public function __construct() {
        $this->resultado = "";
    }

    public function createDataBase() {
        $bd = new mysqli($this->server, $this->user, $this->pass);

        $query = "CREATE DATABASE IF NOT EXISTS ejercicio6 COLLATE utf8_spanish_ci";
        $this->nombre = "ejercicio6";

        if($bd->query($query) === true) {
            echo "<p>Se ha creado la base de datos correctamente</p>";
        } else {
            echo "<p>Se ha producido un error en la creación de la base de datos</p>";
            exit();
        }

        $bd->close();
    }

    public function createTable() {
        $bd = new mysqli($this->server, $this->user, $this->pass, $this->nombre);
        
        $query = "CREATE TABLE IF NOT EXISTS PruebasUsabilidad (
            id int not null,
            dni VARCHAR(9) not null,
            nombre varchar(20) not null,
            apellidos varchar(40) not null,
            email varchar(60) not null,
            telefono int(9) not null,
            edad int not null,
            sexo char(1) not null,
            nivelInformatica int not null,
            tiempo int not null,
            correcto boolean not null,
            comentarios varchar(255) not null,
            propuestas varchar(255) not null,
            valoracion int not null,
            primary key (id),
            constraint uq_dni unique (dni),
            constraint ck_edad check (edad>0),
            constraint ck_sexo check (sexo='M' or sexo='F'),
            constraint ck_nivel check (nivelInformatica>=0 and nivelInformatica <=10),
            constraint ck_tiempo check (tiempo>0),
            constraint ck_valoracion check (valoracion>=0 and valoracion <=10)
            )";

            $bd->query($query);

            $bd->close();
    }

    public function insert() {
        $bd = new mysqli($this->server, $this->user, $this->pass, $this->nombre);
        $id = rand();
        $query = $bd->prepare("INSERT INTO PruebasUsabilidad  values (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        
        $correcto = 0;

        if($_POST["correcto"] == "Sí") {
            $correcto = 1;
        }

        $sexo = 'M';

        if($_POST["sexo"] == "Femenino") {
            $sexo = 'F';
        }

        
        $query->bind_param("issssiisiiissi", $id, $_POST["dni"], $_POST["nombre"], $_POST["apellidos"], $_POST["email"], $_POST["telefono"], $_POST["edad"], $sexo, 
        $_POST["nivel"], $_POST["tiempo"], $correcto, $_POST["comentarios"], $_POST["propuestas"], $_POST["valoracion"]);

        $query->execute();
        $query->close();

        $bd->close();
    }

    public function select() {
        $bd = new mysqli($this->server, $this->user, $this->pass, $this->nombre);
        $this->resultado = "";
        $columna = $_POST["atributo"];
        $query = $bd->prepare("SELECT * FROM PruebasUsabilidad WHERE ".$columna."=?");
        $valor = $_POST["valor"];
        
        if(is_string($valor) == true) {
            $query->bind_param('s', $valor);
        }
        else {
            $query->bind_param('i', $valor);
        }

        $query->execute();
        $result = $query->get_result();

        $fila = $result->fetch_assoc(); 

        do {
            if($fila==null) {
                $texto = "<li>Ningún usuario con esos datos</li>";
            }

            else {
                $texto = "<li>DNI: ".$fila["dni"].", nombre: ".$fila["nombre"].", apellidos: ".$fila["apellidos"].
                ", email: ".$fila["email"].", nº de teléfono: ".$fila["telefono"].", edad: ".$fila["edad"].
                ", sexo: ".$fila["sexo"].", conocimiento informático: ".$fila["nivelInformatica"].", tiempo: ".$fila["tiempo"].
                ", hecho correctamente: ".$fila["correcto"].", comentarios: ".$fila["comentarios"].
                ", propuestas: ".$fila["propuestas"].", valoración: ".$fila["valoracion"]."</li>";

            $fila = $result->fetch_assoc();
            }

            $this->resultado.=$texto;

        } while($fila != null);

        $query->close();
        $bd->close();
    }

    public function escribirResultado() {
        return "<ul>".$this->resultado."</ul>";
    }

    public function update() {
        $bd = new mysqli($this->server, $this->user, $this->pass, $this->nombre);

        $columna = $_POST["atributo"];
        $query = $bd->prepare("UPDATE PruebasUsabilidad SET ".$columna."=? WHERE dni=?");
        $valor = $_POST["valor"];
        $dni = $_POST["filtro"];

        if(is_string($valor) == true) {
            $query->bind_param('ss', $valor, $dni);
        }
        else {
            $query->bind_param('is', $valor, $dni);
        }

        $query->execute();
        $query->close();
        $bd->close();
    }

    public function delete() {
        $bd = new mysqli($this->server, $this->user, $this->pass, $this->nombre);
        $query = $bd->prepare("DELETE from PruebasUsabilidad WHERE dni=?");
        
        $dni = $_POST["dni"];

        $query->bind_param('s',$dni);
        

        $query->execute();
        $query->close();
        $bd->close();
    }

    public function informe() {
        $bd = new mysqli($this->server, $this->user, $this->pass, $this->nombre);

        $edadMedia = $bd->query("SELECT avg(edad) from PruebasUsabilidad")->fetch_row()[0];
        $nUsuarios = $bd->query("SELECT count(*) from PruebasUsabilidad")->fetch_row()[0];
        $nFemeninos = $bd->query("SELECT count(*) from PruebasUsabilidad where sexo='F'")->fetch_row()[0];
        $mediaNivel = $bd->query("SELECT avg(nivelInformatica) from PruebasUsabilidad")->fetch_row()[0];
        $mediaTiempo= $bd->query("SELECT avg(tiempo) from PruebasUsabilidad")->fetch_row()[0];
        $nCorrectos = $bd->query("SELECT count(*) from PruebasUsabilidad where correcto=TRUE")->fetch_row()[0];
        $mediaValoracion = $bd->query("SELECT avg(valoracion) from PruebasUsabilidad")->fetch_row()[0];

        $femPercent = eval("return ".$nFemeninos."/".$nUsuarios."*100;");
        $mascPercent = 100-$femPercent;

        $percentAciertos = eval("return ".$nCorrectos."/".$nUsuarios."*100;");

        $texto = "<dl> <dt>Edad media</dt> <dd>".$edadMedia."</dd><dt><dt>% femenino</dt> <dd>".$femPercent."</dd>
        <dt>% masculino</dt> <dd>".$mascPercent."</dd><dt>Nivel medio</dt> <dd>".$mediaNivel."</dd><dt>Tiempo medio</dt> <dd>".$mediaTiempo."</dd>
        <dt>% correctos</dt> <dd>".$percentAciertos."</dd><dt>Valoración media</dt> <dd>".$mediaValoracion."</dd></dl>";

        $bd->close();

        return $texto;
    }

    public function export() {
        $bd = new mysqli($this->server, $this->user, $this->pass, $this->nombre);

        $datos = $bd->query("SELECT * FROM PruebasUsabilidad");

        $fila = $datos->fetch_assoc();
        
        if($fila!=null) {
            $file = fopen("pruebasUsabilidad.csv", "w");
            do {
                fputcsv($file, $fila, ";");
                $fila = $datos->fetch_assoc();
            } while($fila!=null);
        }
        fclose($file);
        $bd->close();
    }

    public function import() {
        $bd = new mysqli($this->server, $this->user, $this->pass, $this->nombre);

        $file = fopen("PruebasUsabilidad.csv", "r");
        $fila = fgetcsv($file, 0, ";");
        while( $fila!=null) {
            $query = $bd->prepare("INSERT INTO PruebasUsabilidad  values (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

            $query->bind_param("issssiisiiissi", $fila[0], $fila[1], $fila[2], $fila[3], $fila[4], $fila[5], $fila[6], $fila[7], 
            $fila[8], $fila[9], $fila[10], $fila[11], $fila[12], $fila[13]);

            $query->execute();
            $query->close();
            $fila = fgetcsv($file, 0, ";");
        }

        fclose($file);
        $bd->close();
    }
}



if(count($_POST)>0){
    if(isset($_POST['crearBase'])){
        $bd->createDataBase();
    }
    if(isset($_POST['crearTabla'])){
        $bd->createTable();
    }
    if(isset($_POST['insert'])){
        $bd->insert();
    }
    if(isset($_POST['select'])){
        $bd->select();
    }
    if(isset($_POST['update'])){
        $bd->update();
    }
    if(isset($_POST['delete'])){
        $bd->delete();
    }
}

echo "
"
?>