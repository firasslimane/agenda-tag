<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of online_users_model
 *
 * @author jorge
 */
class Online_users_model extends Model {
    public function __construct() {
	parent::Model();
    }


    public function getOnlineUsers($where = null) {
	$this->load->database();

	/* Si $campos no fue definido, obtengo todos los campos de la tabla */
	$campos = '*';

	$this->db->select($campos);

	/* Si se definio $where lo agrego a la consulta*/
	if($where != null && $where != "") {
	    $where = $this->db->escape_str($where);
	    $this->db->where($where);
	}

	$query= $this->db->get('ONLINE_USERS');

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

    public function setOnlineUser($data,$id_usuario = null) {
	$this->load->database();

	$data = $this->db->escape_str($data);
	if(isset ($id_usuario)) {
	    $id_usuario = $this->db->escape_str($id_usuario);
	    $this->db->where('id_usuario',$id_usuario);
	    return $this->db->update('ONLINE_USERS', $data);
	}
	else
	    return $this->db->insert('ONLINE_USERS',$data);

    }

    public function delOnlineUser($id_usuario) {
	$this->load->database();

	$data = array('id_usuario' => $id_usuario);
	$id_propiedad = $this->db->escape_str($data);
	$this->db->where($data);

	return $this->db->delete('ONLINE_USERS');
    }
}
?>
