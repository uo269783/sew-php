<?php
session_start();

class Aplicacion {

    protected $apikey = "?access_key=9ikon99d7g92z9h596ln8ei3o17q996wlt2iwif4bs79glv9pi3ei675o37n";
    protected $url = "https://metals-api.com/api/latest";
    protected $base = "&base=XCU";
    protected $symbols = "&symbols=EUR,USD,GBP";
    protected $gramos = 28.3495;
    protected $pruebas = '{"success":true,"timestamp":1671102000,"date":"2022-12-15","base":"XCU","rates":{"EUR":0.224865952131692,"GBP":0.19357703437499896,"USD":0.23871874999999873},"unit":"per ounce"}';
    public $euros;
    public $dolares;
    public $libras;

    public function __construct() {
        $this->euros = 0.0;
        $this->dolares = 0.0;
        $this->libras = 0.0;
    }

    public function cargar() {

        //$resultado = file_get_contents($this->url + $this->apikey + $this->base + $this->symbols);
        $json = json_decode($this->pruebas);
        
        $this->euros = round($json->rates->EUR, 2);
        $this->dolares = round($json->rates->USD,2);
        $this->libras = round($json->rates->GBP, 2);
        
        
    }

    public function crearParrafo($val, $moneda) {
        $texto = "<h2>".$moneda."</h2>";
        $texto .= "<p>Onza: ".$val."</p>";
        $texto .= "<p>Gramo: ".($val / $this->gramos)."</p>";
        $texto .= "<p>Kilogramo: ".($val / $this->gramos * 1000)."</p>";
        return $texto;
    }
}

$app = new Aplicacion();

$app->cargar();

echo "
<!DOCTYPE html>
<html lang='es'>
<head>
<meta name='author' content='Daniel Fernández Bernardino' />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>Práctica 4 - Ejercicio 4</title>
<link rel='stylesheet' type='text/css' href='Ejercicio4.css' />
</head>
<body>
<h1>Precio del cobre</h1>
<p>En este documento se verá el precio del cobre para varias monedas y unidades de peso</p>
<main>
",
$app->crearParrafo($app->euros, "Euros"),
$app->crearParrafo($app->dolares, "Dólares americanos"),
$app->crearParrafo($app->libras, "Libras esterlinas"),
"
</main>
</body>
</html>"
?>