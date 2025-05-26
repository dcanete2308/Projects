<?php

class EstudiosView
{

    public function showEstudios()
    {
        echo "<!DOCTYPE html>";
        echo "<html lang='es'>";
        echo "";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>Landing Dídac</title>";
        echo "<link rel='stylesheet' href='../css/estilos.css'>";
        echo "<link href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap' rel='stylesheet'>";
        echo "</head>";
        echo "";
        echo "<body>";
        echo "	<header id='header'>";
        echo "		<div class='logo-container'>";
        echo "			<img src='../media/logoWild.png' alt='Logo' />";
        echo "		</div>";
        echo "		<nav>";
        echo "			<ul>";
        echo "				<li><a href='index.php?Home/show'>Sobre mí</a></li>";
        echo "				<li><a href='index.php?Estudios/show'>Studies</a></li>";
        echo "				<li><a href='index.php?Calendar/show'>Calendario</a></li>";
        echo "			</ul>";
        echo "		</nav>";
        echo "	</header>";
        echo "	<main id='studies'>";
        echo "		<h3 class='studiesH3'>Lenguajes de programación</h3>";
        echo "		<hr class='hrStu'>";
        echo "		<div class='lengaujesProgramacion'>";
        echo "			<img src='../media/PHP-logo.png' alt='php'>";
        echo "			<img src='../media/htmlCss.png' alt='HTML y CSS'>";
        echo "			<img src='../media/java.png' alt='Java'>";
        echo "			<img src='../media/javascript_icon_130900.webp' alt='JS'>";
        echo "		</div>";
        echo "		<section id='escuelas'>";
        echo "			<div class='informacionEscuela'>";
        echo "				<h3 class='studiesH3'>Ins Alexander Satorras</h3>";
        echo "              <img src='../media/sato.png' alt='logo Satorras'>";
        echo "				<p>Completé mis estudios de secundaria y bachillerato tecnológico en el periodo comprendido entre 2017 y 2022. Durante estos años, adquirí no solo conocimientos fundamentales en diversas materias, sino también habilidades prácticas que me han preparado para enfrentar los desafíos del mundo actual. Mi formación técnica me permitió familiarizarme con herramientas y tecnologías que son esenciales en el ámbito laboral. A lo largo de este proceso educativo, también desarrollé un fuerte sentido de responsabilidad y compromiso, lo cual considero fundamental para mi crecimiento personal y profesional. Estoy emocionado por lo que el futuro me depara, ya que cada experiencia durante esos años ha sido un peldaño hacia mis objetivos.</p>";
        echo "			</div>";
        echo "			<div class='informacionEscuela'>";
        echo "				<h3 class='studiesH3'>Ins Thos i Codina</h3>";
        echo "				<img src='../media/thos-i-codina_512.webp' alt='logo Thos'>";
        echo "				<p>Actualmente, estoy cursando el segundo año del ciclo formativo de Desarrollo de Aplicaciones Web (DAW). Este programa me ha permitido profundizar en el diseño y la programación de aplicaciones web, tanto del lado del cliente como del servidor. Durante este año, he estado aprendiendo sobre tecnologías fundamentales como HTML, CSS y JavaScript, así como también lenguajes de programación y bases de datos. Este proceso educativo no solo me está brindando conocimientos técnicos, sino también la oportunidad de desarrollar habilidades críticas como la resolución de problemas y el trabajo en equipo. Estoy entusiasmado por aplicar lo que he aprendido en proyectos reales y seguir creciendo en este emocionante campo.</p>";
        echo "			</div>";
        echo "		</section>";
        echo "	</main>";
        echo "</body>";
        echo "";
        echo "</html>";
        
    }
}

