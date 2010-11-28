<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Usuario
 *
 * @author jorge
 */
class Usuario extends Controller {
    private $delta;
    public function __construct() {
	parent::Controller();
	$this->load->helper('url');
	$this->load->library("form_validation");
	//Seteando la zona horaria
	date_default_timezone_set('America/Santiago');
	$this->delta = 4000;
    }

    public function mapa() {
	$nombres = array('jorge', 'rodrigo', 'daniel', 'rakesh');
	$latitudes = array('10001002', '10001034', '10001010', '10001022');
	$longitudes = array('20201032', '20201042', '20201012', '20201010');
	$radios = array('5', '8', '6', '10');
	$avatares = array('barman', 'alien', 'caradebolsa', 'emo');
	$estados = array('conectado', 'no disponible', 'conectado', 'ausente');
	$colores = array('0xddff00', '0xccccff', '0xdddddd', '0x00ddcc');

	$total = count($nombres);
	$data = array();
	for ($i = 0; $i < $total; $i++) {
	    $data[] = array('username' => $nombres[$i],
		'latitud' => $latitudes[$i],
		'longitud' => $longitudes[$i],
		'radio' => $radios[$i],
		'avatar' => $avatares[$i],
		'estado' => $estados[$i],
		'color' => $colores[$i]
	    );
	}

	$data = json_encode(array('mapa' => $data));
	$this->load->view('json_data', array('data' => $data));
    }

    public function index() {
	$this->registro();
    }

    public function login() {
	$this->form_validation->set_rules('username', 'Usuario', 'trim|required|xss_clean');
	$this->form_validation->set_rules('password', 'Contrase&ntilde;a', 'trim|required|xss_clean');
	$this->form_validation->set_rules('latitud', 'Latitud', 'trim|required|callback_dec2int|xss_clean');
	$this->form_validation->set_rules('longitud', 'Longitud', 'trim|required|callback_dec2int|xss_clean');

	//Si no es valido
	if ($this->form_validation->run() == FALSE) {
	    $data = array('ERROR' => validation_errors());
	    $data = json_encode($data);
	    $this->load->view('json_data', array('data' => $data));
	} else {
	    $where = array('username' => $this->input->post('username'),
		'password' => md5($this->input->post('password')));

	    $this->load->model('Usuario_model');
	    $usuario = $this->Usuario_model->getUsuario($where);

	    if (count($usuario) > 0) {

		$latitud = $this->input->post('latitud');
		$longitud = $this->input->post('longitud');

		$usuario = $usuario[0];
		$id_usuario = $usuario['id_usuario'];

		$this->load->model('Online_users_model');
		$where = 'longitud > ' . ($longitud - $this->delta) . ' AND longitud < ' . ($longitud + $this->delta) . ' AND latitud > ' . ($latitud - $this->delta) . ' AND latitud < ' . ($latitud + $this->delta);
		$usuariosOnLine = $this->Online_users_model->getOnlineUsers($where . ' AND id_usuario != ' . $id_usuario);


		$data = array('id_usuario' => $usuario['id_usuario'],
		    'username' => $usuario['username'],
		    'latitud' => $latitud,
		    'longitud' => $longitud,
		    'radio' => $usuario['precision'],
		    'ip_addr' => $_SERVER['REMOTE_ADDR'],
		    'estado' => $usuario['estado'],
		    'avatar' => $usuario['avatar'],
		    'color' => $usuario['color']
		);


		if (count($this->Online_users_model->getOnlineUsers(array('id_usuario' => $id_usuario))) != 0)
		    $this->Online_users_model->setOnlineUser($data, $id_usuario);
		else {
		    $this->Online_users_model->setOnlineUser($data);
		}

		$this->setPosicion($usuario['id_usuario'], $latitud, $longitud);
		$infoUsuario = $data;

		$data = array();
		foreach ($usuariosOnLine as $usuario) {
		    $color = $this->hex2dec($this->getColorPorAfinidad($id_usuario, $usuario['id_usuario']));

		    $data[] = array('username' => $usuario['username'],
			'latitud' => $usuario['latitud'],
			'longitud' => $usuario['longitud'],
			'radio' => $usuario['radio'],
			'avatar' => $usuario['avatar'],
			'estado' => $usuario['estado'],
			'color' => $color,
			'mensaje' => $usuario['mensaje']
		    );

		    $infoUsuario['color'] = $color;
		    /////Actualizar mapa del usuario con los datos del recien logeado
		    //    $this->enviarMensajeAlSocket($usuario['ip_addr'], json_encode(array('add' => $infoUsuario)));
		}

		$this->load->model('Lugar_model');
		$lugares = $this->Lugar_model->getLugar($where);
		$place = array();
		foreach ($lugares as $lugar) {
		    $place[] = array('nombre' => $lugar['nombre'],
			'descripcion' => $lugar['descripcion'],
			'latitud' => $lugar['latitud'],
			'longitud' => $lugar['longitud']
		    );
		}
		$data = json_encode(array('mapa' => $data, 'lugares' => $place));
		$this->load->view('json_data', array('data' => $data));
	    } else {
		$data = array('ERROR' => 'Usuario o password incorrecto.');
		$data = json_encode($data);
		$this->load->view('json_data', array('data' => $data));
	    }
	}
    }

    public function updateLugar($lat, $lng) {
	
    }

    public function getLugar($lat, $lng) {
	
    }

    public function getColorPorAfinidad($user1, $user2) {
	//$colores = array('0xE50B00','0xFF9C00','0xFFF800','0x2700B2','0xA400CC');
	$colores = array('0xA400CC', '0x2700B2', '0xFFF800', '0xFF9C00', '0xE50B00');
	$this->load->model('Usuario_model');
	$interes1 = $this->Usuario_model->getUsuarioInteres(array('USUARIO.id_usuario' => $user1));
	$interes2 = $this->Usuario_model->getUsuarioInteres(array('USUARIO.id_usuario' => $user2));
	$iguales = 0;
	$total1 = count($interes1);
	$total2 = count($interes2);
	for ($i = 0; $i < $total1; $i++) {
	    for ($j = 0; $j < $total2; $j++) {
		if ($interes1[$i]['id_interes'] == $interes2[$j]['id_interes'])
		    $iguales++;
	    }
	}
	return $colores[$iguales];
    }

    public function update() {
	$this->form_validation->set_rules('latitud', 'Latitud', 'trim|required|callback_dec2int|xss_clean');
	$this->form_validation->set_rules('longitud', 'Longitud', 'trim|required|callback_dec2int|xss_clean');
	//Si no es valido
	if ($this->form_validation->run() == FALSE) {
	    $data = array('ERROR' => validation_errors());
	    $data = json_encode($data);
	    $this->load->view('json_data', array('data' => $data));
	} else {

	    $latitud_new = $this->input->post('latitud');
	    $longitud_new = $this->input->post('longitud');
	    $this->load->model('Online_users_model');
	    $usuario = $this->Online_users_model->getOnlineUsers(array('ip_addr' => $_SERVER['REMOTE_ADDR']));
	    $usuario = $usuario[0];


	    $latitud = $usuario['latitud'];
	    $longitud = $usuario['longitud'];
	    $id_usuario = $usuario['id_usuario'];

	    $usuario['latitud'] = $latitud_new;
	    $usuario['longitud'] = $longitud_new;

	    $this->setPosicion($id_usuario, $latitud_new, $longitud_new);
	    $this->Online_users_model->setOnlineUser(array('latitud' => $latitud_new, 'longitud' => $longitud_new), $id_usuario);

	    $this->getMap();
	    /*
	      $usuariosOnLine = $this->Online_users_model->getOnlineUsers('longitud > '.($longitud-$this->delta).' AND longitud < '.($longitud+$this->delta).' AND latitud > '.($latitud-$this->delta).' AND latitud < '.($latitud+$this->delta).' AND id_usuario != '.$id_usuario);

	      foreach ($usuariosOnLine as $userOnLine){
	      $this->enviarMensajeAlSocket($userOnLine['ip_addr'], json_encode(array('del' => array('username' => $usuario['username']))));
	      }

	      $usuariosOnLine = $this->Online_users_model->getOnlineUsers('longitud > '.($longitud_new-$this->delta).' AND longitud < '.($longitud_new+$this->delta).' AND latitud > '.($latitud_new-$this->delta).' AND latitud < '.($latitud_new+$this->delta).' AND id_usuario != '.$id_usuario);

	      foreach ($usuariosOnLine as $userOnLine){
	      $this->enviarMensajeAlSocket($userOnLine['ip_addr'], json_encode(array('add' => $usuario)));
	      }
	     */
	}
    }

    public function sendMensaje() {
	$this->form_validation->set_rules('mensaje', 'Mensaje', 'trim|required|xss_clean');

	if ($this->form_validation->run() == FALSE) {
	    $data = array('ERROR' => validation_errors());
	    $data = json_encode($data);
	    $this->load->view('json_data', array('data' => $data));
	}

	$this->load->model('Online_users_model');
	$usuario = $this->Online_users_model->getOnlineUsers(array('ip_addr' => $_SERVER['REMOTE_ADDR']));
	$mensaje = $this->input->post('mensaje', true);

	$usuario = $usuario[0];
	$this->Online_users_model->setOnlineUser(array('mensaje' => $mensaje), $usuario['id_usuario']);

	$this->load->model('Usuario_model');
	$user = $this->Usuario_model->getUsuario(array('id_usuario' => $usuario['id_usuario']));
	$user = $user[0];

	$com = 'curl --basic --user ' . $user['usuario_twitter'] . ':' . $user['password_twitter'] . ' --data status="' . $mensaje . '" http://twitter.com/statuses/update.xml';
	exec($com);
	$data = array('mensaje' => 'ok');
	$data = json_encode($data);
	$this->load->view('json_data', array('data' => $data));
    }

    public function getMensaje() {
	$this->form_validation->set_rules('username', 'Nombre de Usuario', 'trim|required|xss_clean');

	if ($this->form_validation->run() == FALSE) {
	    $data = array('ERROR' => validation_errors());
	    $data = json_encode($data);
	    $this->load->view('json_data', array('data' => $data));
	}
	$username = $this->input->post('username');
	$this->load->model('Online_users_model');
	$user = $this->Online_users_model->getOnlineUsers(array('username' => $username));
	$user = $user[0];

	$mensaje = $user['mensaje'];

	if (($mensaje == '') OR ($user['usuario_twitter'] == NULL)) {
	    $this->load->model('Usuario_model');
	    $user = $this->Usuario_model->getUsuario(array('username' => $username));
	    $user = $user[0];
	    if (($user['usuario_twitter'] != '') AND ($user['usuario_twitter'] != NULL)) {
		$url = "http://search.twitter.com/search.json?q=" . $user['usuario_twitter'];

		if (($json = file_get_contents($url))) {
		    $data = json_decode($json);
		    if (isset($data)) {
			$mensaje = $data[0]['text'];
		    }
		}
	    }
	}
	$data = json_encode(array('mensaje' => $mensaje));
	$this->load->view('json_data', array('data' => $data));
    }

    public function getMap() {
	$this->load->model('Online_users_model');
	$usuario = $this->Online_users_model->getOnlineUsers(array('ip_addr' => $_SERVER['REMOTE_ADDR']));

	$latitud = $usuario[0]['latitud'];
	$longitud = $usuario[0]['longitud'];
	$id_usuario = $usuario[0]['id_usuario'];

	$where = 'longitud > ' . ($longitud - $this->delta) . ' AND longitud < ' . ($longitud + $this->delta) . ' AND latitud > ' . ($latitud - $this->delta) . ' AND latitud < ' . ($latitud + $this->delta);
	$usuariosOnLine = $this->Online_users_model->getOnlineUsers($where . ' AND id_usuario != ' . $id_usuario);

	$data = array();
	foreach ($usuariosOnLine as $usuario) {
	    $color = $this->hex2dec($this->getColorPorAfinidad($id_usuario, $usuario['id_usuario']));

	    $data[] = array('username' => $usuario['username'],
		'latitud' => $usuario['latitud'],
		'longitud' => $usuario['longitud'],
		'radio' => $usuario['radio'],
		'avatar' => $usuario['avatar'],
		'estado' => $usuario['estado'],
		'color' => $color,
		'mensaje' => $usuario['mensaje']
	    );
	}

	$this->load->model('Lugar_model');
	$lugares = $this->Lugar_model->getLugar($where);
	$place = array();
	foreach ($lugares as $lugar) {
	    $place[] = array('nombre' => $lugar['nombre'],
		'descripcion' => $lugar['descripcion'],
			'latitud' => $lugar['latitud'],
			'longitud' => $lugar['longitud']
	    );
	}
	$data = json_encode(array('mapa' => $data, 'lugares' => $place));
	$this->load->view('json_data', array('data' => $data));
    }

    public function enviarMensajeAlSocket($ip_addr, $message) {
	$puerto = '2012';
	if (($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) AND (socket_connect($socket, $ip_addr, $puerto))) {
	    $len = strlen($message);
	    $offset = 0;
	    while ($offset < $len) {
		$sent = socket_write($socket, substr($message, $offset), $len - $offset);
		if ($sent === false) {
		    // Error occurred, break the while loop
		    break;
		}
		$offset += $sent;
	    }
	    socket_close($socket);
	}
    }

    public function registro() {
	$this->load->view('usuario_registrar_view');
    }

    public function registrar() {
	define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

	$atributos = array('username', 'password', 'nombre', 'email', 'edad', 'sexo', 'usuario_twitter', 'password_twitter', 'pasatiempo', 'musica', 'deporte', 'elemento');
	$reglas = array();
	foreach ($atributos as $atributo) {
	    $reglas[] = array(
		'field' => $atributo,
		'label' => $atributo,
		'rules' => 'trim|required|xss_clean'
	    );
	}

	$this->form_validation->set_rules($reglas);
	if ($this->form_validation->run() != FALSE) {
	    foreach ($atributos as $atributo) {
		$$atributo = $this->input->post($atributo);
	    }
	    if (IS_AJAX) {
		$avatares = array('barman', 'alien', 'caradebolsa', 'emo');
		$colores = array('0xddff00', '0xccccff', '0xdddddd', '0x00ddcc');
		$index = rand(0, count($avatares) - 1);
		$radio = rand(20, 100);
		$precision = rand(10, 100);
		$data = array('username' => $username,
		    'password' => md5($password),
		    'nombre' => $nombre,
		    'email' => $email,
		    'edad' => $edad,
		    'sexo' => $sexo,
		    'avatar' => $avatares[$index],
		    'radio_visibilidad' => $radio,
		    'color' => $colores[$index],
		    'precision' => $precision,
		    'estado' => 'conectado',
		    'usuario_twitter' => $usuario_twitter,
		    'password_twitter' => $password_twitter);

		$this->load->model('Usuario_model');
		if (($id_usuario = $this->Usuario_model->setUsuario($data))) {
		    ;
		    $atributos = array('pasatiempo', 'musica', 'deporte', 'elemento');
		    foreach ($atributos as $atributo) {
			$this->Usuario_model->setUsuarioInteres($id_usuario, $$atributo);
		    }
		    echo 'ok';
		}
	    }
	}
    }

    public function setPosicion($id_usuario, $latitud, $longitud) {
	$this->load->model('Posicion_model');
	$id_posicion = $this->Posicion_model->getPosicion(array('latitud' => $latitud, 'longitud' => $longitud));

	if (count($id_posicion) == 0) {
	    $id_posicion = $this->Posicion_model->setPosicion(array('latitud' => $latitud, 'longitud' => $longitud));
	} else {
	    $id_posicion = $id_posicion[0]['id_posicion'];
	}

	$this->load->model('Usuario_model');
	$this->Usuario_model->setUsuarioPosicion($id_usuario, $id_posicion, date('Y-m-d H:i:s'));
    }

    public function hex2dec($hex) {
	$hex = preg_replace('/[0x]/', '', $hex);
	return hexdec($hex);
    }

    public function dec2int($dec) {
	return (int) round($dec);
    }

}
?>
