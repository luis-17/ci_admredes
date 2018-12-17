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
$route['banco'] = 'Banco_cnt/index';
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
$route['reservar_cita/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'Certificadodetalle_cnt/reservar_cita/$1/$2/$3/$4/$5';
$route['save_cita'] ='Certificadodetalle_cnt/save_cita';
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

$route['consultar_cobros_buscar'] = 'Reportes_cnt/buscar';
$route['consultar_detalle_cobros/(:any)/(:any)/(:any)/(:any)'] = 'Reportes_cnt/detalle_cobros/$1/$2/$3/$4';
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
// Rutas Ariel (Escribir debajo)

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['creaSiniestro'] = 'Siniestro_cnt/creaSiniestro';
$route['guardaGasto'] = 'Siniestro_cnt/guardaGasto';
$route['liquidacion'] = 'Liquidacion_cnt/index';
$route['liquidacion_detalle/(:any)/(:any)'] ='Liquidacion_cnt/getLiquidacionDet/$1/$2';
$route['registraPago'] = 'Liquidacion_cnt/registraPago';
$route['liquidacion_grupo'] = 'Liquidacion_cnt/liquidacion_grupo';
$route['liquidacion_regpago/(:any)/(:any)'] = 'Liquidacion_cnt/liquidacion_regpago/$1/$2';
$route['save_regPago'] = 'Liquidacion_cnt/save_regPago';
$route['imprimir_liquidacion/(:any)/(:any)'] = 'Liquidacion_cnt/liquidacion_pdf/$1/$2';

$route['start_sesion'] = 'Login_cnt/start_sesion';
$route['logout'] = 'Login_cnt/logout';