<!DOCTYPE HTML>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="author" content="Daniel Fernández Bernardino" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Calculadora Científica</title>
    <link rel="stylesheet" type="text/css" href="CalculadoraCientifica.css" />
</head>


<?php
session_start();

if(!isset($_SESSION['calculadoraCientifica'])) {
    $_SESSION['calculadoraCientifica'] = new CalculadoraCientifica();
    $calculadoraCientifica = $_SESSION['calculadoraCientifica'];
} else {
    $calculadoraCientifica = $_SESSION['calculadoraCientifica'];
}

class Calculadora {

    protected $anterior;
    protected $operador;
    protected $editable;
    protected $valor;
    protected $memoria;
    protected $resultado;
    protected $hyp;
    protected $fe;

    public function __construct() {
        $this->anterior = null;
        $this->operador = null;
        $this->editable = false;
        $this->valor = "0";
        $this->memoria = 0;
        $this->hyp = false;
        $this->fe = false;

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

class CalculadoraCientifica extends Calculadora {
    protected $formula;
    protected $grados;
    protected $hyp;
    protected $shiftPulsado;

    public function __construct() {
        parent::__construct();
        $this->formula = "";
        $this->grados = "deg";
        $this->hyp = false;
        $this->shiftPulsado = false;
    }

    public function basica($operador) {
        if ($this->anterior != null) { //solo se cambia el valor de anterior con el metodo potencia()
            $this->valor = "" . pow(eval("return ".$this->anterior.";"), eval("return ".$this->valor.";"));
            $this->anterior = null;
        }
        if ($this->formula != null && substr($this->formula, -1) == ')') {
            $this->formula .= $operador;
        }
        else {
            if (!$this->editable) {
                $this->formula .= $this->valor . $operador;
                $this->valor = "0";
            }

            else {
                $this->formula = $this->valor . $operador;
                $this->valor = "0";
                $this->editable = false;
            }
        }

    }

    public function digitos($value) {
        if ($this->valor == "0" || $this->editable) {
            $this->valor = "" . $value;

            if ($this->editable) {
                $this->formula = "";
            }

            $this->editable = false;
        }
        else
            $this->valor .= $value;

    }

    public function igual() {
        if ($this->anterior != null) { //solo se cambia el valor de anterior con el metodo potencia()
            $this->valor = "" . pow(eval("return ".$this->anterior.";"), eval("return ".$this->valor.";"));
            $this->anterior = null;
        }

        try {
            if ($this->valor == "0") {
                try {
                    $this->valor = eval("return ".$this->formula.";");
                    $this->editable = true;
                } catch (Exception $e) {
                    $this->formula .= $this->valor;
                    $this->valor = eval("return ".$this->formula.";");
                    $this->editable = true;
                }
            }
            else {

                if ($this->editable) {
                    if (!$this->ultimoCharEsNumero()) {
                        $this->formula .= $this->valor;
                        $this->valor = eval("return ".$this->formula.";");
                        $this->editable = true;
                    } else {
                        $this->formula = "" . $this->valor;
                        $this->editable = true;
                    }
                }
                else {
                    $this->formula .= $this->valor;
                    $this->valor = eval("return ".$this->formula.";");
                    $this->editable = true;
                }
            }
        } catch (Exception $e) {
            $this->valor = "Math Error";
            $this->editable = true;
        }

    }

    public function ultimoCharEsNumero() {
        $char = substr($this->formula,-1);

        for ($i = 0; $i <= 9; $i++) {
            if ($char == "" . $i) 
                return true;
        }

        return false;
    }

    public function borrarTodo() {
        $this->formula = "";
        $this->valor = "0";
    }

    public function modulo() {
        $this->basica("%");
    }

    public function borrar() {
        $this->valor = "0";
    }

    public function retroceder() {
        if (strlen($this->valor) > 0)
            $this->valor = substr($this->valor,0, -1);

        if ($this->valor == "")
            $this->valor = "0";
    }

    public function abreParentesis() {
        if ($this->editable) {
            $this->formula = "(";
            $this->editable = false;
        }
        else
            $this->formula .= "(";
        
    }

    public function cierraParentesis() {
        $this->formula .= $this->valor + ")";
        $this->valor = "0";
    }

    public function trigonometrica($operador) {
        $value;

        if (!$this->shiftPulsado) {
            if ($this->grados == "deg") {
                $this->valor = eval("return ".$this->valor.";") * pi() / 180;
            }
            else if ($this->grados == "grad") {
                $this->valor = eval("return ".$this->valor.";") * 63.662;
            }
        }

        switch ($operador) {
            case "sin":
                $value = $this->sin();
                break;
            case "cos":
                $value = $this->cos();
                break;
            case "tan":
                $value = $this->tan();
                break;
        }

        if ($this->shiftPulsado) {
            if ($this->grados == "deg") {
                $value = $value * (180 / pi());
            }
            else if ($this->grados == "grad") {
               $value = $value / 63.662;
            }
        }

        $this->editable = true;
        $this->valor = "" . $value;
    }

    public function sin() {
        $val = eval("return ".$this->valor.";");
        if ($this->hyp) {
            if ($this->shiftPulsado)
                return asinh($this->valor);
            else {
                return sinh($this->valor);
            }
        }
        else {
            if ($this->shiftPulsado) {
                if ($val <= 1 && $val >= -1)
                    return asin($val);

            }
            else {
                return sin($val);
            }
        }


    }

    public function cos() {
        $val = eval("return ".$this->valor.";");
        if ($this->hyp) {
            if ($this->shiftPulsado)
                return acosh($val);
            else {
                return cosh($val);
            }
        }
        else {
            if ($this->shiftPulsado) {
                if ($val <= 1 && $val >= -1)
                    return acos($val);

            }
            else {
                return cos($val);
            }
        }
    }

    public function tan() {
        if ($this->hyp) {
            if ($this->shiftPulsado)
                return atanh(eval("return ".$this->valor.";"));
            else {
                return tanh(eval("return ".$this->valor.";"));
            }
        }
        else {
            if ($this->shiftPulsado) {
                return atan(eval("return ".$this->valor.";"));

            }
            else {
                return tan(eval("return ".$this->valor.";"));
            }
        }
    }

    public function logaritmo() {
        $value;
        if ($this->shiftPulsado)
            $value = log(eval("return ".$this->valor.";"));
        else
            $value = log10(eval("return ".$this->valor.";"));

        if ($this->editable) {
            $this->formula ="". $value;
        }
        else {
            $this->formula .= $value;
        }
        $this->editable = true;
    }

    public function cambiarGrados() {
        switch ($this->grados) {
            case "deg":
                $this->grados = "rad";
                break;
            case "rad":
                $this->grados = "grad";
                break;
            case "grad":
                $this->grados = "deg";
                break;
        }
    }

    public function factorial() {
        $fact = 1;

        for ($i = 1; $i <= eval("return ".$this->valor.";"); $i++) {
            $fact *= $i;
        }

        $this->valor = "" . $fact;
        $this->mostrarTexto();
    }

    public function potencia() {
        $this->anterior = $this->valor;
        $this->valor = "0";
    }

    public function escribePi() {
        $this->valor = "" . pi();
    }

    public function potencia10() {
        $val = pow(10, eval("return ".$this->valor.";"));
        $this->valor = "" . $val;
    }

    public function cuadrado() {
        $this->valor = "" . pow(eval("return ".$this->valor.";"), 2);
    }

    public function shift() {
        $this->shiftPulsado = !$this->shiftPulsado;
    }

    public function toggleHyp() {
        $this->hyp = !$this->hyp;
    }

    public function toggleFE() {
        $this->fe = !$this->fe;
    }

    public function memoria0() {
        $this->memoria = 0;
    }

    public function memoriaAlmacenar() {
        $this->memoria = eval("return ".$this->valor.";");
    }

    public function exponencial() {
        $this->valor = $this->valor . "e+";
    }

    public function mostrarTexto() {
        if($this->fe)
            return "". sprintf('%e', eval("return ".$this->valor.";"));
        else
            return $this->valor;
    }

    public function mostrarFormula() {
        return $this->formula;
    }

    public function getTxt($op) {
        if($this->shiftPulsado) {
            if($op == "log") {
                return "ln";
            }
            else {
                if($this->hyp) {
                    return $op."h-1";
                }
                 else {
                    return $op."-1";
                 }
            }            
        }
        else {
            if($op=="log") {
                return $op;
            }
            else {
                if($this->hyp) {
                    return $op."h";
                }
                else {
                    return $op;
                }
            }
        }
        
    }

    public function getGrados() {
        return strtoupper($this->grados);
    }
}

if(count($_POST)>0) {
    if(isset($_POST['DEG'])) {
        $calculadoraCientifica->cambiarGrados();
    }
    if(isset($_POST['HYP'])) {
        $calculadoraCientifica->toggleHyp();
    }
    if(isset($_POST['F-E'])) {
        $calculadoraCientifica->toggleFE();
    }
    if(isset($_POST['MC'])) {
        $calculadoraCientifica->memoria0();
    }
    if(isset($_POST['MR'])) {
        $calculadoraCientifica->mrc();
    }
    if(isset($_POST['M+'])) {
        $calculadoraCientifica->mmas();
    }
    if(isset($_POST['M-'])) {
        $calculadoraCientifica->mmenos();
    }
    if(isset($_POST['MS'])) {
        $calculadoraCientifica->memoriaAlmacenar();
    }
    if(isset($_POST['x^2'])) {
        $calculadoraCientifica->cuadrado();
    }
    if(isset($_POST['x^y'])) {
        $calculadoraCientifica->potencia();
    }
    if(isset($_POST['sin'])) {
        $calculadoraCientifica->trigonometrica('sin');
    }
    if(isset($_POST['cos'])) {
        $calculadoraCientifica->trigonometrica('cos');
    }
    if(isset($_POST['tan'])) {
        $calculadoraCientifica->trigonometrica('tan');
    }
    if(isset($_POST['√'])) {
        $calculadoraCientifica->raiz();
    }
    if(isset($_POST['10^x'])) {
        $calculadoraCientifica->potencia10();
    }
    if(isset($_POST['log'])) {
        $calculadoraCientifica->logaritmo();
    }
    if(isset($_POST['Exp'])) {
        $calculadoraCientifica->exponencial();
    }
    if(isset($_POST['Mod'])) {
        $calculadoraCientifica->modulo();
    }
    if(isset($_POST['shift'])) {
        $calculadoraCientifica->shift();
    }
    if(isset($_POST['CE'])) {
        $calculadoraCientifica->borrar();
    }
    if(isset($_POST['C'])) {
        $calculadoraCientifica->borrarTodo();
    }
    if(isset($_POST['←'])) {
        $calculadoraCientifica->retroceder();
    }
    if(isset($_POST['/'])) {
        $calculadoraCientifica->dividir();
    }
    if(isset($_POST['π'])) {
        $calculadoraCientifica->escribePi();
    }    
    if(isset($_POST['7'])) {
        $calculadoraCientifica->digitos('7');
    }  
    if(isset($_POST['8'])) {
        $calculadoraCientifica->digitos('8');
    }  
    if(isset($_POST['9'])) {
        $calculadoraCientifica->digitos('9');
    } 
    if(isset($_POST['x'])) {
        $calculadoraCientifica->multiplicar();
    }    
    if(isset($_POST['n!'])) {
        $calculadoraCientifica->factorial();
    } 
    if(isset($_POST['4'])) {
        $calculadoraCientifica->digitos('4');
    } 
    if(isset($_POST['5'])) {
        $calculadoraCientifica->digitos('5');
    } 
    if(isset($_POST['6'])) {
        $calculadoraCientifica->digitos('6');
    } 
    if(isset($_POST['-'])) {
        $calculadoraCientifica->restar();
    } 
    if(isset($_POST['+/-'])) {
        $calculadoraCientifica->masMenos();
    } 
    if(isset($_POST['1'])) {
        $calculadoraCientifica->digitos('1');
    } 
    if(isset($_POST['2'])) {
        $calculadoraCientifica->digitos('2');
    } 
    if(isset($_POST['3'])) {
        $calculadoraCientifica->digitos('3');
    } 
    if(isset($_POST['+'])) {
        $calculadoraCientifica->sumar();
    } 
    if(isset($_POST['('])) {
        $calculadoraCientifica->digitos('(');
    } 
    if(isset($_POST[')'])) {
        $calculadoraCientifica->digitos(')');
    } 
    if(isset($_POST['0'])) {
        $calculadoraCientifica->digitos('0');
    } 
    if(isset($_POST['punto'])) {
        $calculadoraCientifica->punto();
    } 
    if(isset($_POST['='])) {
        $calculadoraCientifica->igual();
    } 
}


echo "<body>
<header>
    <h1>Calculadora Científica</h1>
    <p>Daniel Fernández Bernardino, Software y Estándares para la Web</p>
</header>

<form action='#' method='post'>

    <h2>Windows</h2>

    <label for='formula'>Resultado</label>
    <input id='formula' type='text' readonly value='",$calculadoraCientifica->mostrarFormula(),"' />
    <label for='valor'>Valor</label>
    <input id='valor' type='text' readonly value='",$calculadoraCientifica->mostrarTexto(),"' />

    <p>
        <input type='submit' value=",$calculadoraCientifica->getGrados()," name='DEG'>
        <input type='submit' value='HYP' name='HYP'>
        <input type='submit' value='F-E' name='F-E'>
    </p>

    <input type='submit' value='MC' name='MC' />
    <input type='submit' value='MR' name='MR' />
    <input type='submit' value='M+' name='M+' />
    <input type='submit' value='M-' name='M-' />
    <input type='submit' value='MS' name='MS' />

    <input type='submit' value='x^2' name='x^2' />
    <input type='submit' value='x^y' name='x^y' />
    <input type='submit' value='",$calculadoraCientifica->getTxt('sin'),"' name='sin' />
    <input type='submit' value='",$calculadoraCientifica->getTxt('cos'),"' name='cos' />
    <input type='submit' value='",$calculadoraCientifica->getTxt('tan'),"' name='tan' />

    <input type='submit' value='√' name='√' />
    <input type='submit' value='10^x' name='10^x' />
    <input type='submit' value='",$calculadoraCientifica->getTxt('log'),"' name='log' />
    <input type='submit' value='Exp' name='Exp' />
    <input type='submit' value='Mod' name='Mod' />

    <input type='submit' value='shift' name='shift' />
    <input type='submit' value='CE' name='CE' />
    <input type='submit' value='C' name='C' />
    <input type='submit' value='←' name='←' />
    <input type='submit' value='/' name='/' />

    <input type='submit' value='π' name='π' />
    <input type='submit' value='7' name='7' />
    <input type='submit' value='8' name='8' />
    <input type='submit' value='9' name='9' />
    <input type='submit' value='x' name='x' />

    <input type='submit' value='n!' name='n!' />
    <input type='submit' value='4' name='4' />
    <input type='submit' value='5' name='5' />
    <input type='submit' value='6' name='6' />
    <input type='submit' value='-' name='-' />

    <input type='submit' value='+/-' name='+/-' />
    <input type='submit' value='1' name='1' />
    <input type='submit' value='2' name='2' />
    <input type='submit' value='3' name='3' />
    <input type='submit' value='+' name='+' />

    <input type='submit' value='(' name='(' />
    <input type='submit' value=')' name=')' />
    <input type='submit' value='0' name='0' />
    <input type='submit' value='.' name='punto' />
    <input type='submit' value='=' name='=' />


</form>

</body>

</html>"
?>