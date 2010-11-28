<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of relacion_model
 *
 * @author jorge
 */
class Relacion_model extends Model {
    public function __construct() {
	parent::Model();
    }


    public function getRelacion($where = null) {
	$this->load->database();

	/* Si $campos no fue definido, obtengo todos los campos de la tabla */
	$campos = '*';

	$this->db->select($campos);

	/* Si se definio $where lo agrego a la consulta*/
	if($where != null && $where != "") {
	    $where = $this->db->escape_str($where);
	    $this->db->where($where);
	}

	$query= $this->db->get('RELACION');

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

    public function setRelacion($id_relacion) {
	$this->load->database();

	$data = array('id_relacion' => $id_relacion);
	$data = $this->db->escape_str($data);

	return $this->db->insert('RELACION',$data);

    }

    public function delRelacion($id_relacion) {
	$this->load->database();

	$data = array('id_relacion' => $id_relacion);
	$id_propiedad = $this->db->escape_str($data);
	$this->db->where($data);

	return $this->db->delete('RELACION');
    }
}
?>
