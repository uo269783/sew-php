<?php
include "Ejercicio6.php";
echo "
<!DOCTYPE html>
<html lang='es'>

<head>
    <meta name='author' content='Daniel Fernández Bernardino' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />

    <title>Práctica 4 - Ejercicio 6</title>
    <link rel='stylesheet' type='text/css' href='Ejercicio6.css' />
</head>
<body>
    <header>
        <h1>Pruebas de usabilidad</h1>
    </header>
    <nav>
        <a href='index.php' accesskey='i'>Inicio</a>
        <a href='insert.php' accesskey='a'>Añadir</a>
        <a href='select.php' accesskey='s'>Buscar</a>
        <a href='update.php' accesskey='u'>Modificar</a>
        <a href='delete.php' accesskey='d'>Eliminar</a>
        <a href='informe.php' accesskey='r'>Generar informe</a>
        <a href='importar.php' accesskey='w'>Importar CSV</a>
        <a href='exportar.php' accesskey='e'>Exportar CSV</a>
    </nav>
    <section>
        <h2>
           Añadir usuario a la base de datos
        </h2>
        
        
        <form action='#' method='post'>
            <fieldset>
            <legend>Datos del usuario</legend>
                <p>
                    <label for='dni'>DNI:</label>
                    <input type='text' id='dni' name='dni' />
                    <label for='nombre'>Nombre:</label>
                    <input type='text' id='nombre' name='nombre' />
                    <label for='apellidos'>Apellidos:</label>
                    <input type='text' id='apellidos' name='apellidos' />
                </p>
                <p>
                    <label for='email'>Email:</label>
                    <input type='text' id='email' name='email' />
                    <label for='telefono'>Nº de teléfono:</label>
                    <input type='text' id='telefono' name='telefono' />
                    <label for='edad'>Edad:</label>
                    <input type='number' id='edad' name='edad' />
                </p>
            </fieldset>

            <fieldset>
                <legend>Sexo</legend>
                <label for='masc'>Masculino</label>
                <input type='radio' id='masc' name='sexo' value='Masculino' checked/>
                <label for='fem'>Femenino</label>
                <input type='radio' id='fem' name='sexo' value='Femenino' />
            </fieldset>

            <fieldset>
                <legend>Tarea realizada correctamente</legend>
                <label for='si'>Sí</label>
                <input type='radio' id='si' name='correcto' value='Sí'/>
                <label for='no'>No</label>
                <input type='radio' id='no' name='correcto' value='No' checked />
            </fieldset>

            <fieldset>
            <legend>Datos de la prueba</legend>
                <p>
                    <label for='nivel'>Conocimiento informático:</label>
                    <input type='number' id='nivel' name='nivel' min='0' max='10' />
                    <label for='tiempo'>Tiempo:</label>
                    <input type='text' id='tiempo' name='tiempo' />
                </p>
                <p>
                    <label for='comentarios'>Comentarios:</label>
                    <textarea id='comentarios' name='comentarios'></textarea>
                    <label for='propuestas'>Propuestas:</label>
                    <textarea id='propuestas' name='propuestas'></textarea>
                </p>
                <p>
                    <label for='valoracion'>Valoracion:</label>
                    <input type='number' id='valoracion' name='valoracion' />
                    <input type='submit' name='insert' value='Insertar' />
                </p>
            </fieldset>

        </form>
    </section>

    </body>

    </html>
    ";

    ?>

