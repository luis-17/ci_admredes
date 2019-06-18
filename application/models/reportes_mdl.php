<?php
 class Reportes_mdl extends CI_Model {

 function Reportes_mdl() {
 parent::__construct(); //llamada al constructor de Model.
 $this->load->database();
 }

 	function getCobros($datos){
 		$this->db->select("c2.cert_id, c1.cert_num, cob_fechCob, cob_vezCob, cob_importe, cont_numDoc, concat(coalesce(cont_ape1,''),' ',coalesce(cont_ape2,''),' ',coalesce(cont_nom1,''),' ',coalesce(cont_nom2,'')) as contratante");
 		$this->db->select("(select count(certase_id) from certificado_asegurado ca where ca.cert_id=c1.cert_id group by ca.cert_id) as num_afiliados");
 		$this->db->from('cobro c1');
 		$this->db->join("certificado c2","c1.cert_id=c2.cert_id");
 		$this->db->join("contratante c3","c2.cont_id=c3.cont_id");
 		$this->db->where("cob_fechCob>='".$datos['inicio']."' and cob_fechCob<='".$datos['fin']."' and c1.plan_id=".$datos['plan']." and cob_importe=".$datos['importe']);
 		$this->db->order_by("cob_fechCob");

 	$cobros = $this->db->get();
	return $cobros->result();
 	} 	

 	function getCobros2($datos){
 		$this->db->select("c2.cert_id, c1.cert_num, cob_fechCob, cob_vezCob, cob_importe, cont_numDoc, concat(coalesce(cont_ape1,''),' ',coalesce(cont_ape2,''),' ',coalesce(cont_nom1,''),' ',coalesce(cont_nom2,'')) as contratante");
 		$this->db->select("(select count(certase_id) from certificado_asegurado ca where ca.cert_id=c1.cert_id group by ca.cert_id) as num_afiliados");
 		$this->db->from('cobro c1');
 		$this->db->join("certificado c2","c1.cert_id=c2.cert_id");
 		$this->db->join("contratante c3","c2.cont_id=c3.cont_id");
 		$this->db->where("cob_fechCob>='".$datos['inicio']."' and cob_fechCob<='".$datos['fin']."' and c1.plan_id=".$datos['plan']);
 		$this->db->order_by("cob_fechCob");

 	$cobros = $this->db->get();
	return $cobros->result();
 	} 	

 	function getImportes($datos){
 		$this->db->select("count(c1.cob_id)as cant,cob_importe, case when cob_importe=round(prima_monto*100) then 'Titular' else concat('Titular + ',ROUND((cob_importe-(prima_monto*100))/(prima_adicional*100))) end as descripcion"); 		
 		$this->db->from('cobro c1'); 	
 		$this->db->join("plan p","p.idplan=c1.plan_id");
 		$this->db->where("cob_fechCob>='".$datos['inicio']."' and cob_fechCob<='".$datos['fin']."' and c1.plan_id=".$datos['plan']);
 		$this->db->group_by("cob_importe");

 	$importe = $this->db->get();
	return $importe->result();
 	}

 	function getCanales(){
 		$this->db->select("idclienteempresa, nombre_comercial_cli");
 		$this->db->from("cliente_empresa");
        //$this->db->where("estado_cli",1);
 		$this->db->order_by("nombre_comercial_cli");

 	$canal = $this->db->get();
 	return $canal->result();
 	}

	function getPlanes2($id){
 		$this->db->select("idplan, nombre_plan, flg_cancelar");
 		$this->db->from("plan");		
    	//$this->db->where("estado_plan",1);
 		$this->db->where("idclienteempresa",$id);

 	$planes = $this->db->get();
 	return $planes->result();
 	}

 	function getPlanes(){
 		$this->db->select("*");
 		$this->db->from("plan p");
 		$this->db->join("cliente_empresa c","p.idclienteempresa=c.idclienteempresa");		
    	//$this->db->where("estado_plan",1);
 		$this->db->order_by("nombre_comercial_cli,nombre_plan");

 	$planes = $this->db->get();
 	return $planes->result();
 	}

 	function cons_atenciones($data){
 		$this->db->select("case when s.idcita is null then concat('OA',num_orden_atencion) else concat('PO',num_orden_atencion) end as tipo_atencion, fecha_atencion, aseg_numDoc, concat(COALESCE(a.aseg_ape1,''),' ',COALESCE(aseg_ape2,''),' ',COALESCE(aseg_nom1,''),' ',COALESCE(aseg_nom2,'')) as asegurado, case when aseg_numDoc=cont_numDoc then 'Titular' else 'Dependiente' end as tipo_afiliado, aseg_telf, p.nombre_comercial_pr, e.nombre_esp, estado_cita, fase_atencion, estado_atencion, estado_siniestro, coalesce(u.username,concat('D: ',u2.username)) as username");
 		$this->db->from("siniestro s");
 		$this->db->join("cita ci","ci.idcita=s.idcita","left");
 		$this->db->join("usuario u ","u.idusuario=ci.idusuario","left"); 		
 		$this->db->join("usuario u2 ","u2.idusuario=s.usuario_crea","left");
 		$this->db->join("asegurado a","s.idasegurado=a.aseg_id");
 		$this->db->join("certificado_asegurado ca","ca.aseg_id=a.aseg_id");
 		$this->db->join("certificado c","s.idcertificado=c.cert_id and ca.cert_id=c.cert_id");
 		$this->db->join("contratante co","c.cont_id=co.cont_id");
 		$this->db->join("especialidad e","e.idespecialidad=s.idespecialidad");
 		$this->db->join("proveedor p","s.idproveedor=p.idproveedor");
 		$this->db->where("fecha_atencion>='".$data['inicio']."' and fecha_atencion<='".$data['fin']."' and plan_id=".$data['plan']." and c.cert_num not like 'PR%'");
 		$this->db->order_by("fecha_atencion, aseg_numDoc");
 	$query = $this->db->get();
 	return $query->result();
 	}

 	function cons_afiliados($data){
 		$this->db->select("c.plan_id,c.cert_id, cert_num, a.aseg_numDoc, concat(COALESCE(a.aseg_ape1,''),' ',COALESCE(aseg_ape2,''),' ',COALESCE(aseg_nom1,''),' ',COALESCE(aseg_nom2,'')) as asegurado, aseg_telf, case when a.aseg_numDoc=co.cont_numDoc then 'Titular' else 'Dependiente' end as tipo, case when ca.idusuario is not null then (select username from usuario where idusuario=ca.idusuario) else case when ca.idusuario_afi is not null then (select username_afi from usuario_afi where idusuario_afi=ca.idusuario_afi)else 'sistemas' end end as username");
 		$this->db->from("asegurado a");
 		$this->db->join("certificado_asegurado ca","a.aseg_id=ca.aseg_id");
 		$this->db->join("certificado c","c.cert_id=ca.cert_id");
 		$this->db->join("contratante co","co.cont_id=c.cont_id");
 		$this->db->where("c.plan_id=".$data['plan']." and ca.cert_estado=".$data['tipo']." and c.cert_num not like 'PR%'");
 	$query = $this->db->get();
 	return $query->result();
 	}

 	function cons_gatenciones($data){
 		$query = $this->db->query("select username, c.idusuario, count(idcita) as atenciones, case when AVG(TIMESTAMPDIFF(minute,c.createdat,c.updatedat))>59 then concat(ROUND(AVG(TIMESTAMPDIFF(hour,c.createdat,c.updatedat))),' hora(s)') else concat(ROUND(AVG(TIMESTAMPDIFF(minute,c.createdat,c.updatedat))),' minuto(s)') end as promedio from cita c
		inner join usuario u on u.idusuario=c.idusuario
		where estado_cita=2 and c.createdat>='".$data['fecinicio']."' and c.createdat<='".$data['fecfin']."'
		group by c.idusuario
		order by c.idusuario");
		return $query->result();
 	}

 	function resumen_clinica_usuario($data){
		$query = $this->db->query("select username, nombre_comercial_pr, count(idcita) as atenciones, case when AVG(TIMESTAMPDIFF(minute,c.createdat,c.updatedat))>59 then concat(ROUND(AVG(TIMESTAMPDIFF(hour,c.createdat,c.updatedat))),' hora(s)') else concat(ROUND(AVG(TIMESTAMPDIFF(minute,c.createdat,c.updatedat))),' minuto(s)') end as promedio 
		 			from cita c
					inner join proveedor p on c.idproveedor=p.idproveedor
					inner join usuario u on u.idusuario=c.idusuario
					where estado_cita=2 and c.createdat>='".$data['ini']."' and c.createdat<='".$data['fin']."' and c.idusuario=".$data['id']."
					group by c.idusuario, c.idproveedor
					order by c.idusuario, c.idproveedor;");
		return $query->result();
 	}

 	function resumen_clinica_usuario2($data){
		$query = $this->db->query("select username, nombre_comercial_pr, count(idcita) as atenciones, case when AVG(TIMESTAMPDIFF(minute,c.createdat,c.updatedat))>59 then concat(ROUND(AVG(TIMESTAMPDIFF(hour,c.createdat,c.updatedat))),' hora(s)') else concat(ROUND(AVG(TIMESTAMPDIFF(minute,c.createdat,c.updatedat))),' minuto(s)') end as promedio 
		 			from cita c
					inner join proveedor p on c.idproveedor=p.idproveedor
					inner join usuario u on u.idusuario=c.idusuario
					where estado_cita=2 and c.createdat>='".$data['fecinicio']."' and c.createdat<='".$data['fecfin']."'
					group by c.idusuario, c.idproveedor
					order by c.idusuario, c.idproveedor;");
		return $query->result();
 	}

 	function getUsuario($id){
 		$query = $this->db->query("select username from usuario where idusuario=$id");
 		return $query->row_array();
 	}

 	function resumen_operador($data){
 		$query = $this->db->query("select u2.username as usuario_reserva, c.createdat, u.username, c.updatedat, nombre_comercial_pr, estado_atencion, num_orden_atencion,
				case when TIMESTAMPDIFF(minute,c.createdat,c.updatedat)>59 then concat((TIMESTAMPDIFF(hour,c.createdat,c.updatedat)),' hora(s)') else concat((TIMESTAMPDIFF(minute,c.createdat,c.updatedat)),' minuto(s)') end as promedio 
				from cita c
				inner join siniestro s on c.idcita=s.idcita
				inner join proveedor p on c.idproveedor=p.idproveedor
				inner join usuario u on u.idusuario=c.idusuario
				inner join usuario u2 on u2.idusuario=c.idusuario_reserva
				where estado_cita=2 and c.createdat>='".$data['ini']."' and c.createdat<='".$data['fin']."' and c.idusuario=".$data['id']."
				order by c.idusuario, c.idproveedor;");
 		return $query->result();
 	}

 	function resumen_operador2($data){
 		$query = $this->db->query("select u2.username as usuario_reserva, c.createdat, u.username, c.updatedat, nombre_comercial_pr, estado_atencion, num_orden_atencion,
				case when TIMESTAMPDIFF(minute,c.createdat,c.updatedat)>59 then concat((TIMESTAMPDIFF(hour,c.createdat,c.updatedat)),' hora(s)') else concat((TIMESTAMPDIFF(minute,c.createdat,c.updatedat)),' minuto(s)') end as promedio 
				from cita c
				inner join siniestro s on c.idcita=s.idcita
				inner join proveedor p on c.idproveedor=p.idproveedor
				inner join usuario u on u.idusuario=c.idusuario
				inner join usuario u2 on u2.idusuario=c.idusuario_reserva
				where estado_cita=2 and c.createdat>='".$data['fecinicio']."' and c.createdat<='".$data['fecfin']."'
				order by c.idusuario, c.idproveedor;");
 		return $query->result();
 	}

 	function incidencia_canal($data){
 		$query = $this->db->query("select nombre_comercial_cli, nombre_plan, tipoincidencia, count(idincidencia) as cant,
							case when AVG(TIMESTAMPDIFF(minute,i.fech_reg,case when fecha_solucion is null then DATE_FORMAT(now(),'%Y-%m-%d %H:%i:%s') else fecha_solucion end))<60 
							then concat(round(AVG(TIMESTAMPDIFF(minute,i.fech_reg,case when fecha_solucion is null then DATE_FORMAT(now(),'%Y-%m-%d %H:%i:%s') else fecha_solucion end))),' minuto(s)') 
							else case when AVG(TIMESTAMPDIFF(hour,i.fech_reg,case when fecha_solucion is null then DATE_FORMAT(now(),'%Y-%m-%d %H:%i:%s') else fecha_solucion end))<24 
							then concat(round(AVG(TIMESTAMPDIFF(hour,i.fech_reg,case when fecha_solucion is null then DATE_FORMAT(now(),'%Y-%m-%d %H:%i:%s') else fecha_solucion end))),' hora(s)') 
							else concat(round(AVG(TIMESTAMPDIFF(day,i.fech_reg,case when fecha_solucion is null then DATE_FORMAT(now(),'%Y-%m-%d %H:%i:%s') else fecha_solucion end))),' día(s)') end
							end as promedio
							from incidencia i 
							inner join certificado c on i.cert_id=c.cert_id
							inner join plan pl on pl.idplan=c.plan_id
							inner join cliente_empresa ce on ce.idclienteempresa=pl.idclienteempresa
							where DATE_FORMAT(i.fech_reg,'%Y-%m-%d')>='".$data['fecinicio']."' and DATE_FORMAT(i.fech_reg,'%Y-%m-%d')<='".$data['fecfin']."'
							GROUP BY tipoincidencia, nombre_plan, nombre_comercial_cli");
 		return $query->result();
 	}

 	function incidencia_usuario($data){
 		$query = $this->db->query("select count(i.idincidencia) as total,  (select count(i2.idincidencia) from incidencia i2 where i2.idusuario_soluciona= idusuario_asignado) as solucionado, idusuario_asignado, username, 
							case when AVG(TIMESTAMPDIFF(minute,i.fech_reg,case when fecha_solucion is null then DATE_FORMAT(now(),'%Y-%m-%d %H:%i:%s') else fecha_solucion end))<60 
							then concat(round(AVG(TIMESTAMPDIFF(minute,i.fech_reg,case when fecha_solucion is null then DATE_FORMAT(now(),'%Y-%m-%d %H:%i:%s') else fecha_solucion end))),' minuto(s)') 
							else case when AVG(TIMESTAMPDIFF(hour,i.fech_reg,case when fecha_solucion is null then DATE_FORMAT(now(),'%Y-%m-%d %H:%i:%s') else fecha_solucion end))<24 
							then concat(round(AVG(TIMESTAMPDIFF(hour,i.fech_reg,case when fecha_solucion is null then DATE_FORMAT(now(),'%Y-%m-%d %H:%i:%s') else fecha_solucion end))),' hora(s)') 
							else concat(round(AVG(TIMESTAMPDIFF(day,i.fech_reg,case when fecha_solucion is null then DATE_FORMAT(now(),'%Y-%m-%d %H:%i:%s') else fecha_solucion end))),' día(s)') end
							end as promedio
								from 
								(select case when idusuario_soluciona is null then (case when i.estado_deriva=0 then idusuario_registra else 
								(select idusuario_recepciona from incidencia_deriva id where id.idincidencia=i.idincidencia and estado=0) end ) else idusuario_soluciona end as idusuario_asignado, idincidencia
								from incidencia i ) a 
								inner join incidencia i on i.idincidencia=a.idincidencia
								inner join usuario u on u.idusuario=a.idusuario_asignado
								where DATE_FORMAT(i.fech_reg,'%Y-%m-%d')>='".$data['fecinicio']."' and DATE_FORMAT(i.fech_reg,'%Y-%m-%d')<='".$data['fecfin']."'
								GROUP BY a.idusuario_asignado");
 		return $query->result();
 	}

 	function detalle_incidencias($data){
 		$query = $this->db->query("select date_format(fech_reg,'%d/%m/%y') as fech_reg, u1.username as registra, nombre_comercial_cli, nombre_plan, aseg_numDoc, concat(coalesce(aseg_ape1,''),' ',coalesce(aseg_ape2,''),' ',coalesce(aseg_nom1,''),' ',coalesce(aseg_nom2)) as asegurado,					tipoincidencia, u2.username as asignado, coalesce(date_format(fecha_solucion,'%d/%m/%y'),'Pendiente') as fecha_solucion, TIMESTAMPDIFF(minute,i.fech_reg,case when fecha_solucion is null then DATE_FORMAT(now(),'%Y-%m-%d %H:%i:%s') else fecha_solucion end)as tiempo
					from incidencia i 
					inner join certificado c on i.cert_id=c.cert_id
					inner join asegurado a2 on i.idasegurado=a2.aseg_id 
					inner join plan p on p.idplan=c.plan_id 
					inner join cliente_empresa ce on ce.idclienteempresa=p.idclienteempresa
					inner join (select case when idusuario_soluciona is null then (case when i.estado_deriva=0 then idusuario_registra else 
					(select idusuario_recepciona from incidencia_deriva id where id.idincidencia=i.idincidencia and estado=0) end ) else idusuario_soluciona end as idusuario_asignado, idincidencia
					from incidencia i )a on a.idincidencia=i.idincidencia
					inner join usuario u1 on i.idusuario_registra=u1.idusuario
					inner join usuario u2 on u2.idusuario = a.idusuario_asignado
					where DATE_FORMAT(i.fech_reg,'%Y-%m-%d')>='".$data['fecinicio']."' and DATE_FORMAT(i.fech_reg,'%Y-%m-%d')<='".$data['fecfin']."'");
 		return $query->result();
	}

	function resumen_general($data){
		$query = $this->db->query("select 
						(select count(idsiniestro)  from siniestro_encuesta where idusuario is not null and estado=1 and DATE_FORMAT(fecha_hora,'%Y-%m-%d')>='".$data['fecinicio']."' and DATE_FORMAT(fecha_hora,'%Y-%m-%d')<='".$data['fecfin']."') as contestaron_telefono,
						(select count(idsiniestro)  from siniestro_encuesta where idusuario is null and estado=1 and DATE_FORMAT(fecha_hora,'%Y-%m-%d')>='".$data['fecinicio']."' and DATE_FORMAT(fecha_hora,'%Y-%m-%d')<='".$data['fecfin']."') as contestaron_correo,
						(select count(idsiniestro) from siniestro_encuesta where estado=2 and DATE_FORMAT(fecha_hora,'%Y-%m-%d')>='".$data['fecinicio']."' and DATE_FORMAT(fecha_hora,'%Y-%m-%d')<='".$data['fecfin']."') as no_contestaron,
						(select count(idsiniestro) from siniestro_encuesta where estado=0 and DATE_FORMAT(fecha_hora,'%Y-%m-%d')>='".$data['fecinicio']."' and DATE_FORMAT(fecha_hora,'%Y-%m-%d')<='".$data['fecfin']."') as no_opinan,
						(select count(idsiniestro) from siniestro where estado_siniestro=1 and estado_atencion='O' and fecha_atencion>='".$data['fecinicio']."' and fecha_atencion<='".$data['fecfin']."') as atenciones");
		return $query->row_array();
	}

	function resumen_canal($data){
		$query = $this->db->query("select count(se.idsiniestro) as num_encuestas, nombre_comercial_cli, SUM(er.valor) as suma  from encuesta_detalle ed
				inner join encuesta_respuesta er on er.idrespuesta=ed.idrespuesta
				inner join siniestro_encuesta se on se.idsiniestro_encuesta=ed.idencuesta
				inner join siniestro s on se.idsiniestro=s.idsiniestro
				inner join certificado c on c.cert_id=s.idcertificado
				inner join plan p on p.idplan=c.plan_id 
				inner join cliente_empresa ce on ce.idclienteempresa=p.idclienteempresa
				where er.idpregunta=7 and DATE_FORMAT(fecha_hora,'%Y-%m-%d')>='".$data['fecinicio']."' and DATE_FORMAT(fecha_hora,'%Y-%m-%d')<='".$data['fecfin']."'
				GROUP BY ce.idclienteempresa");
		return $query->result();
	}

	function resumen_plan($data){
		$query = $this->db->query("select nombre_comercial_cli, nombre_plan, coalesce(num_encuestas,0) as num_encuestas, coalesce(num_encuestas2,0) as num_encuestas2, coalesce(suma,0) as suma, coalesce(suma2,0) as suma2
							from cliente_empresa ce 
							inner join plan p on p.idclienteempresa=ce.idclienteempresa
							left join(
							select count(se.idsiniestro) as num_encuestas, SUM(er.valor) as suma, p.idclienteempresa, p.idplan
							from encuesta_detalle ed
											inner join encuesta_respuesta er on er.idrespuesta=ed.idrespuesta
											inner join siniestro_encuesta se on se.idsiniestro_encuesta=ed.idencuesta
											inner join siniestro s on se.idsiniestro=s.idsiniestro
											inner join certificado c on c.cert_id=s.idcertificado
											inner join plan p on p.idplan=c.plan_id 
											where er.idpregunta=8 and DATE_FORMAT(fecha_hora,'%Y-%m-%d')>='".$data['fecinicio']."' and DATE_FORMAT(fecha_hora,'%Y-%m-%d')<='".$data['fecfin']."'
											GROUP BY p.idplan) a on a.idplan=p.idplan
							left join (	
							select count(se.idsiniestro) as num_encuestas2, SUM(er.valor) as suma2, p.idclienteempresa, p.idplan
							from encuesta_detalle ed
											inner join encuesta_respuesta er on er.idrespuesta=ed.idrespuesta
											inner join siniestro_encuesta se on se.idsiniestro_encuesta=ed.idencuesta
											inner join siniestro s on se.idsiniestro=s.idsiniestro
											inner join certificado c on c.cert_id=s.idcertificado
											inner join plan p on p.idplan=c.plan_id 
											where er.idpregunta=9 and DATE_FORMAT(fecha_hora,'%Y-%m-%d')>='".$data['fecinicio']."' and DATE_FORMAT(fecha_hora,'%Y-%m-%d')<='".$data['fecfin']."'
											GROUP BY p.idplan) b on b.idplan=p.idplan");
		return $query->result();
	}

	function resumen_clinica($data){
		$query = $this->db->query("select nombre_comercial_pr, coalesce(num_encuestas,0) as num_encuestas, coalesce(num_encuestas2,0) as num_encuestas2, suma, suma2 
									from proveedor pr 
									left join 
									(select count(se.idsiniestro) as num_encuestas, SUM(er.valor) as suma, p.idproveedor
									from encuesta_detalle ed
													inner join encuesta_respuesta er on er.idrespuesta=ed.idrespuesta
													inner join siniestro_encuesta se on se.idsiniestro_encuesta=ed.idencuesta
													inner join siniestro s on se.idsiniestro=s.idsiniestro
													inner join proveedor p on p.idproveedor=s.idproveedor
													where er.idpregunta=1 and DATE_FORMAT(fecha_hora,'%Y-%m-%d')>='".$data['fecinicio']."' and DATE_FORMAT(fecha_hora,'%Y-%m-%d')<='".$data['fecfin']."'
													GROUP BY p.idproveedor)a on a.idproveedor=pr.idproveedor
									left join 				
									(select count(se.idsiniestro) as num_encuestas2, SUM(er.valor) as suma2, p.idproveedor
									from encuesta_detalle ed
													inner join encuesta_respuesta er on er.idrespuesta=ed.idrespuesta
													inner join siniestro_encuesta se on se.idsiniestro_encuesta=ed.idencuesta
													inner join siniestro s on se.idsiniestro=s.idsiniestro
													inner join proveedor p on p.idproveedor=s.idproveedor
													where er.idpregunta in(2,3,4,5) and DATE_FORMAT(fecha_hora,'%Y-%m-%d')>='".$data['fecinicio']."' and DATE_FORMAT(fecha_hora,'%Y-%m-%d')<='".$data['fecfin']."'
													GROUP BY p.idproveedor)b on b.idproveedor=pr.idproveedor");
		return $query->result();
	}

	function resumen_usuario($data){
		$query = $this->db->query("select count(se.idsiniestro) as num_encuestas, username, SUM(er.valor) as suma  from encuesta_detalle ed
				inner join encuesta_respuesta er on er.idrespuesta=ed.idrespuesta
				inner join siniestro_encuesta se on se.idsiniestro_encuesta=ed.idencuesta
				inner join siniestro s on se.idsiniestro=s.idsiniestro
				inner join cita c on c.idcita=s.idcita
				inner join usuario u on u.idusuario=c.idusuario
				where er.idpregunta=6 and DATE_FORMAT(fecha_hora,'%Y-%m-%d')>='".$data['fecinicio']."' and DATE_FORMAT(fecha_hora,'%Y-%m-%d')<='".$data['fecfin']."'
				GROUP BY c.idusuario");
		return $query->result();
	}

	function encuesta_detalle($data){
		$query = $this->db->query("select s.num_orden_atencion, nombre_comercial_cli, nombre_plan, aseg_numDoc, concat(coalesce(aseg_ape1,''),' ', coalesce(aseg_ape2,''),' ', coalesce(aseg_nom1,''),' ', coalesce(aseg_nom2,'')) as afiliado, coalesce(u.username,'redes') as username, count(ed.idencuesta)as num_respuestas, SUM(er.valor)as suma, comentario, se.estado, coalesce(u2.username, 'Correo') as medio_calificacion, nombre_comercial_pr
			from siniestro_encuesta se
			left join encuesta_detalle ed on se.idsiniestro_encuesta=ed.idencuesta
			left join encuesta_respuesta er on er.idrespuesta=ed.idrespuesta
			inner join siniestro s on se.idsiniestro=s.idsiniestro
			inner join proveedor pr on pr.idproveedor=s.idproveedor
			inner join asegurado a on a.aseg_id=s.idasegurado
			inner join certificado cer on cer.cert_id=s.idcertificado
			inner join plan p on p.idplan=cer.plan_id 
			inner join cliente_empresa ce on ce.idclienteempresa=p.idclienteempresa
			left join cita c on c.idcita=s.idcita
			left join usuario u on u.idusuario=c.idusuario
			left join usuario u2 on u2.idusuario=se.idusuario
			where DATE_FORMAT(fecha_hora,'%Y-%m-%d')>='".$data['fecinicio']."' and DATE_FORMAT(fecha_hora,'%Y-%m-%d')<='".$data['fecfin']."'
			GROUP BY se.idsiniestro_encuesta");
		return $query->result();
	}
}
?>