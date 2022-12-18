<?php
session_start();

if(!isset($_SESSION['e7']))
{
    $_SESSION['e7']= new Ejercicio7();
    $bd= $_SESSION['e7'];
}
else{
    $bd= $_SESSION['e7'];
}


class Ejercicio7 {

    protected $server = "localhost";
    protected $user = "DBUSER2022";
    protected $pass = "DBPSWD2022";
    protected $nombre = "ejercicio7";

    public function __construct() {

    }

    public function createDataBase() {
        $bd = new mysqli($this->server, $this->user, $this->pass);
    
        $query = "CREATE DATABASE IF NOT EXISTS ejercicio7 COLLATE utf8_spanish_ci";
    
        if($bd->query($query) === true) {
            echo "<p>Se ha creado la base de datos correctamente</p>";
        } else {
            echo "<p>Se ha producido un error en la creación de la base de datos</p>";
            exit();
        }
    
        $bd->close();
    }

    public function createTables() {
        $bd = new mysqli($this->server, $this->user, $this->pass, $this->nombre);
        $mesas = "CREATE TABLE IF NOT EXISTS Mesas (
            id int not null,
            num int not null,
            maxOcupantes int not null,
            disponible boolean,
            primary key (id),
            constraint uq_num_mesa unique(num)
            )";

        $reservas = "CREATE TABLE IF NOT EXISTS Reservas (
            id int not null,
            idMesa int not null,
            hora int not null,
            ocupantes int not null,
            primary key(id),
            foreign key(idMesa) references Mesas(id),
            constraint ck_hora check (hora between 12 and 23)
            )";

        $trabajadores = "CREATE TABLE IF NOT EXISTS Trabajadores (
            id int not null,
            nombre varchar(60) not null,
            puesto varchar(60) not null,
            turno int not null,
            primary key (id),
            constraint ck_turno check (turno = 1 or turno = 0)
            )";

        $platos = "CREATE TABLE IF NOT EXISTS Platos (
            id int not null,
            nombre varchar(60) not null,
            precio decimal(4,2) not null,
            disponible boolean,
            primary key (id)
            )";

        $pedidos = "CREATE TABLE IF NOT EXISTS Pedidos (
            id int not null,
            idMesa int not null,
            idPlato int not null,
            primary key(id),
            foreign key (idMesa) references Mesas(id),
            foreign key (idPlato) references Platos(id)
            )";

        $bd->query($mesas);
        $bd->query($reservas);
        $bd->query($trabajadores);
        $bd->query($platos);
        $bd->query($pedidos);
        
        $bd->close();
    }

    public function maxComensales() {
        $bd = new mysqli($this->server, $this->user, $this->pass, $this->nombre);

        $query = "SELECT MAX(maxOcupantes) from Mesas where disponible=true";
        $max = $bd->query($query)->fetch_row()[0];

        $bd->close();
        return $max;
    }

    public function hacerReserva() {
        $bd = new mysqli($this->server, $this->user, $this->pass, $this->nombre);

        $mesa = $bd->prepare("SELECT id from Mesas where maxOcupantes<=? and disponible=true");
        $mesa->bind_param("s", $_POST['comensales']);
        $mesa->execute();
        $idMesa = $mesa->get_result()->fetch_row()[0];

        $query = $bd->prepare("INSERT INTO Reservas values(?,?,?,?)");
        $query->bind_param('iiii', rand(), $idMesa, $_POST['hora'], $_POST['comensales']);

        $query->execute();

        $query->close();
        $mesa->close();
        $bd->close();
    }

    public function verMesas() {
        $bd = new mysqli($this->server, $this->user, $this->pass, $this->nombre);
        $query = $bd->query("SELECT * FROM Mesas where disponible=true");

        $texto = "<ul>";

        $fila = $query->fetch_assoc();
        if($fila!=null) {
            while($fila!=null) {
                $texto.="<li>Número de mesa: ".$fila['num'].", máximo de ocupantes: ".$fila['maxOcupantes']."</li>";
                $fila = $query->fetch_assoc();
            }
        }
         else {
            $texto.="<li>No se han encontrado mesas</li></ul>";
        }
        $texto.="</ul>";
        $bd->close();
        return $texto;

    }

    public function verTrabajadores() {
        $bd = new mysqli($this->server, $this->user, $this->pass, $this->nombre);
        $query = $bd->query("SELECT * FROM Trabajadores");

        $texto = "<ul>";

        $fila = $query->fetch_assoc();
        if($fila!=null) {
            while($fila!=null) {
                $texto.="<li>Nombre: ".$fila['nombre'].", puesto: ".$fila['puesto'].", turno: ".$fila['turno']."</li>";
                $fila = $query->fetch_assoc();
            }
        }
         else {
            $texto.="<li>No se han encontrado trabajadores</li>";
        }
        $texto.="</ul>";
        $bd->close();
        return $texto;
    }

    public function verCarta() {
        $bd = new mysqli($this->server, $this->user, $this->pass, $this->nombre);
        $query = $bd->query("SELECT * FROM Platos");

        $texto = "<ul>";

        $fila = $query->fetch_assoc();
        if($fila!=null) {
            while($fila!=null) {
                $disp = "no";
                if($fila["disponible"] === true) {
                    $disp = "sí";
                }
                $texto.="<li>Nombre: ".$fila['nombre'].", disponible: ".$disp.", precio: ".$fila['precio']."</li>";
                $fila = $query->fetch_assoc();
            }
        }
         else {
            $texto.="<li>No se han encontrado platos</li>";
        }
        $texto.="</ul>";
        $bd->close();
        return $texto;
    }

    public function cargarDatos() {
        $bd = new mysqli($this->server, $this->user, $this->pass, $this->nombre);

        $file = fopen("mesas.csv", "r");
        $fila = fgetcsv($file, 0, ";");
        
        while($fila!=null) {
            $query = $bd->prepare("INSERT INTO Mesas values (?,?,?,?)");
            $query->bind_param("iiii", $fila[0], $fila[1], $fila[2], $fila[3]);
            $query->execute();
            $query->close();
            $fila = fgetcsv($file, 0, ";");
        }

        fclose($file);

        $file = fopen("reservas.csv", "r");
        $fila = fgetcsv($file, 0, ";");
        while($fila!=null) {
            $query = $bd->prepare("INSERT INTO Reservas  values (?,?,?,?)");
            $query->bind_param("iiii", $fila[0], $fila[1], $fila[2], $fila[3]);
            $query->execute();
            $query->close();
            $fila = fgetcsv($file, 0, ";");
        }

        fclose($file);

        $file = fopen("trabajadores.csv", "r");
        $fila = fgetcsv($file, 0, ";");
        while($fila!=null) {
            $query = $bd->prepare("INSERT INTO Trabajadores  values (?,?,?,?)");
            $query->bind_param("issi", $fila[0], $fila[1], $fila[2], $fila[3]);
            $query->execute();
            $query->close();
            $fila = fgetcsv($file, 0, ";");
        }

        fclose($file);

        $file = fopen("platos.csv", "r");
        $fila = fgetcsv($file, 0, ";");
        while($fila!=null) {
            $query = $bd->prepare("INSERT INTO Platos values (?,?,?,?)");
            $query->bind_param("isii", $fila[0], $fila[1], $fila[2], $fila[3]);
            $query->execute();
            $query->close();
            $fila = fgetcsv($file, 0, ";");
        }

        fclose($file);

        $file = fopen("pedidos.csv", "r");
        $fila = fgetcsv($file, 0, ";");
        while($fila!=null) {
            $query = $bd->prepare("INSERT INTO Pedidos  values (?,?,?)");
            $query->bind_param("iii", $fila[0], $fila[1], $fila[2]);
            $query->execute();
            $query->close();
            $fila = fgetcsv($file, 0, ";");
        }

        fclose($file);

        $bd->close();
    }

}

if(count($_POST)>0){
    if(isset($_POST['crearBase'])) {
        $bd->createDataBase();
    }
    if(isset($_POST['crearTabla'])) {
        $bd->createTables();
    }
    if(isset($_POST['crearReserva'])) {
        $bd->hacerReserva();
    }
    if(isset($_POST['cargarDatos'])) {
        $bd->cargarDatos();
    }
}


?>