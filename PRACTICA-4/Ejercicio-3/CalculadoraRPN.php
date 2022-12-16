<!DOCTYPE HTML>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="author" content="Daniel Fernández Bernardino" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Calculadora RPN</title>
    <link rel="stylesheet" type="text/css" href="CalculadoraRPN.css" />
    
</head>
<?php
session_start();

if(!isset($_SESSION['rpn'])) {
    $_SESSION['rpn'] = new CalculadoraRPN();
    $rpn = $_SESSION['rpn'];
} else {
    $rpn = $_SESSION['rpn'];
}

class CalculadoraRPN {

    protected $pila;
    protected $valor;
    protected $editable;

    public function __construct() {
        $this->pila = [];
        $this->valor = "0";
        $this->editable = false;
    }

    public function mostrarPila() {
        $texto = "";
        foreach($this->pila as $valor) {
            $texto.=$valor."\n";
        }

        return $texto;
    }

    public function mostrarValor() {
        return $this->valor;
    }

    public function digitos($valor) {
        if ($this->valor == "0" || $this->editable) {
            $this->valor = "" . $valor;
            $this->editable = false;
        }
        else
            $this->valor .= "" . $valor;
    
    }

    public function enter() {
        $check = array_pop($this->pila);

        if($check!=null && !is_nan($check) && is_finite($check) || $check == 0) {
            array_push($this->pila, $check);
        }

        array_push($this->pila,floatval($this->valor));
        $this->valor = "0";
    }

    public function basica($operador) {

        $val2 = array_pop($this->pila);
        $val1 = array_pop($this->pila);
        
        if (is_nan($val1) || $val1 == null || !is_finite($val1))
            $val1 = 0;
        if (is_nan($val2) || $val2 == null || !is_finite($val2))
            $val2 = 0;

        switch ($operador) {
            case '+':
                array_push($this->pila, $val1 + $val2);
                break;
            case '-':
                array_push($this->pila, $val1 - $val2);
                break;
            case '*':
                array_push($this->pila, $val1 * $val2);
                break;
            case '/':
                array_push($this->pila, $val1 / $val2);
                break;
        }
    }

    public function punto() {
        if (strpos($this->valor, ".")===false) {
            $this->valor += ".";
        }

        if ($this->editable)
            $this->editable = false;

    }

    public function sumar() {
        $this->basica("+");
    }

    public function restar() {
        $this->basica("-");
    }

    public function dividir() {
        $this->basica("/");
    }

    public function multiplicar() {
        $this->basica("*");
    }

    public function sin() {
        $this->trigonometrica("sin");
    }

    public function arcsin() {
        $this->trigonometrica("arcsin");
    }

    public function cos() {
        $this->trigonometrica("cos");
    }

    public function arccos() {
        $this->trigonometrica("arccos");
    }

    public function tan() {
        $this->trigonometrica("tan");
    }

    public function arctan() {
        $this->trigonometrica("arctan");
    }

    public function borrar() {
        $this->valor = "0";
    }

    public function borrarPila() {
        $this->pila = [];
        $this->valor = "0";
    }

    public function trigonometrica($operador) {
        $val = array_pop($this->pila);

        if (!is_nan($val) || $val != null)
            switch ($operador) {
                case "sin":
                    array_push($this->pila, sin($val));
                    break;
                case "cos":
                    array_push($this->pila, cos($val));
                    break;
                case "tan":
                    array_push($this->pila, tan($val));
                    break;
                case "arcsin":
                    array_push($this->pila, asin($val));
                    break;
                case "arccos":
                    array_push($this->pila, acos($val));
                    break;
                case "arctan":
                    array_push($this->pila, atan($val));
                    break;
            }
        $this->editable = true;
    }

    public function masMenos() {
        $val = array_pop($this->pila);
        array_push($this->pila, -1*$val);
        
    }

    public function raiz() {
        $val = array_pop($this->pila);
        array_push($this->pila,sqrt($val));
        
    }


}

if(count($_POST)>0) {
    if(isset($_POST['CE'])) {
        $rpn->borrar();
    }
    if(isset($_POST['C'])) {
        $rpn->borrarPila();
    }
    if(isset($_POST['√'])) {
        $rpn->raiz();
    }
    if(isset($_POST['7'])) {
        $rpn->digitos('7');
    }
    if(isset($_POST['8'])) {
        $rpn->digitos('8');
    }
    if(isset($_POST['9'])) {
        $rpn->digitos('9');
    }
    if(isset($_POST['x'])) {
        $rpn->multiplicar();
    }
    if(isset($_POST['/'])) {
        $rpn->dividir();
    }
    if(isset($_POST['4'])) {
        $rpn->digitos('4');
    }
    if(isset($_POST['5'])) {
        $rpn->digitos('5');
    }
    if(isset($_POST['6'])) {
        $rpn->digitos('6');
    }
    if(isset($_POST['-'])) {
        $rpn->restar();
    }
    if(isset($_POST['1'])) {
        $rpn->digitos('1');
    }
    if(isset($_POST['2'])) {
        $rpn->digitos('2');
    }
    if(isset($_POST['3'])) {
        $rpn->digitos('3');
    }
    if(isset($_POST['+'])) {
        $rpn->sumar();
    }
    if(isset($_POST['0'])) {
        $rpn->digitos('0');
    }
    if(isset($_POST['punto'])) {
        $rpn->punto();
    }
    if(isset($_POST['enter'])) {
        $rpn->enter();
    }
    if(isset($_POST['sin'])) {
        $rpn->trigonometrica('sin');
    }
    if(isset($_POST['cos'])) {
        $rpn->trigonometrica('cos');
    }
    if(isset($_POST['tan'])) {
        $rpn->trigonometrica('tan');
    }
    if(isset($_POST['sin-1'])) {
        $rpn->trigonometrica('arcsin');
    }
    if(isset($_POST['cos-1'])) {
        $rpn->trigonometrica('arccos');
    }
    if(isset($_POST['tan-1'])) {
        $rpn->trigonometrica('arctan');
    }
    if(isset($_POST['+/-'])) {
        $rpn->masMenos('sin');
    }
           
}

echo "<body>
    <header>
        <h1>Calculadora RPN</h1>
        <p>Daniel Fernández Bernardino, Software y Estándares para la Web</p>
    </header>

    <form action='#' method='post'>
        <h2>RPN</h2>
        <label for='pila'>Pila</label>
        <textarea id='pila' readonly>",$rpn->mostrarPila(),"</textarea>
        <label for='valor'>Valor</label>
        <input id='valor' type='text' readonly value='", $rpn->mostrarValor(),"' />

        <p>
            <input type='submit' value='CE' name='CE' />
            <input type='submit' value='C' name='C' />
        </p>

        <input type='submit' value='7' name='7' />
        <input type='submit' value='8' name='8' />
        <input type='submit' value='9' name='9' />
        <input type='submit' value='x' name='x' />
        <input type='submit' value='/' name='/' />
        <input type='submit' value='√' name='√' />

        <input type='submit' value='4' name='4' />
        <input type='submit' value='5' name='5' />
        <input type='submit' value='6' name='6' />
        <input type='submit' value='-' name='-' />
        <input type='submit' value='sin' name='sin' />
        <input type='submit' value='sin-1' name='sin-1' />

        <input type='submit' value='1' name='1' />
        <input type='submit' value='2' name='2' />
        <input type='submit' value='3' name='3' />
        <input type='submit' value='+' name='+' />
        <input type='submit' value='cos' name='cos' />
        <input type='submit' value='cos-1' name='cos-1' />

        <input type='submit' value='.' name='.' />
        <input type='submit' value='0' name='0' />
        <input type='submit' value='enter' name='enter' />
        <input type='submit' value='+/-' name='+/-' />
        <input type='submit' value='tan' name='tan' />
        <input type='submit' value='tan-1' name='tan-1' />

    </form>

</body>

</html>"

?>