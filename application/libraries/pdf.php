<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    //require_once APPPATH."/fpdf/fpdf.php";
    require_once(dirname(__FILE__) . '/fpdf/fpdf.php');
 
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Pdf extends FPDF {
        public function __construct() {
            parent::__construct();
        }
        // El encabezado del PDF
        public function Header(){
            $this->Image(base_url().'public/assets/avatars/logo.jpg',10,8,80);
            $this->Ln('5');
            $this->SetFont('Arial','B',11);            
            $this->Cell(30);
            $this->Cell(160,10,'HEALTH CARE ADMINISTRATION RED SALUD S.A.C.',120,0,'R');
            $this->Ln('5');
            $this->SetFont('Arial','B',8);
            $this->Cell(30);
            $this->Cell(160,10,'Av. Jose Pardo Nro. 601 Int. 502 (Piso 5), Miraflores',12,0,'R');
            $this->Ln('5');
            $this->SetFont('Arial','B',8);
            $this->Cell(30);
            $this->Cell(160,10,utf8_decode('Lima, Perú'),12,0,'R');
            $this->Ln('5');
            $this->Line(10, 40 , 200, 40); 
       }

       // El pie del pdf
       public function Footer(){
            $this->SetY(-15);
            $this->SetFont('Arial','I',8);
            $this->Cell(0,10,utf8_decode('Página ').''.$this->PageNo().'/{nb}',0,0,'C');
      }
    }
?>