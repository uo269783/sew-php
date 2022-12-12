<!DOCTYPE HTML>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="author" content="Daniel Fernández Bernardino" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Calculadora Milan</title>
    <link rel="stylesheet" type="text/css" href="CalculadoraMilan.css" />
    <script src="CalculadoraMilan.js"></script>
    <?php 
    session_start();
    class Calculadora {

        public function __construct() {
            this.anterior = null;
            this.operador = null;
            this.editable = false;
            this.valor = "0";
            this.memoria = Number(0);
    
            document.addEventListener('keydown', (event) => this.procesarTeclas(event));
        }
    
        mostrarTexto() {
            var texto = document.getElementsByTagName("input")[0];
            texto.value = this.valor;
        }
    
        digitos(value) {
    
            if (this.valor == "0" || this.editable) {
                this.valor = "" + value;
                this.editable = false;
            }
            else
                this.valor += value;
    
            this.mostrarTexto();
        }
    
        punto() {
    
            if (!this.valor.includes("."))
                this.valor += ".";
    
            this.editable = false;
            this.mostrarTexto();
        }
    
        borrarTodo() {
            this.valor = "0";
            this.anterior = null;
            this.editable = true;
            this.operador = null;
            this.mostrarTexto();
        }
    
        borrar() {
            this.valor = "0";
            this.editable = true;
            this.mostrarTexto();
        }
    
        basica(operador) {
    
            //no hay numero anterior
            if (this.anterior == null || this.editable) {
                this.anterior = this.valor;
                this.valor = "0";
                this.operador = operador;
            }
    
            //hay numero anterior
            else {
                this.valor = "" + eval(Number(this.anterior) + this.operador + Number(this.valor));
                this.anterior = this.valor;
                this.editable = true;
                this.operador = operador;
            }
    
            this.mostrarTexto();
        }
    
        sumar() {
            this.basica("+");
        }
    
        restar() {
            this.basica("-");
        }
    
        multiplicar() {
            this.basica("*");
        }
    
        dividir() {
            this.basica("/");
        }
    
        raiz() {
            if (Number(this.valor) > 0) {
                this.valor = "" + Math.sqrt(Number(this.valor));
                this.editable = true;
            }
            this.mostrarTexto();
        }
    
        igual() {
            if (this.anterior == null) {
                this.valor = "" + this.valor;
            }
            else {
                this.valor = "" + eval(Number(this.anterior) + this.operador + Number(this.valor));
                this.editable = true;
                this.anterior = null;
                this.operador = null;
            }
    
            this.mostrarTexto();
        }
    
        porcentaje() {
            if (this.anterior == null) { //si no hay anterior, simplemente se pone a 0
                this.valor = "0";
            }
            else {
                var percent = Number(this.valor);
                this.valor = "" + eval(Number(this.anterior) * percent / 100);
                this.editable = true;
            }
    
            this.mostrarTexto();
        }
    
        masMenos() {
            this.valor = "" + eval(Number(this.valor) + "*-1");
            this.mostrarTexto();
        }
    
        mrc() {
            this.valor = this.memoria.toString();
            this.editable = true;
            this.mostrarTexto();
        }
    
        mmas() {
            this.memoria += Number(this.valor);
            this.editable = true;
        }
    
        mmenos() {
            this.memoria -= Number(this.valor);
            this.editable = true;
        }
    
        procesarTeclas(event) {
            switch (event.key) {
                case '1':
                    this.digitos(1);
                    break;
                case '2':
                    this.digitos(2);
                    break;
                case '3':
                    this.digitos(3);
                    break;
                case '4':
                    this.digitos(4);
                    break;
                case '5':
                    this.digitos(5);
                    break;
                case '6':
                    this.digitos(6);
                    break;
                case '7':
                    this.digitos(7);
                    break;
                case '8':
                    this.digitos(8);
                    break;
                case '9':
                    this.digitos(9);
                    break;
                case '0':
                    this.digitos(0);
                    break;
                case '+':
                    this.sumar();
                    break;
                case '-':
                    this.restar();
                    break;
                case '*':
                    this.multiplicar();
                    break;
                case '/':
                    this.dividir();
                    break;
                case 'Enter':
                    event.preventDefault();
                    this.igual();
                    break;
                case 'c':
                    this.borrarTodo();
                    break;
                case 'Backspace':
                    this.borrar();
                    break;
                case '.':
                    this.punto();
                    break;
                case 'r':
                    this.raiz();
                    break;
                case 'm':
                    this.mrc();
                    break;
                case 'n':
                    this.mmenos();
                    break;
                case 'b':
                    this.mmas();
                    break;
                case 's':
                    this.masMenos();
                    break;
                case 'p':
                    this.porcentaje();
                    break;
            }
    
        }
    
    }
    
    var calculadora = new Calculadora();
    ?>
</head>

<body>
    <header>
        <h1>Calculadora Milan</h1>
        <p>Daniel Fernández Bernardino, Software y Estándares para la Web</p>
    </header>
    <pre>
        <code>
            
        </code>
    </pre>

    <form action='#' method='post'>


        <label for="resultado">nata by Milan</label>
        <input id="resultado" type="text" readonly value="0" />



        <input type="button" value="CE" onclick="calculadora.borrar()" />
        <input type="button" value="C" onclick="calculadora.borrarTodo()" />
        <input type="button" value="+/-" onclick="calculadora.masMenos()" />
        <input type="button" value="√" onclick="calculadora.raiz()" />
        <input type="button" value="%" onclick="calculadora.porcentaje()" />

        <input type="button" value="7" onclick="calculadora.digitos(7)" />
        <input type="button" value="8" onclick="calculadora.digitos(8)" />
        <input type="button" value="9" onclick="calculadora.digitos(9)" />
        <input type="button" value="x" onclick="calculadora.multiplicar()" />
        <input type="button" value="/" onclick="calculadora.dividir()" />

        <input type="button" value="4" onclick="calculadora.digitos(4)" />
        <input type="button" value="5" onclick="calculadora.digitos(5)" />
        <input type="button" value="6" onclick="calculadora.digitos(6)" />
        <input type="button" value="-" onclick="calculadora.restar()" />
        <input type="button" value="MRC" onclick="calculadora.mrc()" />

        <input type="button" value="1" onclick="calculadora.digitos(1)" />
        <input type="button" value="2" onclick="calculadora.digitos(2)" />
        <input type="button" value="3" onclick="calculadora.digitos(3)" />
        <input type="button" value="+" onclick="calculadora.sumar()" />
        <input type="button" value="M-" onclick="calculadora.mmenos()" />

        <input type="button" value="0" onclick="calculadora.digitos(0)" />
        <input type="button" value="." onclick="calculadora.punto()" />
        <input type="button" value="=" onclick="calculadora.igual()" />
        <input type="button" value="M+" onclick="calculadora.mmas()" />

    </form>

</body>

</html>