<?php

class Util {

    public static function paginacao($pagina, $num, $num_total, $base_url, $indicador='', $ajax=0, $function='', $campo='') {
        global $delimitador;
		global $classepagatual;
		
        $pg = $pagina - 1;
        $inicio = $pg * $num;
        $fim = $inicio + $num;
        if ($fim >= $num_total) {
            $fim = $num_total;
        }
        $inicio = $inicio + 1;
        $num_totalx = $num_total - 1;
        $pages = @intval($num_totalx / $num) + 1;
        $total_pagina = $pages;
        $on_page = @floor($inicio / $num) + 1;
        $page_string = '';
        $endereco = "$base_url&amp;pagina=";
        if ($total_pagina > 10) {
    	$init_page_max = ($total_pagina > 3)?3:$total_pagina;
     	for($i = 1; $i < $init_page_max + 1; $i++) {
			if ($ajax) {
				$page_string .= ($i == $on_page)?'<strong'.$classepagatual.'>'.$i.'</strong>':"<a href=\"javascript:void(0);\" onclick=\"$function('$indicador','$campo','$i');\">$i</a>";
    		} else {
    			$page_string .= ($i == $on_page)?'<strong'.$classepagatual.'>'.$i.'</strong>':'<a href="'.stripslashes($endereco.$i).'">'.$i.'</a>';
    		}
    	    if ($i <  $init_page_max) {
    		  //$page_string .= ", ";
    		  if ($delimitador != '')
    		      $page_string .= $delimitador;
    		  else
                  $page_string .= " ";
            }
    	}
    	if ($total_pagina > 3) {
    	    if ($on_page > 1  && $on_page < $total_pagina) {
    		//$page_string .= ($on_page > 5)?' ... ':', ';
    		$page_string .= ($on_page > 5)?' ... ':' ';
    		$init_page_min = ($on_page > 4)?$on_page:5;
    		$init_page_max = ($on_page < $total_pagina - 4)?$on_page:$total_pagina - 4;
    		for($i = $init_page_min - 1; $i < $init_page_max + 2; $i++) {
				if ($ajax) {
					$page_string .= ($i == $on_page)?'<strong'.$classepagatual.'>'.$i.'</strong>':"<a href=\"javascript:void(0);\" onclick=\"$function('$indicador','$campo','$i');\">$i</a>";
	    		} else {
	    			$page_string .= ($i == $on_page)?'<strong'.$classepagatual.'>'.$i.'</strong>':'<a href="'.stripslashes($endereco.$i).'">'.$i.'</a>';
	    		}
    		    if ($i <  $init_page_max + 1) {
    			     //$page_string .= ', ';
    			     if ($delimitador != '')
            		      $page_string .= $delimitador;
            		  else
                          $page_string .= " ";
            		}
    		}
    		//$page_string .= ($on_page < $total_pagina - 4)?'<span> ... </span>':', ';
    		$page_string .= ($on_page < $total_pagina - 4)?'<span> ... </span>':' ';
    	    } else {
    		      $page_string .= ' ... ';
    	    }
    	    for($i = $total_pagina - 2; $i < $total_pagina + 1; $i++) {
				if ($ajax) {
					$page_string .= ($i == $on_page)?'<strong'.$classepagatual.'>'.$i.'</strong>':"<a href=\"javascript:void(0);\" onclick=\"$function('$indicador','$campo','$i');\">$i</a>";
    			} else {
    				$page_string .= ($i == $on_page)?'<strong'.$classepagatual.'>'.$i.'</strong>':'<a href="'.stripslashes($endereco.$i).'">'.$i.'</a>';
    			}
    			if ($i <  $total_pagina) {
    		    	//$page_string .= ", ";
    		    	if ($delimitador != '')
    		            $page_string .= $delimitador;
    		        else
                        $page_string .= " ";
    			     }
    	    }
    	}
        } else {
        	for($i = 1; $i < $total_pagina + 1; $i++) {
    			if ($ajax) {
    				$page_string .= ($i == $on_page)?'<strong'.$classepagatual.'>'.$i.'</strong>':"<a href=\"javascript:void(0);\" onclick=\"$function('$indicador','$campo','$i');\">$i</a>";
        		} else {
        			$page_string .= ($i == $on_page)?'<strong'.$classepagatual.'>'.$i.'</strong>':'<a href="'.stripslashes($endereco.$i).'">'.$i.'</a>';
        		}
        	    if ($i <  $total_pagina) {
        		    //$page_string .= ', ';
        		    if ($delimitador != '')
    		            $page_string .= $delimitador;
    		        else
                        $page_string .= " ";
        	       }
        	}
        }
        $paginacao = array(
	        'pagina'       => $pagina,
	        'page_string'  => $page_string,
	        'total_pagina' => $total_pagina,
	        'num_total'    => $num_total,
	        'inicio'       => $inicio,
	        'fim'          => $fim
        );
        if ($ajax) {
			if ($pagina > 1) {
	           $num = $pagina - 1;
	           $paginacao['anterior'] = "<a href=\"javascript:void(0);\" onclick=\"$function('$indicador','$campo','$num');\">&laquo; Anterior</a>";
	        }
    	    if ($pagina < $pages) {
    	        $num = $pagina + 1;
    	        $paginacao['proxima']  = "<a href=\"javascript:void(0);\" onclick=\"$function('$indicador','$campo','$num');\">Pr&oacute;xima &raquo;</a>";
    	    }
	   	} else {
			if ($pagina > 1) {
	           $num = $pagina - 1;
	           $paginacao['anterior'] = array('url' => stripslashes($endereco.$num),'num' => $num);
	        }
    	    if ($pagina < $pages) {
    	        $num = $pagina + 1;
    	        $paginacao['proxima']  = array('url' => stripslashes($endereco.$num),'num' => $num);
    	    }
    	    if ($pagina > 1) {
    	        $paginacao['primeira'] = array('url' => stripslashes($endereco."1"),'num' => 1);
    	    }
    	    if ($pagina < $pages) {
    	        $paginacao['ultima']   = array('url' => stripslashes($endereco.$pages),'num' => $pages);
    	    }
		}
        return $paginacao;
	}

	public static function cortaTexto($str, $n = 500, $end_char = '&#8230;') {
    	if (strlen($str) < $n) {
    		return $str;
    	}
    	$str = preg_replace("/\s+/", ' ', preg_replace("/(\r\n|\r|\n)/", " ", $str));
    	if (strlen($str) <= $n) {
    		return $str;
    	}
    	$out = "";
    	foreach (explode(' ', trim($str)) as $val) {
    		$out .= $val.' ';
    		if (strlen($out) >= $n) {
    			return trim($out).$end_char;
    		}
    	}
    }

    public static function gera_randomico($tipo = "str", $tamanho = 10) {
		$randomico = "";
		$cont = 1;
		while ($cont <= $tamanho) {
			if ($tipo == "str")	$x = mt_rand(48, 122);
			elseif ($tipo == "num") $x = mt_rand(48, 57);
			else return "";
			if (($tipo == "num") || ($x <= 57) || ($x >= 97)) {
				$randomico .= chr($x);
				$cont++;
			}
		}
		return $randomico;
	}

    public static function enviaemail($assunto, $de, $email, $conteudo, $emaildestino) {
		include_once(dirname(__FILE__).'/htmlMimeMail5/htmlMimeMail5.php');
		$mail = new htmlMimeMail5();

		$mail->setHtml($conteudo);
		//$mail->setReturnPath($email);
		$mail->setFrom("\"".$de."\" <".$email.">");
		$mail->setSubject($assunto);

		if (is_array($emaildestino)) {
			for ($i = 0; $i < sizeof($emaildestino); $i++) {
				$mail->send(array($emaildestino[$i]));
			}
		} else {
			$mail->send(array($emaildestino));
		}
	}

    public static function iif($condition, $true, $false='') {
        return ($condition ? $true : $false);
    }

    public static function checkEmail($email) {
		return (!preg_match('/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i', $email)) ? false : true;
    }

    public static function force_download($data, $name, $mimetype='', $filesize=false) {
    	if ($filesize == false OR !is_numeric($filesize)) {
    		$filesize = strlen($data);
    	}
    	if (empty($mimetype)) {
    		$mimetype = 'application/octet-stream';
    	}
    	self::ob_clean_all();
    	header("Pragma: public");
    	header("Expires: 0");
    	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    	header("Cache-Control: private",false);
    	header("Content-Transfer-Encoding: binary");
    	header("Content-Type: " . $mimetype);
    	header("Content-Length: " . $filesize);
    	header("Content-Disposition: attachment; filename=\"" . $name . "\";" );
    	echo $data;
    	//die();
    }

    private function ob_clean_all () {
    	$ob_active = ob_get_length () !== false;
    	while($ob_active) {
    		ob_end_clean();
    		$ob_active = ob_get_length () !== false;
    	}
    	return true;
    }

    public static function getIconeConteudo($cod_formato) {
        switch ($cod_formato) {
            case 1: $icon = 'texto'; break;
            case 2: $icon = 'imagem'; break;
            case 3: $icon = 'audio'; break;
            case 4: $icon = 'video'; break;
            case 5: $icon = 'noticia'; break;
			case 6: $icon = 'evento'; break;
        }
        return $icon;
    }

    public static function getFormatoConteudo($cod_formato) {
        switch ($cod_formato) {
            case 1: $formato = 'Texto'; break;
            case 2: $formato = 'Imagem'; break;
            case 3: $formato = '&Aacute;udio'; break;
            case 4: $formato = 'V&iacute;deo'; break;
            case 5: $formato = 'Jornal'; break;
			case 6: $formato = 'Eventos'; break;
        }
        return $formato;
    }

    public static function getFormatoConteudoMenor($cod_formato) {
        switch ($cod_formato) {
            case 1: $formato = 'texto'; break;
            case 2: $formato = 'imagem'; break;
            case 3: $formato = '&aacute;udio'; break;
            case 4: $formato = 'v&iacute;deo'; break;
            case 5: $formato = 'not&iacute;cia'; break;
			case 6: $formato = 'evento'; break;
        }
        return $formato;
    }

	public static function getFormatoConteudoBusca($cod_formato) {
        switch ($cod_formato) {
            case 1: $formato = 'textos'; break;
            case 2: $formato = 'imagens'; break;
            case 3: $formato = 'audios'; break;
            case 4: $formato = 'videos'; break;
            case 5: $formato = 'noticias'; break;
			case 6: $formato = 'eventos'; break;
        }
        return $formato;
    }

    public static function getImagemPadraoConteudo($cod_formato) {
        switch ($cod_formato) {
            case 1:
                $icon = 'imagem_texto_124x124.jpg';
                break;
            case 2:
                $icon = 'imagem_foto_124x124.jpg';
                break;
            case 3:
                $icon = 'imagem_audio_124x124.jpg';
                break;
            case 4:
                $icon = 'imagem_video_124x124.jpg';
                break;
            case 5:
                $icon = 'imagem_texto_124x124.jpg';
                break;
            case 6:
                $icon = 'imagem_texto_124x124.jpg';
                break;
        }
        return $icon;
    }

    public static function getImagemPadraoUsuario($tipo) {
        switch ($tipo) {
            case 1: // colaborador
                $icon = 'imagem_colaborador_124x124.jpg';
                break;
            case 2: // autor
                $icon = 'imagem_autor_124x124.jpg';
                break;
        }
        return $icon;
    }

    public static function getTipoContato($cod) {
		switch ($cod) {
			case 1: return "Gtalk"; break;
			case 2: return "MSN"; break;
			case 3: return "Skype"; break;
			case 4: return "Jabber"; break;
			case 5: return "ICQ"; break;
			case 6: return "Yahoo Messenger"; break;
			case 7: return "AIM"; break;
			case 8: return "Outro"; break;
		}
	}

	public static function getExtensaoArquivo($nomearq) {
		$nomearq_partes = explode(".", $nomearq);
		if (count($nomearq_partes) < 2)
			return "";
		return $nomearq_partes[count($nomearq_partes) - 1];
	}

	public static function geraRandomico($tipo = "str", $tamanho = 10) {
		$randomico = "";
		$cont = 1;
		while ($cont <= $tamanho) {
			if ($tipo == "str")	$x = mt_rand(48, 122);
			elseif ($tipo == "num") $x = mt_rand(48, 57);
			else return "";

			if (($tipo == "num") || ($x <= 57) || ($x >= 97)) {
				$randomico .= chr($x);
				$cont++;
			}
		}
		return $randomico;
	}

    public static function file_size($file_size = 0) {
        $file_size = number_format($file_size / 1048576,1);
    	return $file_size;
	}

	public static function removeAcentos($texto) {
       return strtr($texto, "·‡‚„‰¡¿¬√ƒÈËÍÎ…» ÀÌÏÓÔÕÃŒœÛÚÙıˆ”“‘’÷˙˘˚¸⁄Ÿ€‹Á«Ò—", "aaaaaAAAAAeeeeEEEEiiiiIIIIoooooOOOOOuuuuUUUUcCnN");
   	}

   	public static function geraTags($str) {
   	    $str = strip_tags(strtolower($str));
        return trim(str_replace(array(',', '!', '@', '#', '$', '%', '®', '&', '*', '(', ')', '-', '=', '+', '¥', '`', '[', ']', '{', '}', '~', '^', ',', '<', '.', '>', ':', '/', '?', '\\', '|', 'π', '≤', '≥', '£', '¢', '¨', 'ß', '™', '∫', '∞'), array(''), $str));
    }


	public static function geraUrlTitulo($str) {
		$search	 = '_';
		$replace = '-';
		$trans = array(
					$search					=> $replace,
					"\s+"					=> $replace,
					"[^a-z0-9".$replace."]"	=> '',
					$replace."+"			=> $replace,
					$replace."$"			=> '',
					"^".$replace			=> ''
				);
		$str = strip_tags(strtolower(self::removeAcentos($str)));
		foreach ($trans as $key => $val) {
			$str = preg_replace("#".$key."#", $val, $str);
		}
		return trim(stripslashes($str));
	}

	public static function removeTags($texto) {
		return strip_tags($texto, "<b><i><ul><li><em><strong><p><br><ol><img><a>");
	}

	public static function getMensagemNotificacao($codtipo, $nome, $formato) {
		switch ($codtipo) {
			// autor mandou conteudo pra aprovaÁ„o do colaborador
			case 2: $msg = "O autor ".$nome." enviou um novo(a) ".$formato." para aprovaÁ„o."; break;
			// colaborador aprovou conteudo
			case 3: $msg = "O colaborador ".$nome." aprovou o seu conte˙do."; break;
			// colaborador reprovou conteduo
			case 4: $msg = "O colaborador ".$nome." rejeitou o seu conte˙do."; break;
			// autor re-enviou conteudo para aprovaÁ„o do colaborador
			case 5: $msg = "O autor ".$nome." fez uma alteraÁ„o em um(a) ".$formato." e pede nova aprovaÁ„o."; break;
		}
		return $msg;
	}

	public static function getTipoLicenca($array) {
		$nomelicenca = '';
		foreach ($array as $key => $licenca) {
            $htmlicenca .= "<img src=\"img/cc/".$licenca['imagem']."\" alt=\"".$licenca['titulo']."\" border=\"0\" />&nbsp;";
            if ($licenca['descricao'])
            	$comp = "<a href=\"".$licenca['descricao']."\" target=\"_blank\" class=\"ext\">".$licenca['titulo']."</a>\n";
			else
				$comp = $licenca['titulo'];
		}
		return $htmlicenca.'<br />'.$comp;
	}

	public static function converteSegundos($total_segundos, $inicio = 'Y') {
		$dias_por_mes = ((((365*3)+366)/4)/12);
		$comecou = false;
		if ($inicio == 'Y') {
			//$tempo = floor($total_segundos / (60*60*24* $dias_por_mes *12)).'a ';
			$total_segundos = ($total_segundos % (60*60*24* $dias_por_mes *12));
			$comecou = true;
		}
		if (($inicio == 'm') || ($comecou == true)) {
			//$tempo .= floor($total_segundos / (60*60*24* $dias_por_mes )).'m ';
			$total_segundos = ($total_segundos % (60*60*24* $dias_por_mes ));
			$comecou = true;
		}
		if (($inicio == 'd') || ($comecou == true)) {
			//$tempo .= floor($total_segundos / (60*60*24)).'d ';
			$total_segundos = ($total_segundos % (60*60*24));
			$comecou = true;
		}
		if (($inicio == 'H') || ($comecou == true)) {
			$tempo .= floor($total_segundos / (60*60)).'h ';
			$total_segundos = ($total_segundos % (60*60));
			$comecou = true;
		}
		if (($inicio == 'i') || ($comecou == true)) {
			//$tempo .= floor($total_segundos / 60).'m ';
			$total_segundos = ($total_segundos % 60);
			$comecou = true;
		}
		//$tempo .= $total_segundos.'s';
		return $tempo;
	}

	public static function removerImagensCache($nomearq) {
		foreach (glob(ConfigVO::getDirImgCache()."*".$nomearq."*") as $arquivo)
			unlink($arquivo);
	}

	public static function marcarPalavra($texto, $palavra) {
		$tag1 = '<strong class="palavra">';
		$tag2 = '</strong>';
		return mb_eregi_replace("(".$palavra.")", $tag1.'\1'.$tag2, $texto);
	}

	public static function unhtmlentities($string) {
		$trans_tbl = get_html_translation_table (HTML_ENTITIES);
		$trans_tbl = array_flip ($trans_tbl);
		return strtr ($string, $trans_tbl);
	}

	public static function clearText($texto) {
		$texto = str_replace("<br>", "\n", $texto);
		$texto = str_replace("<br />", "\n", $texto);
		$texto = str_replace("</p>", "\n\n", $texto);
		$texto = strip_tags($texto);
		return $texto;
	}

	public static function autoLink($str, $type = 'both', $popup = true) {
			if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches))
			{
				//$pop = ($popup == TRUE) ? " target=\"_blank\" " : "";
				$pop = " target=\"_blank\" ";

				for ($i = 0; $i < sizeof($matches['0']); $i++)
				{
					$period = '';
					if (preg_match("|\.$|", $matches['6'][$i]))
					{
						$period = '.';
						$matches['6'][$i] = substr($matches['6'][$i], 0, -1);
					}

					$str = str_replace($matches['0'][$i],
										$matches['1'][$i].'<a href="http'.
										$matches['4'][$i].'://'.
										$matches['5'][$i].
										$matches['6'][$i].'"'.$pop.'>'.
										$matches['4'][$i].
										//$matches['6'][$i].'"'.$pop.'>http'.
										//$matches['4'][$i].'://'.
										$matches['5'][$i].
										$matches['6'][$i].'</a>'.
										$period, $str);
				}
			}
		return $str;
	}

	public static function getTrecho($text, $words, $length = 400) {
		// first replace all whitespaces with single spaces
		$text = preg_replace('/ +/', ' ', strtr($text, "\t\n\r\x0C ", '     '), $text);

		$word_indizes = array();
		if (sizeof($words))
		{
			$match = '';
			// find the starting indizes of all words
			foreach ($words as $word)
			{
				if ($word)
				{
					if (preg_match('#(?:[^\w]|^)(' . $word . ')(?:[^\w]|$)#i', $text, $match))
					{
						$pos = self::utf8_strpos($text, $match[1]);
						if ($pos !== false)
						{
							$word_indizes[] = $pos;
						}
					}
				}
			}
			unset($match);

			if (sizeof($word_indizes))
			{
				$word_indizes = array_unique($word_indizes);
				sort($word_indizes);

				$wordnum = sizeof($word_indizes);
				// number of characters on the right and left side of each word
				$sequence_length = (int) ($length / (2 * $wordnum)) - 2;
				$final_text = '';
				$word = $j = 0;
				$final_text_index = -1;

				// cycle through every character in the original text
				for ($i = $word_indizes[$word], $n = self::utf8_strlen($text); $i < $n; $i++)
				{
					// if the current position is the start of one of the words then append $sequence_length characters to the final text
					if (isset($word_indizes[$word]) && ($i == $word_indizes[$word]))
					{
						if ($final_text_index < $i - $sequence_length - 1)
						{
							$final_text .= '... ' . preg_replace('#^([^ ]*)#', '', self::utf8_substr($text, $i - $sequence_length, $sequence_length));
						}
						else
						{
							// if the final text is already nearer to the current word than $sequence_length we only append the text
							// from its current index on and distribute the unused length to all other sequenes
							$sequence_length += (int) (($final_text_index - $i + $sequence_length + 1) / (2 * $wordnum));
							$final_text .= self::utf8_substr($text, $final_text_index + 1, $i - $final_text_index - 1);
						}
						$final_text_index = $i - 1;

						// add the following characters to the final text (see below)
						$word++;
						$j = 1;
					}

					if ($j > 0)
					{
						// add the character to the final text and increment the sequence counter
						$final_text .= self::utf8_substr($text, $i, 1);
						$final_text_index++;
						$j++;

						// if this is a whitespace then check whether we are done with this sequence
						if (self::utf8_substr($text, $i, 1) == ' ')
						{
							// only check whether we have to exit the context generation completely if we haven't already reached the end anyway
							if ($i + 4 < $n)
							{
								if (($j > $sequence_length && $word >= $wordnum) || self::utf8_strlen($final_text) > $length)
								{
									$final_text .= ' ...';
									break;
								}
							}
							else
							{
								// make sure the text really reaches the end
								$j -= 4;
							}

							// stop context generation and wait for the next word
							if ($j > $sequence_length)
							{
								$j = 0;
							}
						}
					}
				}
				return $final_text;
			}
		}

		if (!sizeof($words) || !sizeof($word_indizes))
		{
			return (self::utf8_strlen($text) >= $length + 3) ? self::utf8_substr($text, 0, $length) . '...' : $text;
		}
	}

	private function utf8_strpos($str, $needle, $offset = null)
	{
		if (is_null($offset))
		{
			return mb_strpos($str, $needle);
		}
		else
		{
			return mb_strpos($str, $needle, $offset);
		}
	}

	private function utf8_strlen($text)
	{
		return mb_strlen($text, 'utf-8');
	}

	private function utf8_substr($str, $offset, $length = null)
	{
		if (is_null($length))
		{
			return mb_substr($str, $offset);
		}
		else
		{
			return mb_substr($str, $offset, $length);
		}
	}

	public static function getDescricaoLicenca($codlicenca, $ordem) {
		switch ($codlicenca) {
			case 1:
				switch ($ordem) {
					case 1: $descricao = 'copiar, distribuir, exibir e executar a obra'; break;
					case 2: $descricao = 'criar obras derivadas'; break;
					case 3: $descricao = 'VocÍ deve dar crÈdito ao autor original, da forma especificada pelo autor ou licenciante.'; break;
				}
			break;
			case 2:
				switch ($ordem) {
					case 1: $descricao = 'copiar, distribuir, exibir e executar a obra'; break;
					case 2: $descricao = 'criar obras derivadas'; break;
					case 3: $descricao = 'VocÍ deve dar crÈdito ao autor original, da forma especificada pelo autor ou licenciante.'; break;
					case 4: $descricao = 'Se vocÍ alterar, transformar, ou criar outra obra com base nesta, vocÍ somente poder· distribuir a obra resultante sob uma licenÁa idÍntica a esta.'; break;
				}
			break;
			case 3:
				switch ($ordem) {
					case 1: $descricao = 'copiar, distribuir, exibir e executar a obra'; break;
					case 2: $descricao = 'VocÍ deve dar crÈdito ao autor original, da forma especificada pelo autor ou licenciante.'; break;
					case 3: $descricao = 'VocÍ n„o pode alterar, transformar ou criar outra obra com base nesta.'; break;
				}
			break;
			case 4:
				switch ($ordem) {
					case 1: $descricao = 'copiar, distribuir, exibir e executar a obra'; break;
					case 2: $descricao = 'criar obras derivadas'; break;
					case 3: $descricao = 'VocÍ deve dar crÈdito ao autor original, da forma especificada pelo autor ou licenciante.'; break;
					case 4: $descricao = 'VocÍ n„o pode utilizar esta obra com finalidades comerciais.'; break;
				}
			break;
			case 5:
				switch ($ordem) {
					case 1: $descricao = 'copiar, distribuir, exibir e executar a obra'; break;
					case 2: $descricao = 'criar obras derivadas'; break;
					case 3: $descricao = 'VocÍ deve dar crÈdito ao autor original, da forma especificada pelo autor ou licenciante.'; break;
					case 4: $descricao = 'VocÍ n„o pode utilizar esta obra com finalidades comerciais.'; break;
					case 5: $descricao = 'Se vocÍ alterar, transformar, ou criar outra obra com base nesta, vocÍ somente poder· distribuir a obra resultante sob uma licenÁa idÍntica a esta.'; break;
				}
			break;
			case 6:
				switch ($ordem) {
					case 1: $descricao = 'copiar, distribuir, exibir e executar a obra'; break;
					case 2: $descricao = 'VocÍ deve dar crÈdito ao autor original, da forma especificada pelo autor ou licenciante.'; break;
					case 3: $descricao = 'VocÍ n„o pode utilizar esta obra com finalidades comerciais.'; break;
					case 4: $descricao = 'VocÍ n„o pode alterar, transformar ou criar outra obra com base nesta.'; break;
				}
			break;
			case 7:
				$descricao = 'DomÌnio P˙blico';
			break;
			case 8:
				$descricao = 'Direito Autoral';
			break;
		}
		return $descricao;
	}
	
	public static function getTituloLicenca($codlicenca) {
		switch ($codlicenca) {
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6: $nomelicenca = 'Alguns direitos reservados'; break;
			case 7: $nomelicenca = 'Dom&iacute;nio p&uacute;blico'; break;
			case 8: $nomelicenca = 'Todos direitos reservados'; break;
		}
		return $nomelicenca;
	}
	
	public static function getHtmlCanal($codsegmento, $html='<br />') {
		require_once(ConfigPortalVO::getDirClassesRaiz()."dao/SegmentoDAO.php");
		$segdao = new SegmentoDAO;
		$dados = $segdao->getSegmentoDados($codsegmento);
		if ($dados['nome'])
			return '<a href="/canal.php?cod='.$codsegmento.'" title="Ir para p·gina deste canal">'.htmlentities($dados['nome']).'</a>'.$html;
	}
	
	public static function getHtmlListaAutores($codconteudo, $texto='Por ') {
		require_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
		$contdao = new ConteudoDAO;
		
		$lista_autores = $contdao->getAutoresConteudo($codconteudo);
		$links_autores = array();
		foreach ($lista_autores as $autor)
			$links_autores[] = '<a href="'.$autor['titulo'].'" title="Ir para p·gina deste autor" class="info">'.$autor['nome'].'</a>';
		$autores = $contdao->getAutoresConteudo($codconteudo);
		$lista_autores_ficha = $contdao->getAutoresFichaTecnicaConteudo($codconteudo);

		$html  = '';
		$html .= $texto;

		if (count($lista_autores_ficha)) {
			if ($lista_autores_ficha[0]['nome'])
				$lista = Util::iif($lista != '', ', ')."<a href=\"/".$lista_autores_ficha[0]['url']."\" title=\"Ir para p·gina deste autor\" class=\"info\">".Util::cortaTexto($lista_autores_ficha[0]['nome'], 35)."</a>";
			if ($lista_autores_ficha[1]['nome'])
				$lista .= ' e outros';
		}

		if ($lista == '') {
			if (count($autores)) {
				if ($autores[0]['nome'])
					$lista .= Util::iif($lista != '', ', ')."<a href=\"/".$autores[0]['url']."\" title=\"Ir para p·gina deste autor\" class=\"info\">".Util::cortaTexto($autores[0]['nome'], 35)."</a>";
				if ($autores[1]['nome'])
					$lista .= ' e outros';
			}
		}

		return $html.$lista;
	}
	
	public static function bitly($url) {
		$login = 'iteia';    //your bit.ly login
		$apikey = 'R_3137e6519b8d3bf0e9a1be44a21ae655'; //bit.ly apikey
		$format = 'json';         //choose between json or xml
		$version = '2.0.1';
	
		//create the URL
		$bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$apikey.'&format='.$format;
	
		//get the url
		//could also use cURL here
		$response = file_get_contents($bitly);
	
		//parse depending on desired format
		if (strtolower($format) == 'json') {
			$json = @json_decode($response,true);
			echo $json['results'][$url]['shortUrl'];
		}
		else //xml
			{
			$xml = simplexml_load_string($response);
			echo 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
		}
	}

     public static function  removeAcento($frase) {
	 
	 $frase=ereg_replace("[^a-zA-Z0-9_.]", "", 
  strtr($frase, "·‡„‚ÈÍÌÛÙı˙¸Á¡¿√¬… Õ”‘’⁄‹« ", 
  "aaaaeeiooouucAAAAEEIOOOUUC_"));
  
   return $frase;

	 
	 }


}
