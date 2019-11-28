<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Login_cnt';

//$route['default_controller'] = 'login';


// Rutas para vistas 
$route['index'] = 'Dashboard/index';
$route['usuario'] = 'Usuario_cnt/index';
$route['accesos'] = 'Accesos_cnt/index';
$route['notificaciones'] = 'Notificaciones_cnt/index';
$route['log_eventos'] = 'Log_eventos_cnt/index';
$route['diagnostico_cie10'] = 'Diagnostico_cie10_cnt/index';
$route['medico'] = 'Medico_cnt/index';
$route['especialidad'] = 'Especialidad_cnt/index';
$route['variables_plan'] = 'Variables_plan_cnt/index';
$route['canal_venta'] = 'Canal_venta_cnt/index';
$route['redes'] = 'Redes_cnt/index';
$route['forma_pago'] = 'Forma_pago_cnt/index';
$route['categoria_elemento'] = 'Categoria_elemento_cnt/index';
$route['elemento'] = 'Elemento_cnt/index';
$route['concepto'] = 'Concepto_cnt/index';
$route['empresa'] = 'Empresa_cnt/index';
$route['dni_prueba'] = 'DniPrueba_cnt/index';
$route['certificado'] = 'Certificado_cnt/index';
$route['incidencias'] = 'Incidencias_cnt/index';
$route['atenciones'] = 'Atenciones_cnt/index';
$route['proveedor'] = 'Proveedor_cnt/index';
$route['contactos_proveedor'] = 'Contactos_proveedor_cnt/index';
$route['persona_natural'] = 'Persona_natural_cnt/index';
$route['persona_juridica'] = 'Persona_juridica_cnt/index';
$route['contactos_cliente'] = 'Contactos_cliente_cnt/index';
$route['categoria'] = 'Categoria_cnt/index';
$route['plan'] = 'Plan_cnt/index';
$route['compras'] = 'Compras_cnt/index';
$route['ventas'] = 'Ventas_cnt/index';
$route['boletaje'] = 'Boletaje_cnt/index';
$route['comprobantes_series'] = 'Comprobantes_series_cnt/index';
$route['historial_compras'] = 'Historial_compras_cnt/index';
$route['historial_ventas'] = 'Historial_ventas_cnt/index';
$route['verificar'] = 'Verificar_cnt/index';
$route['notas'] = 'Notas_cnt/index';
$route['cobros'] = 'Cobros_cnt/index';
$route['consultar_cobros'] = 'Reportes_cnt/index';
$route['consultar_atenciones'] = 'Reportes_cnt/consultar_atenciones';
$route['consultar_afiliados'] = 'Reportes_cnt/consultar_afiliados';
$route['reservas'] = 'Reservas_cnt/index';
$route['afiliacion'] = 'Afiliacion_cnt/index';
$route['denegado/(:any)'] = 'Login_cnt/denegado/$1';
$route['gastos'] = 'Liquidacion_cnt/gastos_proveedor';
$route['pagos'] = 'Liquidacion_cnt/pagos';

$route['planes'] = 'Afiliacion_cnt/plan';
$route['buscar'] = 'Afiliacion_cnt/buscar';
$route['provincia'] = 'Afiliacion_cnt/provincia';
$route['distrito'] = 'Afiliacion_cnt/distrito';
$route['cont_save'] = 'Afiliacion_cnt/cont_save';
$route['aseg_guardar'] = 'Afiliacion_cnt/aseg_guardar';
$route['aseg_editar/(:any)/(:any)'] = 'Afiliacion_cnt/aseg_editar/$1/$2';
$route['aseg_nuevo/(:any)/(:any)'] = 'Afiliacion_cnt/aseg_nuevo/$1/$2';
$route['aseg_save'] = 'Afiliacion_cnt/aseg_save';
$route['aseg_up'] = 'Afiliacion_cnt/aseg_up';
$route['verifica_dni'] = 'Afiliacion_cnt/verifica_dni';
$route['verifica_dni_in'] = 'Afiliacion_cnt/verifica_dni_in';
$route['form_incidencia/(:any)'] = 'Afiliacion_cnt/form_incidencia/$1';
$route['form_cancelado/(:any)/(:any)/(:any)'] = 'Afiliacion_cnt/form_cancelado/$1/$2/$3';
$route['save_incidencia'] = 'Afiliacion_cnt/save_incidencia';
$route['email'] = 'Afiliacion_cnt/email';

$route['proveedor_editar/(:any)'] = 'Proveedor_cnt/proveedor_editar/$1';
$route['proveedor_guardar'] = 'Proveedor_cnt/proveedor_guardar';
$route['proveedor_contactos/(:any)'] = 'Proveedor_cnt/proveedor_contactos/$1';
$route['seleccionar_contacto/(:any)/(:any)'] = 'Proveedor_cnt/seleccionar_contacto/$1/$2';
$route['guardar_contacto'] = 'Proveedor_cnt/guardar_contacto';
$route['habilitar_proveedor/(:any)'] = 'Proveedor_cnt/habilitar/$1';
$route['inhabilitar_proveedor/(:any)'] = 'Proveedor_cnt/inhabilitar/$1';
$route['nuevo_proveedor'] = "Proveedor_cnt/nuevo";
$route['editar_proveedor/(:any)'] = "Proveedor_cnt/editar/$1";
$route['contacto_anular/(:any)/(:any)'] = 'Proveedor_cnt/contacto_anular/$1/$2';
$route['contacto_activar/(:any)/(:any)'] = 'Proveedor_cnt/contacto_activar/$1/$2';

$route['certificado_detalle/(:any)/(:any)'] = 'Certificadodetalle_cnt/index/$1/$2';
$route['aseg_atenciones/(:any)/(:any)'] = 'Certificadodetalle_cnt/aseg_atenciones/$1/$2';
$route['aseg_editar/(:any)'] = 'Certificadodetalle_cnt/aseg_editar/$1';
$route['calendario/(:any)/(:any)'] = 'Calendario_cnt/index/$1/$2';
$route['calendario_guardar'] = 'Calendario_cnt/calendario_guardar';
$route['certificado2/(:any)/(:any)'] = 'Certificado_cnt/index2/$1/$2';
$route['consulta_certificado'] = 'Certificado_cnt/consulta_certificado';
$route['activar_certificado/(:any)/(:any)'] = 'Certificadodetalle_cnt/activar_certificado/$1/$2';
$route['cancelar_certificado/(:any)/(:any)'] = 'Certificadodetalle_cnt/cancelar_certificado/$1/$2';
$route['seleccionar_proveedor/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'Certificadodetalle_cnt/seleccionar_proveedor/$1/$2/$3/$4/$5';
$route['validar_evento/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'Certificadodetalle_cnt/validar_evento/$1/$2/$3/$4/$5';
$route['reservar_cita/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'Certificadodetalle_cnt/reservar_cita/$1/$2/$3/$4/$5/$6';
$route['save_cita'] ='Certificadodetalle_cnt/save_cita';
$route['notificacion_afiliado/(:any)'] = 'Reservas_cnt/notificacion_afiliado/$1';
$route['anular_cita/(:any)/(:any)/(:any)'] = 'Certificadodetalle_cnt/anular_cita/$1/$2/$3';
$route['eliminar_cita'] = 'Certificadodetalle_cnt/eliminar_cita';
$route['generarPdf/(:any)/(:any)'] = 'Ventas_cnt/generarPdf/$1/$2';
$route['enviarPdf/(:any)/(:any)'] = 'Ventas_cnt/enviarPdf/$1/$2';

$route['cert_cont_save'] = 'Certificadodetalle_cnt/cert_cont_save';
$route['cert_aseg_save'] = 'Certificadodetalle_cnt/cert_aseg_up';

$route['siniestro/(:any)'] = 'Siniestro_cnt/siniestro/$1';
$route['atenciones/(:any)'] = 'Atenciones_cnt/atenciones/$1';
$route['add_diagnostico/(:any)'] = 'Siniestro_cnt/add_diagnostico/$1';
$route['add_diagnosticoSec/(:any)'] = 'Siniestro_cnt/add_diagnosticoSec/$1';
$route['diagnostico_cie10'] = 'DiagnosticoCie10_cnt/index';
$route['guardaTriaje'] = 'Siniestro_cnt/guardaTriaje';
$route['guardaDiagP'] = 'Siniestro_cnt/guardaDiagP';
$route['guardaDiagS'] = 'Siniestro_cnt/guardaDiagS';
$route['add_tratamiento/(:any)'] = 'Siniestro_cnt/add_tratamiento/$1';
$route['add_tratamientoSec/(:any)'] = 'Siniestro_cnt/add_tratamientoSec/$1';
$route['guardaMediP'] = 'Siniestro_cnt/guardaMediP';
$route['guardaMediS'] = 'Siniestro_cnt/guardaMediS';
$route['edit_medi/(:num)/(:any)'] = 'Siniestro_cnt/edit_medi/$1/$2';
$route['guardaEditMed'] = 'Siniestro_cnt/guardaEditMed';
$route['delete_trata/(:num)/(:any)'] = 'Siniestro_cnt/delete_trata/$1/$2';
$route['eliminar_diagnostico/(:any)/(:any)'] = 'Siniestro_cnt/eliminar_diagnostico/$1/$2';
$route['save_siniestro_analisis'] = "siniestro_cnt/save_siniestro_analisis";

$route['search'] = "siniestro_cnt/search";

$route['orden/(:any)/(:any)'] = 'atenciones_cnt/orden/$1/$2';

$route['plan_cobertura/(:any)/(:any)'] = 'plan_cnt/plan_cobertura/$1/$2';
$route['plan_editar/(:any)/(:any)'] = 'plan_cnt/plan_editar/$1/$2';
$route['plan_registrar'] = 'plan_cnt/plan_registrar';
$route['plan_guardar'] = 'plan_cnt/plan_guardar';
$route['guardar_cobertura'] = 'plan_cnt/guardar_cobertura';
$route['plan_anular/(:any)'] = 'plan_cnt/plan_anular/$1';
$route['plan_activar/(:any)'] = 'plan_cnt/plan_activar/$1';
$route['plan_email/(:any)/(:any)'] = 'plan_cnt/plan_email/$1/$2';
$route['guardar_email'] = 'plan_cnt/guardar_email';
$route['cobertura_anular/(:any)/(:any)/(:any)'] = 'plan_cnt/cobertura_anular/$1/$2/$3';
$route['cobertura_activar/(:any)/(:any)/(:any)'] = 'plan_cnt/cobertura_activar/$1/$2/$3';
$route['seleccionar_cobertura/(:any)/(:any)/(:any)'] = 'plan_cnt/seleccionar_cobertura/$1/$2/$3';

$route['plan_proveedor/(:any)'] = 'plan_cnt/plan_proveedor/$1';
$route['save_proveedor'] = 'plan_cnt/save_proveedor';
$route['plan_proveedor'] = 'plan_cnt/plan_proveedor';

$route['consultar_cobros_buscar'] = 'Reportes_cnt/buscar';
$route['consultar_detalle_cobros/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'Reportes_cnt/detalle_cobros/$1/$2/$3/$4/$5';
$route['exc_cobros/(:any)/(:any)/(:any)'] = 'Reportes_cnt/exc_cobros/$1/$2/$3';

$route['consultar_atenciones_buscar'] = 'Reportes_cnt/consultar_atenciones_buscar';
$route['consultar_afiliados_buscar'] = 'Reportes_cnt/consultar_afiliados_buscar';
$route['actualizar_pass'] = 'Perfil/actualizar_pass';
$route['cambiar_pass'] = 'Perfil/cambiar_pass';
$route['canal_anular/(:any)'] = 'Persona_juridica_cnt/canal_anular/$1';
$route['canal_activar/(:any)'] = 'Persona_juridica_cnt/canal_activar/$1';
$route['canal_registrar'] = 'Persona_juridica_cnt/canal_registrar';
$route['canal_editar/(:any)'] = 'Persona_juridica_cnt/canal_editar/$1';
$route['canal_guardar'] = 'Persona_juridica_cnt/canal_guardar';

$route['detalle_producto'] = 'Plan_cnt/detalle_producto';
$route['eliminar_producto/(:any)/(:any)/(:any)/(:any)'] = 'Plan_cnt/eliminar_producto/$1/$2/$3/$4';
$route['insertar_producto/(:any)/(:any)/(:any)/(:any)'] = 'Plan_cnt/insertar_producto/$1/$2/$3/$4';
$route['reenviar_proveedor/(:any)'] = 'Certificadodetalle_cnt/reenviar_proveedor/$1';
$route['reenviar_afiliado/(:any)'] = 'Certificadodetalle_cnt/reenviar_afiliado/$1';
$route['registrar_incidencia/(:any)/(:any)'] =  'Certificadodetalle_cnt/registrar_incidencia/$1/$2';
$route['save_incidencia'] = 'Certificadodetalle_cnt/save_incidencia';
$route['notificar/(:any)'] = 'Reservas_cnt/notificar/$1';

$route['derivar_incidencia/(:any)'] = 'Incidencias_cnt/derivar_incidencia/$1';
$route['reg_derivacion'] = 'Incidencias_cnt/reg_derivacion';
$route['solucion_incidencia/(:any)'] = 'Incidencias_cnt/solucion_incidencia/$1';
$route['reg_solucion'] = 'Incidencias_cnt/reg_solucion';
$route['historial/(:any)'] = 'Incidencias_cnt/historial/$1'; 

$route['save_liqgrupo'] = 'Liquidacion_cnt/save_liqgrupo';
$route['view_reenviar/(:any)/(:any)'] = 'Liquidacion_cnt/view_reenviar/$1/$2';
$route['reenviar_liquidacion'] = 'Liquidacion_cnt/reenviar_liquidacion';
$route['variable_anular/(:any)'] = 'Variables_plan_cnt/variable_anular/$1';
$route['variable_activar/(:any)'] = 'Variables_plan_cnt/variable_activar/$1';
$route['editar_variable/(:any)'] = 'Variables_plan_cnt/editar_variable/$1';
$route['detalle_variable/(:any)'] = 'Variables_plan_cnt/detalle_variable/$1';
$route['variable_registrar'] = 'Variables_plan_cnt/variable_registrar';
$route['variable_guardar'] = 'Variables_plan_cnt/variable_guardar';
$route['detalle_variable/(:any)'] = 'Variables_plan_cnt/detalle_variable/$1';
$route['seleccionar_detalle/(:any)'] ='Variables_plan_cnt/seleccionar_detalle/$1';
$route['save_producto'] = 'Variables_plan_cnt/save_producto';
$route['delete_producto/(:any)/(:any)'] = 'Variables_plan_cnt/delete_producto/$1/$2';
$route['usuario_registrar'] = 'Usuario_cnt/usuario_registrar';
$route['usuario_editar/(:any)'] = 'Usuario_cnt/usuario_editar/$1';
$route['usuario_guardar'] = 'Usuario_cnt/usuario_guardar';
$route['usuario_roles/(:any)'] = 'Usuario_cnt/usuario_roles/$1';
$route['save_roles'] = 'Usuario_cnt/save_roles';
$route['detalle_roles'] = 'Usuario_cnt/detalle_roles';
$route['usuario_anular/(:any)'] = 'Usuario_cnt/usuario_anular/$1';
$route['usuario_activar/(:any)'] = 'Usuario_cnt/usuario_activar/$1';
$route['reenviar_usuario/(:any)'] = 'Usuario_cnt/reenviar_usuario/$1';
$route['nuevo_afiliado'] = 'DniPrueba_cnt/nuevo_afiliado';
$route['verifica_dni_in2'] = 'DniPrueba_cnt/verifica_dni_in';
$route['cont_save2'] = 'DniPrueba_cnt/cont_save';
$route['baja/(:any)/(:any)'] = 'DniPrueba_cnt/baja/$1/$2';
$route['planesDni'] = 'Atenciones_cnt/getPlanesDni';
$route['especialidadPlan'] = 'Atenciones_cnt/especialidadPlan';
$route['reg_siniestro'] = 'Atenciones_cnt/reg_siniestro';
$route['pago_detalle/(:any)'] = 'Liquidacion_cnt/pagoDetalle/$1';
// Rutas Ariel (Escribir debajo)

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['creaSiniestro'] = 'Siniestro_cnt/creaSiniestro';
$route['guardaGasto'] = 'Siniestro_cnt/guardaGasto';
$route['pre_liquidacion/(:any)'] = 'Liquidacion_cnt/pre_liquidacion/$1';
$route['liquidacion_detalle/(:any)/(:any)'] ='Liquidacion_cnt/getLiquidacionDet/$1/$2';
$route['registraPago'] = 'Liquidacion_cnt/registraPago';
$route['liquidacion_grupo'] = 'Liquidacion_cnt/liquidacion_grupo';
$route['liquidacion_regpago/(:any)'] = 'Liquidacion_cnt/liquidacion_regpago/$1';
$route['save_regPago'] = 'Liquidacion_cnt/save_regPago';
$route['imprimir_liquidacion/(:any)'] = 'Liquidacion_cnt/liquidacion_pdf/$1';
$route['imprimir_liquidacion2/(:any)/(:any)'] = 'Liquidacion_cnt/liquidacion_pdf2/$1/$2';
$route['generar_cobros'] = 'Persona_juridica_cnt/generar_cobros';
$route['buscar_cobros'] = 'Persona_juridica_cnt/buscar_cobros';
$route['registrar_cobros'] ='Persona_juridica_cnt/registrar_cobros';

$route['solicitud_cancelacion'] = 'Persona_juridica_cnt/solicitud_cancelacion';
$route['aceptar_solicitud/(:any)'] = 'Persona_juridica_cnt/aceptar_solicitud/$1';
$route['rechazar_solicitud/(:any)'] = 'Persona_juridica_cnt/rechazar_solicitud/$1';
$route['save_rechazo'] = 'Persona_juridica_cnt/save_rechazo';

$route['agrupar_liquidacion/(:any)'] = 'Liquidacion_cnt/agrupar_liquidacion/$1';
$route['save_gasto'] = 'Liquidacion_cnt/save_gasto';
$route['anular_siniestro/(:any)/(:any)'] = 'Atenciones_cnt/anular_siniestro/$1/$2';
$route['reactivar_siniestro/(:any)/(:any)'] = 'Atenciones_cnt/reactivar_siniestro/$1/$2';
$route['restablecer_siniestro/(:any)/(:any)'] = 'Atenciones_cnt/restablecer_siniestro/$1/$2';
$route['gestion_atenciones'] = 'Reportes_cnt/gestion_atenciones';
$route['gestion_atenciones_buscar'] = 'Reportes_cnt/gestion_atenciones_buscar';
$route['resumen_clinica_usuario/(:any)/(:any)/(:any)'] = 'Reportes_cnt/resumen_clinica_usuario/$1/$2/$3';
$route['resumen_operador/(:any)/(:any)/(:any)'] = 'Reportes_cnt/resumen_operador/$1/$2/$3';
$route['registrar_evento/(:any)'] = 'Incidencias_cnt/registrar_evento/$1';
$route['reg_evento'] = 'Incidencias_cnt/reg_evento';
$route['historial_incidencias/(:any)/(:any)'] = 'Incidencias_cnt/historial_incidencias/$1/$2';
$route['resolver_incidencia/(:any)/(:any)'] = 'Incidencias_cnt/reg_solucion_mail/$1/$2';
$route['save_solucion'] = 'Incidencias_cnt/save_solucion';
$route['consulta_incidencias'] = 'Reportes_cnt/consultar_incidencias';
$route['consulta_incidencias_buscar'] = 'Reportes_cnt/consulta_incidencias_buscar';
$route['post_venta'] = 'PostVenta_cnt/index';
$route['encuesta/(:any)'] = 'PostVenta_cnt/encuesta/$1';
$route['no_contesta/(:any)'] = 'PostVenta_cnt/no_contesta/$1';
$route['encuesta_mail/(:any)'] = 'PostVenta_cnt/encuesta_mail/$1';
$route['save_sincalificar'] = 'PostVenta_cnt/save_sincalificar';
$route['save_calificar'] = 'PostVenta_cnt/save_calificar';
$route['save_calificar_mail'] = 'PostVenta_cnt/save_calificar_mail';
$route['eventos/(:any)'] = 'Plan_cnt/eventos/$1';
$route['reg_evento'] = 'Plan_cnt/reg_evento';
$route['bloqueo/(:any)'] = 'Plan_cnt/bloqueo/$1';
$route['reg_bloqueo'] = 'Plan_cnt/reg_bloqueo';
$route['coaseguro/(:any)'] = 'Plan_cnt/coaseguro/$1';
$route['reg_coaseguro'] = 'Plan_cnt/reg_coaseguro';
$route['anular_coaseguro/(:any)/(:any)'] = 'Plan_cnt/anular_coaseguro/$1/$2';
$route['anular_bloqueo/(:any)/(:any)'] = 'Plan_cnt/anular_bloqueo/$1/$2';
$route['servicios'] = 'Proveedor_cnt/servicios';
$route['nuevo_servicio'] = 'Proveedor_cnt/nuevo_servicio';
$route['editar_servicio/(:any)'] = 'Proveedor_cnt/editar_servicio/$1';
$route['save_servicio'] = 'Proveedor_cnt/save_servicio';
$route['proveedor_servicios/(:any)'] = 'Proveedor_cnt/proveedor_servicios/$1';
$route['guardar_servicio'] = 'Proveedor_cnt/guardar_servicio';
$route['eliminar_servicio/(:any)/(:any)'] = 'Proveedor_cnt/eliminar_servicio/$1/$2';
$route['consultar_atenciones_buscar2'] = 'Atenciones_cnt/consultar_atenciones_buscar';
$route['capacitaciones'] = 'Proveedor_cnt/capacitaciones';
$route['nueva_capacitacion'] = 'Proveedor_cnt/nueva_capacitacion';
$route['capacitacion_guardar'] = 'Proveedor_cnt/capacitacion_guardar';
$route['editar_capacitacion/(:any)'] = 'Proveedor_cnt/editar_capacitacion/$1';
$route['cerrar_capacitacion/(:any)/(:any)'] = 'Proveedor_cnt/cerrar_capacitacion/$1/$2';
$route['save_finCapacitacion'] = 'Proveedor_cnt/save_finCapacitacion';
$route['capacitaciones2'] = 'Proveedor_cnt/capacitaciones2';
$route['centro_costos'] = 'Plan_cnt/centro_costos';
$route['add_cc/(:any)'] = 'Plan_cnt/add_cc/$1';
$route['reg_cc'] = 'Plan_cnt/reg_cc';
$route['series'] = 'Series_cnt/series';
$route['add_serie/(:any)'] = 'Series_cnt/add_serie/$1';
$route['del_serie/(:any)'] = 'Series_cnt/del_serie/$1';
$route['medicamentos'] = 'Medicamentos_cnt/medicamentos';
$route['medicamentos_anular/(:any)'] = 'Medicamentos_cnt/medicamentos_anular/$1';
$route['medicamentos_activar/(:any)'] = 'Medicamentos_cnt/medicamentos_activar/$1';
$route['medicamentos_editar/(:any)'] = 'Medicamentos_cnt/medicamentos_editar/$1';
$route['medicamentos_guardar'] = 'Medicamentos_cnt/medicamentos_guardar';
$route['diagnosticos'] = 'Diagnosticos_cnt/diagnosticos';
$route['diagnosticos_anular/(:any)'] = 'Diagnosticos_cnt/diagnosticos_anular/$1';
$route['diagnosticos_activar/(:any)'] = 'Diagnosticos_cnt/diagnosticos_activar/$1';
$route['diagnosticos_detalle/(:any)'] = 'Diagnosticos_cnt/diagnosticos_detalle/$1';
$route['diagnosticos_editar/(:any)'] = 'Diagnosticos_cnt/diagnosticos_editar/$1';
$route['del_medicamento/(:any)/(:any)'] = 'Diagnosticos_cnt/del_medicamento/$1/$2';
$route['add_medicamento/(:any)/(:any)'] = 'Diagnosticos_cnt/add_medicamento/$1/$2';
$route['del_producto/(:any)/(:any)'] = 'Diagnosticos_cnt/del_producto/$1/$2';
$route['add_producto/(:any)/(:any)'] = 'Diagnosticos_cnt/add_producto/$1/$2';
$route['diagnosticos_guardar'] = 'Diagnosticos_cnt/diagnosticos_guardar';
$route['diagnosticos_add'] = 'Diagnosticos_cnt/diagnosticos_add';
$route['del_medicamento_save/(:any)/(:any)'] = 'Diagnosticos_cnt/del_medicamento_save/$1/$2';
$route['add_medicamento_save/(:any)/(:any)'] = 'Diagnosticos_cnt/add_medicamento_save/$1/$2';
$route['del_producto_save/(:any)/(:any)'] = 'Diagnosticos_cnt/del_producto_save/$1/$2';
$route['add_producto_save/(:any)/(:any)'] = 'Diagnosticos_cnt/add_producto_save/$1/$2';
$route['diagnosticos_add/(:any)'] = 'Diagnosticos_cnt/diagnosticos_add/$1';
$route['red_medica'] = 'Proveedor_cnt/red_medica';
$route['red_medica2'] = 'Proveedor_cnt/red_medica2';
$route['addresponsable/(:any)/(:any)'] = 'Plan_cnt/addresponsable/$1/$2';
$route['reg_plan_usuario'] = 'Plan_cnt/reg_plan_usuario';
$route['eliminar_responsable/(:any)/(:any)/(:any)'] = 'Plan_cnt/eliminar_responsable/$1/$2/$3';
$route['otros_proveedores'] = 'Proveedor_otros_cnt/index';
$route['nuevo_proveedor_int'] = 'Proveedor_otros_cnt/nuevo';
$route['proveedor_otros_guardar'] = 'Proveedor_otros_cnt/proveedor_guardar';
$route['proveedor_otros_editar/(:any)'] = 'Proveedor_otros_cnt/proveedor_editar/$1';
$route['mesa_partes'] = 'Mesa_partes_cnt/index';
$route['consultar_orden'] = 'Mesa_partes_cnt/consultar_orden';
$route['guardar_recepcion'] = 'Mesa_partes_cnt/guardar_recepcion';
$route['consultar_ruc'] = 'Mesa_partes_cnt/consultar_ruc';
$route['guardar_recepcion2'] = 'Mesa_partes_cnt/guardar_recepcion2';
$route['nuevo_otro_proveedor'] = 'Proveedor_otros_cnt/nuevo_otro_proveedor';
$route['proveedor_otros_guardar2'] = 'Proveedor_otros_cnt/proveedor_otros_guardar2';
$route['nueva_orden'] = 'Atenciones_cnt/nueva_orden';
$route['reg_siniestro2'] = 'Atenciones_cnt/reg_siniestro2';
$route['mesa_partes2/(:any)'] = 'Mesa_partes_cnt/mesa_partes2/$1';
$route['mesa_partes3/(:any)'] = 'Mesa_partes_cnt/mesa_partes3/$1';
$route['consultar_recepciones_buscar'] = 'Mesa_partes_cnt/consultar_recepciones_buscar';
$route['editar_recepcion/(:any)'] = 'Mesa_partes_cnt/editar_recepcion/$1';
$route['reg_recepcion'] = 'Mesa_partes_cnt/reg_recepcion';
$route['guardar_recepcion3'] = 'Mesa_partes_cnt/guardar_recepcion3';
$route['reg_recepcion3'] = 'Mesa_partes_cnt/reg_recepcion3';
$route['ruc'] = 'Mesa_partes_cnt/ruc';
$route['siniestros'] = 'Siniestro_cnt/registrar_siniestro';
$route['seleccionar_factura/(:any)/(:any)'] = 'Siniestro_cnt/seleccionar_factura/$1/$2';
$route['cotizador'] = 'Cotizador_cnt/index';
$route['nueva_cotizacion'] = 'Cotizador_cnt/nueva_cotizacion';
$route['guardar_cotizacion'] = 'Cotizador_cnt/guardar_cotizacion';
$route['coberturas_cotizacion/(:any)/(:any)'] = 'Cotizador_cnt/coberturas_cotizacion/$1/$2';
$route['cotizacion_cobertura/(:any)/(:any)'] = 'Cotizador_cnt/cotizacion_cobertura/$1/$2';
$route['detalle_producto2'] = 'Cotizador_cnt/detalle_producto';
$route['guardar_cotcobertura'] = 'Cotizador_cnt/guardar_cotcobertura';
$route['cotizacion_variables/(:any)/(:any)'] = 'Cotizador_cnt/cotizacion_variables/$1/$2';
$route['guardar_variables'] = 'Cotizador_cnt/guardar_variables';
$route['visualizar_cotizacion/(:any)/(:any)'] = 'Cotizador_cnt/visualizar_cotizacion/$1/$2';
$route['coaseguro_cotizacion/(:any)/(:any)'] = 'Cotizador_cnt/coaseguro_cotizacion/$1/$2';
$route['reg_cotcoaseguro'] = 'Cotizador_cnt/reg_cotcoaseguro';
$route['guardar_primas'] = 'Cotizador_cnt/guardar_primas';
$route['propuesta_comercial/(:any)/(:any)'] = 'Cotizador_cnt/propuesta_comercial/$1/$2';
$route['eventos_cotizacion/(:any)'] = 'Cotizador_cnt/eventos_cotizacion/$1';
$route['reg_evento_cotizacion'] = 'Cotizador_cnt/reg_evento_cotizacion';
$route['sol_apGerencia'] = 'Cotizador_cnt/sol_apGerencia';
$route['cot_pendientes'] = 'Cotizador_cnt/cot_pendientes';
$route['editar_cotizacion/(:any)/(:any)'] = 'Cotizador_cnt/editar_cotizacion/$1/$2';
$route['desaprobarCot/(:any)'] = 'Cotizador_cnt/desaprobarCot/$1';
$route['guardar_desaprobacion'] = 'Cotizador_cnt/guardar_desaprobacion';
$route['revision_propuesta/(:any)/(:any)/(:any)/(:any)'] = 'Cotizador_cnt/revision_propuesta/$1/$2/$3/$4';
$route['aprobCot/(:any)'] = 'Cotizador_cnt/aprobCot/$1';
$route['cotizacion_rechazo/(:any)'] = 'Cotizador_cnt/cotizacion_rechazo/$1';
$route['duplicar_data/(:any)/(:any)'] = 'Cotizador_cnt/duplicar_data/$1/$2';
$route['cotizacion_aprobacion/(:any)/(:any)'] = 'Cotizador_cnt/cotizacion_aprobacion/$1/$2';
$route['generar_cotPdf/(:any)'] = 'Cotizador_cnt/generar_cotPdf/$1';
$route['aceptar_cliente/(:any)'] = 'Cotizador_cnt/aceptar_cliente/$1';
$route['bloqueo_cot/(:any)'] = 'Cotizador_cnt/bloqueo_cot/$1';
$route['reg_bloqueo_cot'] = 'Cotizador_cnt/reg_bloqueo_cot';
$route['anular_bloqueo_cot/(:any)/(:any)'] = 'Cotizador_cnt/anular_bloqueo_cot/$1/$2';
$route['seleccionar_cobertura_cot/(:any)/(:any)/(:any)'] = 'Cotizador_cnt/seleccionar_cobertura_cot/$1/$2/$3';

$route['eliminar_producto_cot/(:any)/(:any)/(:any)/(:any)'] = 'Cotizador_cnt/eliminar_producto/$1/$2/$3/$4';
$route['insertar_producto_cot/(:any)/(:any)/(:any)/(:any)'] = 'Cotizador_cnt/insertar_producto/$1/$2/$3/$4';


$route['consultar_postVenta'] = 'Reportes_cnt/consultar_postVenta';
$route['consultar_postVenta_buscar'] = 'Reportes_cnt/consultar_postVenta_buscar';

$route['consultar_siniestros'] = 'Reportes_cnt/consultar_siniestros';
$route['consultar_siniestros_buscar'] = 'Reportes_cnt/consultar_siniestros_buscar';

$route['consultas'] = 'Login_cnt/consultas';

$route['detalle_plan'] = 'Login_cnt/detalle_plan';
$route['diagnosticos_detalle2/(:any)/(:any)'] = 'Login_cnt/diagnosticos_detalle2/$1/$2';


$route['liq_pagadas'] = 'Reportes_cnt/liq_pagadas';
$route['pago_detalle2/(:any)'] = 'Reportes_cnt/pago_detalle2/$1';

$route['start_sesion'] = 'Login_cnt/start_sesion';
$route['logout'] = 'Login_cnt/logout';
