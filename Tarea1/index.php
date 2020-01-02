<?php

function Mensaje($Sesion,$Bandera){
                    if($Sesion=='Activo')
                        echo '<h1>Acceso Permitido</h1>';
                    else if($Sesion=='Registro')
                    echo '<h1>Usuario Registrado</h1>';
                    else if($Sesion=='Error de Acceso'){
                      switch($Bandera){
                       case 1:
                              echo 'Usuario se encuentra vacio';
                              break;
                       case 2: 
                             echo 'Contrase&ntildea se encuentra vacio';
                             break;
                       case 3:  
                             echo 'Usuario y Contrase&ntildea se encuentra vacio';
                             break;
                      }
                    }
                      else
                      echo '<h1>Usuario no Registrado....</h1>';
                      return;
                }
/*
   Inicio de Programa            
*/
  session_start();
  if(isset($_SESSION['Acceso'])) {

	$BanderaValores=0;
	if(!is_file('archivo.txt')){
		$fp = fopen("archivo.txt", "w+");
		if($fp==false) die("No se pudo crear el archivo"); 
		fclose($fp);
	}
	
	$valores=array(3);
	$valoresOriginales=array(3);
	$i=0;
  if( isset($_POST['nombre']) && isset($_POST['clave']))
	foreach($_POST as $valor){
		$valores[$i]=sha1(trim($valor));
		$valoresOriginales[$i]=trim($valor);
		$i++;
	}
	if(!empty($valoresOriginales[0]) && !empty($valoresOriginales[1])){
		if($_POST['acceder']=='Entrar'){
			//Leer Archivo de texto
			$fp = fopen("archivo.txt", "r");
			$compararValores=array(2);
			$bandera=false;
			$linea=0;
			while (!feof($fp) && !$bandera){
				if($linea<2){
					$compararValores[$linea] = fgets($fp);
					$linea++;
				}
				else
				{
					if(trim($compararValores[0])==$valores[0] && trim($compararValores[1])==$valores[1]){
						$_SESSION['Acceso']='Activo';
						$bandera=true;
					}
					$linea=0;
				}	
			}
			fclose($fp);
		}
		else if($_POST['acceder']=='Registrarse'){
		
		 $fp = fopen("archivo.txt", "a");
			fwrite($fp, $valores[0] . PHP_EOL);
			fwrite($fp, $valores[1] . PHP_EOL);
			fclose($fp);
			$_SESSION['Acceso']='Registro';
		}
	}
	else
	{
	  $_SESSION['Acceso']='Error de Acceso';
	  if(empty($valoresOriginales[0]))
		  $BanderaValores=1;
	  if(empty($valoresOriginales[1]))
		  $BanderaValores=2;
	  if(empty($valoresOriginales[0]) && empty($valoresOriginales[1]))
	     $BanderaValores=3;
	}
  }
 ?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
    <title>Aplicacion WEB con manejo de Archivo</title>
    <link rel="stylesheet" href="estilo.css">
</head>

<body>
    <div id="principal">
        <div id="cabecera">
			<center> <img src="logo-w.png" style="margin-top:3%;"> </center>
		</div>
        <div id="avisos"> 
		
		</div>
        <div class="clear"></div>
        <div id="login">
          <center>
            <?php
            if(!isset($_SESSION['Acceso'])){ ?>
            <form action="index.php" method="post">
              <h1>Acceso a Sistema</h1>
                Usuario&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
				<input type="text" name="nombre" maxlength="100" value="" size="30"
                style="font-family: Arial; font-size: 14pt; background-color: #e0d100"><br />
                <br />
				Contrase&ntildea :
                <input type="password" name="clave" maxlength="100" size="30"
                style="font-family: Arial; font-size: 14pt; background-color: #e0d100"><br />
                <br />
                <input type="submit" name="acceder" value="Entrar"
                style="font-family: Arial; font-size: 14pt; background-color: #00FF00">
				&nbsp;&nbsp;&nbsp;
                <input type="submit" name="acceder" value="Registrarse"
                style="font-family: Arial; font-size: 14pt; background-color: #00FF00"><br />
		   </form>
		   <br />
           <?php
             $_SESSION['Acceso']='Iniciando';
             }
             else {
                //Mensaje
                Mensaje($_SESSION['Acceso'],$BanderaValores);
                
                session_destroy();
             }
           ?>

         </center>
        </div>

        <div class="clear">
        </div>
        <div id="pie">
            <div id="pieizq">
                <center>
				  <h6>
				  <br />
				   Asignatura: Computación en el Servidor Web <br />
			 	   Maestro:  Israel Sandoval <br />
                   Desarollo de Actividad: Ra&uacute;l V&aacute;zquez Tiscare&ntilde;o <br />
                   Email: rtiscareno@hotmail.com
                 </h6>
                </center>
            </div>
        </div>
    </div>
</body>
</html>
