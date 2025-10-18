<?php
// Reusable PDF base with EMCALI header/footer
require_once __DIR__ . '/../vendor/fpdf186/fpdf.php';

class PDF_Emcali extends \FPDF {
    public $logoPath = '';
    public $title = '';
    public $showDateInFooter = true;
    public $showUserInFooter = false;
    public $userLabel = '';

    function Header(){
        // Logo
        if($this->logoPath && file_exists($this->logoPath)){
            $this->Image($this->logoPath, 10, 8, 28);
        }
        // Title (optional)
        if(!empty($this->title)){
            $this->SetFont('Arial','B',14);
            $this->Cell(0,10,utf8_decode($this->title),0,1,'C');
            $this->Ln(4);
            $this->SetFont('Arial','',11);
        }
    }

    function Footer(){
        $this->SetY(-18);
        $this->SetFont('Arial','I',8);
        $left = '';
        if($this->showDateInFooter){
            $left = 'Generado por JANUS - '.date('Y-m-d');
        }
        if($this->showUserInFooter && !empty($this->userLabel)){
            $left = trim($left . '  |  Usuario: ' . $this->userLabel);
        }
        if(!empty($left)){
            $this->Cell(0,5,utf8_decode($left),0,1,'L');
        }
        $this->Cell(0,5,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
    }
}
