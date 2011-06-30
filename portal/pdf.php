<?php
require_once('classes/vo/ConfigPortalVO.php');
require_once(ConfigPortalVO::getDirClassesRaiz().'vo/ConfigVO.php');
require_once(ConfigPortalVO::getDirClassesRaiz().'dao/ConteudoExibicaoDAO.php');
require_once(ConfigPortalVO::getDirClassesRaiz().'dao/AgendaDAO.php');
require_once(ConfigPortalVO::getDirClassesRaiz().'dao/NoticiaDAO.php');

include('fpdf/pdf_class.php');
include('fpdf/funcoes.php');

class PDF2 extends PDF {
	function Header() {
		$this->Image(ConfigVO::DIR_SITE.'portal/img/molde/logo.gif', 10, 5, 30);
		$this->SetXY(10, 35);
	}
	function Footer() {
		$this->SetY(-15);
		$this->SetFont('Arial', '', 8);
		$this->SetTextColor(0, 0, 0);
		$this->Cell(0, 10, 'www.achanoticias.com.br                                                                 www.iteia.org.br', 0, 0, 'C');
	}
}
$pdf = new PDF2('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 11);

$baixar = $_GET['baixar'];

if ($baixar == 'noticia') {
	$codnoticia = (int)$_GET['cod'];

	$notdao = new NoticiaDAO;
    $noticia = $notdao->getNoticiaDadosPDF($codnoticia);

	$pdf->posicaox_padrao_orig = $pdf->lMargin;
	$pdf->largura_cell_orig = $pdf->w - $pdf->lMargin - $pdf->rMargin;

	$pdf->posicaox_padrao = $pdf->posicaox_padrao_orig;
	$pdf->largura_cell = $pdf->largura_cell_orig;

	$pdf->SetFontSize(10);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(0, 4, 'Pernambuco Nação Cultural', 0, 0, 'L');
	$pdf->SetY($pdf->GetY() + 4);
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(0, 4, date("d/m/Y - H:i", strtotime($noticia['datahora'])), 0, 0, 'L');

	$pdf->SetX(10);

	$pdf->SetXY(10, 48);
	$pdf->SetTextColor(50, 128, 167);
	$pdf->SetFont('Arial', 'B', 14);
	$pdf->MultiCell(0, 6.5, formata_texto($noticia['titulo']), 0, 'L');

	if ($noticia['subtitulo']) {
		$pdf->SetTextColor(102, 102, 102);
		$pdf->SetFont('Arial', '', 10);
		$pdf->MultiCell(0, 5.5, formata_texto($noticia['subtitulo']).chr(10).' ', 0, 'L');
	}

	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$pdf->MultiCell(0, 5, formata_texto($noticia['assinatura']).chr(10).' ', 0, 'R');

	$pdf->SetTextColor(0, 0, 0);
	$posicaoy = $pdf->GetY();
	$pdf->FonteTexto();
	$altura_linha = 6;

	$pdf->linha_atual = 0;
	$pdf->linhas_texto = array();
	$x_atual = $pdf->GetX();
	$x_atual_ok = false;

	if ($noticia['imagem']) {
		$pdf->InserirFoto(ConfigVO::getDirImgCache().'img_s8_5_'.$noticia['imagem'], formata_texto($noticia['foto_credito']), formata_texto($noticia['foto_legenda']), $x_atual, $pdf->GetY(), 'esq', $altura_linha, $pdf->linha_atual);
		$x_atual_ok = true;
	}

	$texto = $pdf->MultiCell(0, $altura_linha, formata_texto(strip_tags($noticia['descricao'])), 0, 'J', 0, 0, true);
	$pdf->Output('iteia_noticia_'.$codnoticia.'.pdf', 'D');
}

if ($baixar == 'texto') {
	$codconteudo = (int)$_GET['cod'];

    $contdao = new ConteudoExibicaoDAO;
    $conteudo = $contdao->getDadosConteudo($codconteudo);
    
	$pdf->posicaox_padrao_orig = $pdf->lMargin;
	$pdf->largura_cell_orig = $pdf->w - $pdf->lMargin - $pdf->rMargin;

	$pdf->posicaox_padrao = $pdf->posicaox_padrao_orig;
	$pdf->largura_cell = $pdf->largura_cell_orig;

	$pdf->SetXY(10, 48);
	$pdf->SetTextColor(50, 128, 167);
	$pdf->SetFont('Arial', 'B', 14);
	$pdf->MultiCell(0, 6.5, formata_texto($conteudo['titulo']), 0, 'L');

	$pdf->SetTextColor(0, 0, 0);
	$posicaoy = $pdf->GetY();
	$pdf->FonteTexto();
	$altura_linha = 6;

	$pdf->linha_atual = 0;
	$pdf->linhas_texto = array();
	$x_atual = $pdf->GetX();
	$x_atual_ok = false;

	if ($conteudo['imagem']) {
		$pdf->InserirFoto(ConfigVO::getDirImgCache().'img_s30_1_'.$conteudo['imagem'], formata_texto($conteudo['foto_credito']), formata_texto($conteudo['foto_legenda']), $x_atual, $pdf->GetY(), 'esq', $altura_linha, $pdf->linha_atual);
		$x_atual_ok = true;
	}

	$texto = $pdf->MultiCell(0, $altura_linha, formata_texto(strip_tags($conteudo['descricao'])), 0, 'J', 0, 0, true);
	$pdf->Output('iteia_texto_'.$codconteudo.'.pdf', 'D');
}

if ($baixar == 'evento') {
	$codconteudo = (int)$_GET['cod'];

    $agendao = new AgendaDAO;
    $conteudo = $agendao->getDadosAgenda($codconteudo);
    
	$pdf->posicaox_padrao_orig = $pdf->lMargin;
	$pdf->largura_cell_orig = $pdf->w - $pdf->lMargin - $pdf->rMargin;

	$pdf->posicaox_padrao = $pdf->posicaox_padrao_orig;
	$pdf->largura_cell = $pdf->largura_cell_orig;

	$pdf->SetXY(10, 48);
	$pdf->SetTextColor(50, 128, 167);
	$pdf->SetFont('Arial', 'B', 14);
	$pdf->MultiCell(0, 6.5, formata_texto($conteudo['titulo']), 0, 'L');

	$pdf->SetTextColor(0, 0, 0);
	$posicaoy = $pdf->GetY();
	$pdf->FonteTexto();
	$altura_linha = 6;
	
	$pdf->SetXY(10, 57);
	$pdf->MultiCell(0, $altura_linha, formata_texto(strip_tags($conteudo['descricao'])), 0, 'J', 0, 0, true);
	
	$pdf->SetXY(10, 65);
	$pdf->Cell(0, 10, formata_texto('Data:' . date('d/m/Y', strtotime($conteudo['data_inicial'])).' até ' . date('d/m/Y', strtotime($conteudo['data_final']))), 0, 1, 'L');
	
	//$pdf->SetXY(10, 75);
	$pdf->Cell(0, 10, formata_texto('Horário: ' . date('H:i', strtotime($conteudo['hora_inicial'])).' às ' . date('H:i', strtotime($conteudo['hora_final']))), 0, 1, 'L');
	
	$pdf->Cell(0, 10, formata_texto('Local: ' . $conteudo['local']), 0, 1, 'L');
	
	if ($conteudo['site'])
		$pdf->Cell(0, 10, formata_texto('Site: ' . $conteudo['site']), 0, 1, 'L');
	
	if ($conteudo['valor'])
		$pdf->Cell(0, 10, formata_texto('Valor: ' . $conteudo['valor']), 0, 1, 'L');
	
	if ($conteudo['telefone'])
		$pdf->Cell(0, 10, formata_texto('Informações: ' . $conteudo['telefone']), 0, 1, 'L');

	$pdf->linha_atual = 0;
	$pdf->linhas_texto = array();
	$x_atual = $pdf->GetX();
	$x_atual_ok = false;
	
	$pdf->Output('iteia_evento_'.$codconteudo.'.pdf', 'D');
}
