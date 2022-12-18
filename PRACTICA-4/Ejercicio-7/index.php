<?php
include "Ejercicio7.php";
echo "

<!DOCTYPE html>
<html lang='es'>

<head>
    <meta name='author' content='Daniel Fernández Bernardino' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />

    <title>Práctica 4 - Ejercicio 7</title>
    <link rel='stylesheet' type='text/css' href='Ejercicio7.css' />
</head>
<body>
    <header>
        <h1>Gestión de base de datos de restaurante</h1>
    </header>
    <nav>
        <a href='index.php' accesskey='i'>Inicio</a>
        <a href='reservas.php' accesskey='a'>Reservas</a>
        <a href='mesas.php' accesskey='s'>Mesas</a>
        <a href='trabajadores.php' accesskey='u'>Listado de trabajadores</a>
        <a href='carta.php' accesskey='r'>Ver carta</a>
    </nav>

    <p>En este documento se verá la gestión de una base de datos de un restaurante, permitiendo hacer reservas, ver el listado de mesas, trabajadores y la carta.</p>
    <section>
        <h2>
            Crear base
        </h2>
        <form action='#' method='post'>
        <p>Para comenzar, cree la base de datos:
            <input type='submit' name='crearBase' value='Crear base de datos' />
        </p>
        <p>A continuación, cree la tabla:
            <input type='submit' name='crearTabla' value='Crear tabla' />
        </p>
        <p>Finalmente, importe los datos:
            <input type='submit' name='cargarDatos' value='Cargar datos' />
        </p>
        </form>
    </section>
    </body>
    </html>
    ";

    ?>