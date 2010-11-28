<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of lugar_model
 *
 * @author jorge
 */
class Lugar_model extends Model{
    public function __construct() {
	parent::Model();
    }


    public function getLugar($where = null) {
	$this->load->database();

	/* Si $campos no fue definido, obtengo todos los campos de la tabla */
	$campos = '*,POSICION.*';

	$this->db->select($campos);

	$this->db->join('POSICION','LUGAR.id_lugar = POSICION.id_posicion','left');
	
	/* Si se definio $where lo agrego a la consulta*/
	if($where != null && $where != "") {
	    $where = $this->db->escape_str($where);
	    $this->db->where($where);
	}


	$query= $this->db->get('LUGAR');

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

    public function setLugar($id_lugar) {
	$this->load->database();

	$data = array('id_lugar' => $id_lugar);
	$data = $this->db->escape_str($data);

	return $this->db->insert('LUGAR',$data);

    }

    public function delLugar($id_lugar) {
	$this->load->database();

	$data = array('id_lugar' => $id_lugar);
	$id_propiedad = $this->db->escape_str($data);
	$this->db->where($data);

	return $this->db->delete('LUGAR');
    }
}
?>
