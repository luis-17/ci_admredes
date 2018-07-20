<?php
  class DiagnosticoCie10_mdl extends CI_Model {
    
      function count_all()
     {
      $query = $this->db->get("diagnostico");
      return $query->num_rows();
     }

     function fetch_details($limit, $start)
     {
      $output = '';
      $this->db->select("codigo_cie, descripcion_cie, cubierto");
      $this->db->from("diagnostico");
      $this->db->order_by("descripcion_cie", "ASC");
      $this->db->limit($limit, $start);
      $query = $this->db->get();
      $output .= '
      <table class="table table-bordered">
       <tr>
        <th>Codigo</th>
        <th>Descripcion</th>
        <th>Cubierto</th>
       </tr>
      ';
      foreach($query->result() as $row)
      {
       $output .= '
       <tr>
        <td>'.$row->codigo_cie.'</td>
        <td>'.$row->descripcion_cie.'</td>
        <td>'.$row->cubierto.'</td>
       </tr>
       ';
      }
      $output .= '</table>';
      return $output;
     }
  }
?>