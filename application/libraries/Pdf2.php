<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    //require_once APPPATH."/fpdf/fpdf.php";
    require_once(dirname(__FILE__) . '/fpdf/fpdf.php');
 
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Pdf2 extends FPDF {
        public function __construct() {
            parent::__construct('L','mm','Legal');
        }
    }
?>