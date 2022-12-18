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
           Eliminar usuario de la base de datos
        </h2>
        
        <form action='#' method='post'>
        
            <label for='dni'>DNI a eliminar:</label>
            <input type='text' name='dni' id='dni' placeholder='DNI' />
            
            <input type='submit' name='delete' value='Eliminar' />

        </form>
    </section>

    </body>

    </html>
    ";

    ?>

