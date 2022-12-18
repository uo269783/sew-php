<?php
include "Ejercicio7.php";
echo "
<!DOCTYPE html>
<html lang='es'>

<head>
    <meta name='author' content='Daniel Fernández Bernardino' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />

    <title>Práctica 4 - Ejercicio 6</title>
    <link rel='stylesheet' type='text/css' href='Ejercicio7.css' />
</head>
<body>
    <header>
        <h1>Pruebas de usabilidad</h1>
    </header>
    <nav>
        <a href='index.php' accesskey='i'>Inicio</a>
        <a href='reservas.php' accesskey='a'>Reservas</a>
        <a href='mesas.php' accesskey='s'>Mesas</a>
        <a href='trabajadores.php' accesskey='u'>Listado de trabajadores</a>
        <a href='carta.php' accesskey='r'>Ver carta</a>
    </nav>
    <section>
        <h2>
           Lista de mesas
        </h2>
        <p>A continuación aparecerá una lista con las mesas disponibles en el restaurante.</p>

        ",$bd->verMesas(),"
        
        
    </section>

    </body>

    </html>
    ";