<!DOCTYPE HTML>
<html lang='es'>

<head>
    <meta charset='UTF-8' />
    <meta name='author' content='Daniel Fernández Bernardino' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <title>Calculadora Milan</title>
    <link rel='stylesheet' type='text/css' href='CalculadoraMilan.css' />
</head>

<?php 
    session_start();

    if(!isset($_SESSION['calculadora'])) {
        $_SESSION['calculadora'] = new Calculadora();
        $calculadora = $_SESSION['calculadora'];
    } else {
        $calculadora = $_SESSION['calculadora'];
    }

    class Calculadora {

        protected $anterior;
        protected $operador;
        protected $editable;
        protected $valor;
        protected $memoria;
        protected $resultado;

        public function __construct() {
            $this->anterior = null;
            $this->operador = null;
            $this->editable = false;
            $this->valor = "0";
            $this->memoria = 0;
    
        }
    
        
        public function mostrarTexto() {
            return $this->valor;
        }
    
        public function digitos($value) {
    
            if ($this->valor == "0" || $this->editable==true) {
                $this->valor = $value;
                $this->editable = false;
            }
            else {
                $this->valor .= $value;
            }
        }
    
        public function punto() {
    
            if (strpos($this->valor, ".") ===false) {
                    $this->valor .= ".";
                }
    
            $this->editable = false;
            
        }
    
        public function borrarTodo() {
            $this->valor = "0";
            $this->anterior = null;
            $this->editable = true;
            $this->operador = null;
            
        }
    
        public function borrar() {
            $this->valor = "0";
            $this->editable = true;
            
        }
    
        public function basica($operador) {
    
            //no hay numero anterior
            if ($this->anterior == null || $this->editable) {
                $this->anterior = $this->valor;
                $this->valor = "0";
                $this->operador = $operador;
            }
    
            //hay numero anterior
            else {
                $eval = $this->anterior . $this->operador . $this->valor;
                $this->valor = "" . eval("return ".$eval.";");
                $this->anterior = null;
                $this->editable = true;
                $this->operador = null;
            }
    
        }
    
        public function sumar() {
            $this->basica("+");
        }
    
        public function restar() {
            $this->basica("-");
        }
    
        public function multiplicar() {
            $this->basica("*");
        }
    
        public function dividir() {
            $this->basica("/");
        }
    
        public function raiz() {
            $eval = eval("return ".$this->valor.";");
            if ($eval > 0) {
                
                $this->valor = "".sqrt($eval);
                $this->editable = true;
            }
            
        }
    
        public function igual() {
            if ($this->anterior == null) {
                $this->valor = "". $this->valor;
            }
            else {
                $eval = $this->anterior . $this->operador . $this->valor;
                $this->valor = "". eval("return ".$eval.";");
                $this->editable = true;
                $this->anterior = null;
                $this->operador = null;
            }
    
            
        }
    
        public function porcentaje() {
            if ($this->anterior == null) { //si no hay anterior, simplemente se pone a 0
                $this->valor = "0";
            }
            else {
                $percent = $this->valor;
                $eval = $this->anterior * $percent / 100;
                $this->valor = "" . eval("return ".$eval.";");
                $this->editable = true;
            }
    
            
        }
    
        public function masMenos() {
            $eval = eval("return ".$this->valor."*-1;");
            $this->valor = "".$eval;
            
        }
    
        public function mrc() {
            $this->valor = "".$this->memoria;
            $this->editable = true;
            
        }
    
        public function mmas() {
            $this->memoria += eval("return ".$this->valor.";");
            $this->editable = true;
        }
    
        public function mmenos() {
            $this->memoria -= eval("return ".$this->valor.";");
            $this->editable = true;
        }
    }

    

    if(count($_POST)>0) {
        if(isset($_POST['CE'])) {
            $calculadora->borrar();
        }
        if(isset($_POST['C'])) {
            $calculadora->borrarTodo();
        }
        if(isset($_POST['+/-'])) {
            $calculadora->masMenos();
        }
        if(isset($_POST['√'])) {
            $calculadora->raiz();
        }
        if(isset($_POST['%'])) {
            $calculadora->porcentaje();
        }
        if(isset($_POST['7'])) {
            $calculadora->digitos('7');
        }
        if(isset($_POST['8'])) {
            $calculadora->digitos('8');
        }
        if(isset($_POST['9'])) {
            $calculadora->digitos('9');
        }
        if(isset($_POST['x'])) {
            $calculadora->multiplicar();
        }
        if(isset($_POST['/'])) {
            $calculadora->dividir();
        }
        if(isset($_POST['4'])) {
            $calculadora->digitos('4');
        }
        if(isset($_POST['5'])) {
            $calculadora->digitos('5');
        }
        if(isset($_POST['6'])) {
            $calculadora->digitos('6');
        }
        if(isset($_POST['-'])) {
            $calculadora->digitos();
        }
        if(isset($_POST['MRC'])) {
            $calculadora->mrc();
        }
        if(isset($_POST['1'])) {
            $calculadora->digitos('1');
        }
        if(isset($_POST['2'])) {
            $calculadora->digitos('2');
        }
        if(isset($_POST['3'])) {
            $calculadora->digitos('3');
        }
        if(isset($_POST['+'])) {
            $calculadora->sumar();
        }
        if(isset($_POST['M-'])) {
            $calculadora->mmenos();
        }
        if(isset($_POST['0'])) {
            $calculadora->digitos('0');
        }
        if(isset($_POST['punto'])) {
            $calculadora->punto();
        }
        if(isset($_POST['='])) {
            $calculadora->igual();
        }
        if(isset($_POST['M+'])) {
            $calculadora->mmas();
        }        
    }
    
    

    echo "<body>
    <header>
        <h1>Calculadora Milan</h1>
        <p>Daniel Fernández Bernardino, Software y Estándares para la Web</p>
    </header>

    <form action='#' method='post'>


        <label for='resultado'>nata by Milan</label>
        <input id='resultado' type='text' readonly value=",$calculadora->mostrarTexto()," />



        <input type='submit' value='CE' name='CE'/>
        <input type='submit' value='C' name='C'/>
        <input type='submit' value='+/-' name='+/-'/>
        <input type='submit' value='√' name='√'/>
        <input type='submit' value='%' name='%'/>

        <input type='submit' value='7' name='7'/>
        <input type='submit' value='8' name='8'/>
        <input type='submit' value='9' name='9'/>
        <input type='submit' value='x' name='x' />
        <input type='submit' value='/'  name='/'/>

        <input type='submit' value='4' name='4'/>
        <input type='submit' value='5' name='5'/>
        <input type='submit' value='6' name='6'/>
        <input type='submit' value='-' name='-'/>
        <input type='submit' value='MRC' name='MRC'/>

        <input type='submit' value='1' name='1'/>
        <input type='submit' value='2' name='2'/>
        <input type='submit' value='3' name='3'/>
        <input type='submit' value='+' name='+'/>
        <input type='submit' value='M-' name='M-'/>

        <input type='submit' value='0' name='0'/>
        <input type='submit' value='.' name='punto'/>
        <input type='submit' value='=' name='='/>
        <input type='submit' value='M+' name='M+'/>

    </form>

</body>

</html>"
    ?>