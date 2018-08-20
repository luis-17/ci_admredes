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
$route['default_controller'] = 'login_cnt/index';

//$route['default_controller'] = 'login';


// Rutas para vistas 
$route['index'] = 'Dashboard/index';
$route['usuario'] = 'usuario_cnt/index';
$route['accesos'] = 'accesos_cnt/index';
$route['notificaciones'] = 'notificaciones_cnt/index';
$route['log_eventos'] = 'log_eventos_cnt/index';
$route['diagnostico_cie10'] = 'diagnostico_cie10_cnt/index';
$route['medico'] = 'medico_cnt/index';
$route['especialidad'] = 'especialidad_cnt/index';
$route['variables_plan'] = 'variables_plan_cnt/index';
$route['canal_venta'] = 'canal_venta_cnt/index';
$route['redes'] = 'redes_cnt/index';
$route['forma_pago'] = 'forma_pago_cnt/index';
$route['categoria_elemento'] = 'categoria_elemento_cnt/index';
$route['elemento'] = 'elemento_cnt/index';
$route['concepto'] = 'concepto_cnt/index';
$route['empresa'] = 'empresa_cnt/index';
$route['banco'] = 'banco_cnt/index';
$route['certificado'] = 'certificado_cnt/index';
$route['incidencias'] = 'incidencias_cnt/index';
$route['atenciones'] = 'atenciones_cnt/index';
$route['proveedor'] = 'proveedor_cnt/index';
$route['contactos_proveedor'] = 'contactos_proveedor_cnt/index';
$route['persona_natural'] = 'persona_natural_cnt/index';
$route['persona_juridica'] = 'persona_juridica_cnt/index';
$route['contactos_cliente'] = 'contactos_cliente_cnt/index';
$route['categoria'] = 'categoria_cnt/index';
$route['plan'] = 'plan_cnt/index';
$route['compras'] = 'compras_cnt/index';
$route['ventas'] = 'ventas_cnt/index';
$route['boletaje'] = 'boletaje_cnt/index';
$route['comprobantes_series'] = 'comprobantes_series_cnt/index';
$route['historial_compras'] = 'historial_compras_cnt/index';
$route['historial_ventas'] = 'historial_ventas_cnt/index';
$route['cobros'] = 'cobros_cnt/index';
$route['consultar_cobros'] = 'Reportes_cnt/index';
$route['afiliacion'] = 'Afiliacion_cnt/index';

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

$route['proveedor_editar/(:any)/(:any)'] = 'proveedor_cnt/proveedor_editar/$1/$2';
$route['proveedor_guardar'] = 'proveedor_cnt/proveedor_guardar';
$route['habilitar_proveedor/(:any)'] = 'proveedor_cnt/habilitar/$1';
$route['inhabilitar_proveedor/(:any)'] = 'proveedor_cnt/inhabilitar/$1';
$route['nuevo_proveedor'] = "proveedor_cnt/nuevo";
$route['editar_proveedor/(:any)'] = "proveedor_cnt/editar/$1";

$route['certificado_detalle/(:any)/(:any)'] = 'certificadodetalle_cnt/index/$1/$2';
$route['aseg_atenciones/(:any)/(:any)'] = 'certificadodetalle_cnt/aseg_atenciones/$1/$2';
$route['aseg_editar/(:any)'] = 'certificadodetalle_cnt/aseg_editar/$1';
$route['calendario/(:any)/(:any)'] = 'calendario_cnt/index/$1/$2';
$route['calendario_guardar'] = 'calendario_cnt/calendario_guardar';
$route['certificado2/(:any)/(:any)'] = 'certificado_cnt/index2/$1/$2';
$route['consulta_certificado'] = 'certificado_cnt/consulta_certificado';
$route['activar_certificado/(:any)/(:any)'] = 'certificadodetalle_cnt/activar_certificado/$1/$2';
$route['cancelar_certificado/(:any)/(:any)'] = 'certificadodetalle_cnt/cancelar_certificado/$1/$2';
$route['reservar_cita/(:any)/(:any)/(:any)/(:any)'] = 'certificadodetalle_cnt/reservar_cita/$1/$2/$3/$4';
$route['save_cita'] ='certificadodetalle_cnt/save_cita';
$route['cert_cont_save'] = 'certificadodetalle_cnt/cert_cont_save';
$route['cert_aseg_save'] = 'certificadodetalle_cnt/cert_aseg_up';

$route['siniestro/(:any)'] = 'siniestro_cnt/siniestro/$1';
$route['atenciones/(:any)'] = 'atenciones_cnt/atenciones/$1';
$route['add_diagnostico/(:any)'] = 'siniestro_cnt/add_diagnostico/$1';
$route['add_diagnosticoSec/(:any)'] = 'siniestro_cnt/add_diagnosticoSec/$1';
$route['diagnostico_cie10'] = 'diagnosticoCie10_cnt/index';
$route['guardaTriaje'] = 'siniestro_cnt/guardaTriaje';
$route['guardaDiagP'] = 'siniestro_cnt/guardaDiagP';
$route['guardaDiagS'] = 'siniestro_cnt/guardaDiagS';
$route['add_tratamiento/(:any)'] = 'siniestro_cnt/add_tratamiento/$1';
$route['add_tratamientoSec/(:any)'] = 'siniestro_cnt/add_tratamientoSec/$1';
$route['guardaMediP'] = 'siniestro_cnt/guardaMediP';
$route['guardaMediS'] = 'siniestro_cnt/guardaMediS';
$route['edit_medi/(:num)/(:any)'] = 'siniestro_cnt/edit_medi/$1/$2';
$route['guardaEditMed'] = 'siniestro_cnt/guardaEditMed';
$route['delete_trata/(:num)/(:any)'] = 'siniestro_cnt/delete_trata/$1/$2';

$route['search'] = "siniestro_cnt/search";

$route['orden/(:any)/(:any)'] = 'atenciones_cnt/orden/$1/$2';

$route['plan_cobertura/(:any)/(:any)'] = 'plan_cnt/plan_cobertura/$1/$2';
$route['plan_editar/(:any)/(:any)'] = 'plan_cnt/plan_editar/$1/$2';
$route['plan_registrar'] = 'plan_cnt/plan_registrar';
$route['plan_guardar'] = 'plan_cnt/plan_guardar';
$route['guardar_cobertura'] = 'plan_cnt/guardar_cobertura';
$route['plan_anular/(:any)'] = 'plan_cnt/plan_anular/$1';
$route['plan_activar/(:any)'] = 'plan_cnt/plan_activar/$1';
$route['cobertura_anular/(:any)/(:any)/(:any)'] = 'plan_cnt/cobertura_anular/$1/$2/$3';
$route['cobertura_activar/(:any)/(:any)/(:any)'] = 'plan_cnt/cobertura_activar/$1/$2/$3';
$route['seleccionar_cobertura/(:any)/(:any)/(:any)'] = 'plan_cnt/seleccionar_cobertura/$1/$2/$3';

$route['consultar_cobros_buscar'] = 'Reportes_cnt/buscar';
$route['consultar_detalle_cobros/(:any)/(:any)/(:any)/(:any)'] = 'Reportes_cnt/detalle_cobros/$1/$2/$3/$4';
$route['exc_cobros/(:any)/(:any)/(:any)'] = 'Reportes_cnt/exc_cobros/$1/$2/$3';

// Rutas Ariel (Escribir debajo)

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['creaSiniestro'] = 'siniestro_cnt/creaSiniestro';
$route['guardaGasto'] = 'siniestro_cnt/guardaGasto';
$route['liquidacion'] = 'liquidacion_cnt/index';
$route['registraPago'] = 'liquidacion_cnt/registraPago';

$route['start_sesion'] = 'login_cnt/start_sesion';
$route['logout'] = 'login_cnt/logout';