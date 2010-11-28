<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of usuario
 *
 * @author jorge
 */
class Usuario_model extends Model {
    public function __construct() {
	parent::Model();
    }

    public function getUsuario($where = null) {
	$this->load->database();

	/* Si $campos no fue definido, obtengo todos los campos de la tabla */
	$campos = 'USUARIO.*';

	$this->db->select($campos);

	/* Si se definio $where lo agrego a la consulta*/
	if($where != null && $where != "") {
	    $where = $this->db->escape_str($where);
	    $this->db->where($where);
	}
	$query= $this->db->get('USUARIO');

	$num_filas = $query->num_rows();
	$row=array();
	/* Se recorren todas las filas y se forma el arreglo
         * que contiene la informacion
	*/
	for( $i=0 ; $i<$num_filas ; $i++ ) {
	    $row[$i] = $query->row_array($i);
	}
	return $row;
    }

    /**
     *
     * Actualiza o Inserta las filas que cumplen con las clausulas del parámetro
     * $where. Si el segundo parametro es null, realiza una insercion.
     *
     * @param array $data Campos que seran actualizados.
     *                    Ej: $data = array('nombre' => $nombre, 'psswd'=> $psswd)
     *
     * @param array $where Condiciones que deben cumplir las filas.<br>
     *                     Ej: $where = array('id_persona' => $id)
     *                     <b>Por omisión null</b>.
     * @return object
     */
    public function setUsuario($data, $id_usuario = null) {
	$this->load->database();

	$data = $this->db->escape_str($data);
	if($id_usuario != null) {
	    $id_usuario = $this->db->escape_str($id_usuario);
	    $this->db->where('id_usuario',$id_usuario);
	    return $this->db->update('USUARIO', $data);
	}

	else{
            if($this->db->insert('USUARIO',$data))
                return $this->db->insert_id();
            else
                return FALSE;
        }
    }


    public function delUsuario($id_usuario) {
	$this->load->database();
	$id_usuario = $this->db->escape_str($id_usuario);

	$this->db->where('id_usuario',$id_usuario);

	return $this->db->delete('USUARIO');
    }

    public function getUsuarioRelacionPermiso($where = null) {
	$this->load->database();

	/* Si $campos no fue definido, obtengo todos los campos de la tabla */
	$campos = 'USUARIO.*,RELACION.descripcion AS rel_desc,PERMISO.descripcion AS per_desc,USUARIO_x_RELACION.*';

	$this->db->select($campos);

	/* Si se definio $where lo agrego a la consulta*/
	if($where != null && $where != "") {
	    $where = $this->db->escape_str($where);
	    $this->db->where($where);
	}
	$this->db->join('USUARIO','USUARIO_x_RELACION.id_usuario = USUARIO.id_usuario','left');
	$this->db->join('RELACION','USUARIO_x_RELACION.id_relacion = RELACION.id_relacion','left');
	$this->db->join('PERMISO','USUARIO_x_RELACION.id_permiso = PERMISO.id_permiso','left');

	$query= $this->db->get('USUARIO_x_RELACION');

	$num_filas = $query->num_rows();
	$row=array();
	/* Se recorren todas las filas y se forma el arreglo
         * que contiene la informacion
	*/
	for( $i=0 ; $i<$num_filas ; $i++ ) {
	    $row[$i] = $query->row_array($i);
	}
	return $row;
    }

    public function setUsuarioRelacionPermiso($id_usuario,$id_relacion,$id_permiso,$acceso,$update = FALSE) {
	$this->load->database();

	$data = array('id_usuario' => $id_usuario, 'id_relacion' => $id_relacion, 'id_permiso' => $id_permiso, 'acceso' => $acceso);
	$data = $this->db->escape_str($data);
	if($update) {
	    return $this->db->update('USUARIO_x_RELACION', $data);
	}
	else {
	    return $this->db->insert('USUARIO_x_RELACION',$data);
	}

    }

    public function getUsuarioContacto($where = null) {
	$this->load->database();

	/* Si $campos no fue definido, obtengo todos los campos de la tabla */
	$campos = 'USUARIO.*,RELACION.descripcion AS rel_desc,CONTACTO.*';

	$this->db->select($campos);

	/* Si se definio $where lo agrego a la consulta*/
	if($where != null && $where != "") {
	    $where = $this->db->escape_str($where);
	    $this->db->where($where);
	}
	$this->db->join('USUARIO','CONTACTO.id_contacto = USUARIO.id_usuario','left');
	$this->db->join('RELACION','CONTACTO.id_relacion = RELACION.id_relacion','left');

	$query= $this->db->get('CONTACTO');

	$num_filas = $query->num_rows();
	$row=array();
	/* Se recorren todas las filas y se forma el arreglo
         * que contiene la informacion
	*/
	for( $i=0 ; $i<$num_filas ; $i++ ) {
	    $row[$i] = $query->row_array($i);
	}
	return $row;
    }

    public function setUsuarioContato($id_usuario,$id_contacto,$id_relacion,$update = FALSE) {
	$this->load->database();

	$data = array('id_usuario' => $id_usuario,'id_contacto' => $id_contacto, 'id_relacion' => $id_relacion);
	$data = $this->db->escape_str($data);
	if($update) {
	    return $this->db->update('CONTACTO', $data);
	}
	else {
	    return $this->db->insert('CONTACTO',$data);
	}

    }

    public function delUsuarioContacto($id_usuario,$id_contacto) {
	$this->load->database();

	$data = array('id_usuario' => $id_usuario, 'id_contacto' => $id_contacto);
	$id_propiedad = $this->db->escape_str($data);
	$this->db->where($data);

	return $this->db->delete('CONTACTO');
    }


    public function getUsuarioInteres($where = null) {
	$this->load->database();

	/* Si $campos no fue definido, obtengo todos los campos de la tabla */
	$campos = 'USUARIO.*,INTERES.descripcion AS interes_desc,USUARIO_x_INTERES.*';

	$this->db->select($campos);

	/* Si se definio $where lo agrego a la consulta*/
	if($where != null && $where != "") {
	    $where = $this->db->escape_str($where);
	    $this->db->where($where);
	}
	$this->db->join('USUARIO','USUARIO_x_INTERES.id_usuario = USUARIO.id_usuario','left');
	$this->db->join('INTERES','USUARIO_x_INTERES.id_interes = INTERES.id_interes','left');

	$query= $this->db->get('USUARIO_x_INTERES');

	$num_filas = $query->num_rows();
	$row=array();
	/* Se recorren todas las filas y se forma el arreglo
         * que contiene la informacion
	*/
	for( $i=0 ; $i<$num_filas ; $i++ ) {
	    $row[$i] = $query->row_array($i);
	}
	return $row;
    }

    public function setUsuarioInteres($id_usuario,$id_interes,$nivel = 100,$update = FALSE) {
	$this->load->database();

	$data = array('id_usuario' => $id_usuario,'id_interes' => $id_interes, 'nivel' => $nivel);
	$data = $this->db->escape_str($data);
	if($update) {
	    return $this->db->update('USUARIO_x_INTERES', $data);
	}
	else {
	    return $this->db->insert('USUARIO_x_INTERES',$data);
	}

    }

    public function delUsuarioInteres($id_usuario,$id_interes) {
	$this->load->database();

	$data = array('id_usuario' => $id_usuario,'id_interes' => $id_interes);
	$id_propiedad = $this->db->escape_str($data);
	$this->db->where($data);

	return $this->db->delete('USUARIO_x_INTERES');
    }

    public function getUsuarioPreferencia($where = null) {
	$this->load->database();

	/* Si $campos no fue definido, obtengo todos los campos de la tabla */
	$campos = 'USUARIO.*,PREFERENCIA.descripcion AS interes_desc,USUARIO_x_PREFERENCIA.*';

	$this->db->select($campos);

	/* Si se definio $where lo agrego a la consulta*/
	if($where != null && $where != "") {
	    $where = $this->db->escape_str($where);
	    $this->db->where($where);
	}
	$this->db->join('USUARIO','USUARIO_x_PREFERENCIA.id_usuario = USUARIO.id_usuario','left');
	$this->db->join('PREFERENCIA','USUARIO_x_PREFERENCIA.id_preferencia = PREFERENCIA.id_preferencia','left');

	$query= $this->db->get('USUARIO_x_PREFERENCIA');

	$num_filas = $query->num_rows();
	$row=array();
	/* Se recorren todas las filas y se forma el arreglo
         * que contiene la informacion
	*/
	for( $i=0 ; $i<$num_filas ; $i++ ) {
	    $row[$i] = $query->row_array($i);
	}
	return $row;
    }

    public function setUsuarioPreferencia($id_usuario,$id_preferencia,$update = FALSE) {
	$this->load->database();

	$data = array('id_usuario' => $id_usuario,'id_preferencia' => $id_preferencia);
	$data = $this->db->escape_str($data);
	if($update) {
	    return $this->db->update('USUARIO_x_PREFERENCIA', $data);
	}
	else {
	    return $this->db->insert('USUARIO_x_PREFERENCIA',$data);
	}

    }

    public function delUsuarioPreferencia($id_usuario,$id_preferencia) {
	$this->load->database();

	$data = array('id_usuario' => $id_usuario,'id_preferencia' => $id_preferencia);
	$id_propiedad = $this->db->escape_str($data);
	$this->db->where($data);

	return $this->db->delete('USUARIO_x_PREFERENCIA');
    }

    public function getUsuarioPosicion($where = null) {
	$this->load->database();

	/* Si $campos no fue definido, obtengo todos los campos de la tabla */
	$campos = 'USUARIO.*,POSICION.*,TRAYECTO.*';

	$this->db->select($campos);

	/* Si se definio $where lo agrego a la consulta*/
	if($where != null && $where != "") {
	    $where = $this->db->escape_str($where);
	    $this->db->where($where);
	}
	$this->db->join('USUARIO','TRAYECTO.id_usuario = USUARIO.id_usuario','left');
	$this->db->join('POSICION','TRAYECTO.id_posicion = POSICION.id_posicion','left');

	$query= $this->db->get('TRAYECTO');

	$num_filas = $query->num_rows();
	$row=array();
	/* Se recorren todas las filas y se forma el arreglo
         * que contiene la informacion
	*/
	for( $i=0 ; $i<$num_filas ; $i++ ) {
	    $row[$i] = $query->row_array($i);
	}
	return $row;
    }

    public function setUsuarioPosicion($id_usuario,$id_posicion,$tiempo) {
	$this->load->database();

	$data = array('id_usuario' => $id_usuario,'id_posicion' => $id_posicion,'tiempo' => $tiempo);
	$data = $this->db->escape_str($data);

	return $this->db->insert('TRAYECTO',$data);

    }

    public function delUsuarioPosicion($id_usuario,$id_posicion) {
	$this->load->database();

	$data = array('id_usuario' => $id_usuario,'id_posicion' => $id_posicion);
	$id_propiedad = $this->db->escape_str($data);
	$this->db->where($data);

	return $this->db->delete('TRAYECTO');
    }
}
?>
