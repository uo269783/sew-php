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
           Buscar usuario en la base de datos
        </h2>
        
        <form action='#' method='post'>
        <label for='atributo'>Filtro a aplicar:</label>
        <select name='atributo' id='atributo'>
            <option value='dni'>DNI</option>
            <option value='nombre'>Nombre</option>
            <option value='apellidos'>Apellidos</option>
            <option value='email'>Email</option>
            <option value='telefono'>Telefono</option>
            <option value='edad'>Edad</option>
            <option value='sexo'>Sexo</option>
            <option value='nivelInformatica'>Nivel informático</option>
            <option value='tiempo'>Tiempo</option>
            <option value='correcto'>Resuelto correctamente</option>
            <option value='comentarios'>Comentarios</option>
            <option value='propuestas'>Propuestas</option>
            <option value='valoracion'>Valoración</option>
        </select>
        
            <label for='valor'>Valor:</label>
            <input type='text' name='valor' id='valor' placeholder='Valor' />
            
            <input type='submit' name='select' value='Buscar' />

        </form>
    </section>

    <section>
        <h2>Resultado de la consulta</h2>

        ",$bd->escribirResultado(),"
    </section>

    </body>

    </html>
    ";

    ?>

