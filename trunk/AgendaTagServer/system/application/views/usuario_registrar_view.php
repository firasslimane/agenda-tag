<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>ChatGPS</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/style.css" />
	<script src="<?php echo base_url(); ?>scripts/jquery-1.4.2.min.js" ></script>
	<script type="text/javascript">
	    function enviar(){
		var datos = 'username=' + $('#username').val();
		datos += '&password=' + $('#password').val();
		datos += '&nombre=' + $('#nombre').val();
		datos += '&email=' + $('#email').val();
		datos += '&edad=' + $('#edad').val();
		datos += '&sexo=' + $('#sexo').val();
		datos += '&usuario_twitter=' + $('#usuario_twitter').val();
		datos += '&password_twitter=' + $('#password_twitter').val();
		datos += '&pasatiempo=' + $('#pasatiempo').val();
		datos += '&musica=' + $('#musica').val();
		datos += '&deporte=' + $('#deporte').val();
		datos += '&elemento=' + $('#elemento').val();

		$.ajax({
		    url:'<?php echo site_url("usuario/registrar")?>',

		    type: 'POST',
		    dataType: 'html',
		    data: datos,
		    success: function(data) {
			if(data == 'ok'){
			    $('.button').fadeOut('fast');
			    $('div#3').fadeOut('fast',function(){
				var html = '<div style="padding:20px;text-align: center;">';
				html += '<h1>Tu registro se ha realizado satisfactoriamente.</h1>';
				html += '<h3>Ahora inicia sesi&oacute;n en tu tel&eacute;fono y podr&aacute;s disfrutar de todas las caracter&iacute;sticas que <span class="chat"> Chat</span><span class="gps">GPS</span> tiene para ti.</h3>';
				html += '</div>';
				$('.content').append(html);
			    });
			}
			else{
			    $('.button').fadeOut('fast');
			    $('div#3').fadeOut('fast',function(){
				var html = '<div style="padding:20px;text-align: center;">';
				html += '<h1>Ha ocurrido un error durante el procedimiento.</h1>';
				html += '<h3>Lamentablemente no te hemos podido registrar en el sistema. Por favor, intenta m&aacute;s tarde.</h3>';
				html += '</div>';
				$('.content').append(html);
			    });
			}
		    }
		});
	    }
	    $(document).ready(function(){
		$('.button#continuar').click(function(){
		    if($('div#1').is(':visible')){
			$('div#1').fadeOut('fast', function(){
			    $('div#2').fadeIn('slow');
			});
		    }else if($('div#2').is(':visible')){
			$('div#2').fadeOut('fast', function(){
			    $('div#3').fadeIn('slow');
			    $('.button').text('Finalizar');
			});
		    }else{
			enviar();
		    }
		    return false;
		});
	    });
	</script>
    </head>
    <body>
	<div id="wrapper">
	    <div class="header"><span class="chat"> Chat</span><span class="gps">GPS</span></div>
	    <div class="container" >
		<div class="content">
		    <form>
			<div id="1">
			    <div class="box">
				<span class="textbox"><label>Nombre de Usuario</label></span>
				<span class="textbox"><input type="text" id="username" name="username" size="35" /></span>
			    </div>
			    <div class="box">
				<span class="textbox"><label>Password</label></span>
				<span class="textbox"><input type="password" id="password" name="password" size="35" /></span>
			    </div>
			    <div class="box">
				<span class="textbox"><label>Nombre Completo</label></span>
				<span class="textbox"><input type="text" id="nombre" name="nombre" size="35" /></span>
			    </div>
			    <div class="box">
				<span class="textbox"><label>e-mail</label></span>
				<span class="textbox"><input type="text" id="email" name="email" size="35" /></span>
			    </div>
			</div>
			<div id="2" style="display: none">
			    <div class="box">
				<span class="textbox"><label>Edad</label></span>
				<span class="textbox"><input type="text" id="edad" name="edad" size="35" /></span>
			    </div>
			    <div class="box">
				<span class="textbox"><label>Sexo</label></span>
				<span class="textbox"><select id="sexo" name="sexo" >
					<option value="m" >Masculino</option>
					<option value="f" >Femenino</option>
				    </select>
				</span>
			    </div>
			    <div class="box">
				<span class="textbox"><label>Usuario Twitter</label></span>
				<span class="textbox"><input type="text" id="usuario_twitter" name="usuario_twitter" size="35" /></span>
			    </div>
			    <div class="box">
				<span class="textbox"><label>Password Twitter</label></span>
				<span class="textbox"><input type="password" id="password_twitter" name="password_twitter" size="35" /></span>
			    </div>
			</div>
			<div id="3" style="display: none">
			    <div class="box">
				<span class="textbox"><label>Pasatiempo Favorito</label></span>
				<span class="textbox"><select id="pasatiempo" name="pasatiempo" >
					<option value="1" >Juntar Estampillas</option>
					<option value="2" >Coleccionar Llaveros</option>
					<option value="3" >Cazar Mariposas</option>
					<option value="4" >Alimentar Palomas</option>
					<option value="5" >Otro menos normal</option>
				    </select>
				</span>
			    </div>
			    <div class="box">
				<span class="textbox"><label>Estido musical predilecto</label></span>
				<span class="textbox"><select  id="musica" name="musica" >
					<option value="6" >Polka</option>
					<option value="7" >Cueca</option>
					<option value="8" >M&uacute;sica Evang&eacute;lica</option>
					<option value="9" >Cumbia Peruana</option>
					<option value="10" >Otro menos popular</option>
				    </select>
				</span>
			    </div>
			    <div class="box">
				<span class="textbox"><label>Deporte que practicas actualmente</label></span>
				<span class="textbox"><select id="deporte" name="deporte" >
					<option value="11" >Rayuela Corta</option>
					<option value="12" >Emboque</option>
					<option value="13" >Palo Encebado</option>
					<option value="14" >Tetris</option>
					<option value="15" >Otro menos extremo</option>
				    </select>
				</span>
			    </div>
			    <div class="box">
				<span class="textbox"  style="text-align: center;padding-top: 5px"><label>Si pudieses ser un elemento de la tabla peri&oacute;dica, ¿cu&aacute;l ser&iacute;as?</label></span>
				<span class="textbox"><select id="elemento" name="elemento"  >
					<option value="16" >Hidr&oacute;geno</option>
					<option value="17" >Litio</option>
					<option value="18" >Magnesio</option>
					<option value="19" >Titanio</option>
					<option value="20" >Aluminio</option>
					<option value="21" >Arsénico</option>
					<option value="22" >Cloro</option>
					<option value="23" >Helio</option>
					<option value="24" >Tulio</option>
					<option value="25" >Otro más inestable</option>
				    </select>
				</span>
			    </div>
			</div>
			<a href="#" id="continuar" class="button">Continuar</a>

		    </form>
		</div>

	    </div>
	</div>
    </body>
</html>
