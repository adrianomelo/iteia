<?php
include_once(dirname(__FILE__)."/../vo/ConfigVO.php");

class PlayerUtil {

    public function playerAudio($item) {
		//$link_audio = self::urlArquivo($item, 2);
		$link_audio = ConfigVO::getUrlAudio().$item;
    	$html_player = "<object type=\"application/x-shockwave-flash\" data=\"".ConfigVO::URL_SITE."FlowPlayerDark.swf\" width=\"320\" height=\"29\" id=\"FlowPlayer\"><param name=\"allowScriptAccess\" value=\"sameDomain\" /><param name=\"movie\" value=\"".ConfigVO::URL_SITE."FlowPlayerDark.swf\" /><param name=\"quality\" value=\"high\" /><param name=\"scale\" value=\"noScale\" /><param name=\"wmode\" value=\"transparent\" /><param name=\"flashvars\" value=\"config={videoFile: '".$link_audio."', autoBuffering: false, streamingServer: 'lighttpd', initialScale: 'orig', loop: false }\" /></object>";
        return $html_player;
    }

	public static function urlArquivo($arquivo, $tipo) {
		if ($tipo == 1) {
			//$AgetHeaders = @get_headers('http://www.achix.inf.br/penc/videos/'.$arquivo);
			//if (preg_match("|200|", $AgetHeaders[0]))
			//	return 'http://www.achix.inf.br/penc/videos/'.$arquivo;
			return ConfigVO::getUrlVideo().$arquivo;
		}
		elseif ($tipo == 2) {
			//$AgetHeaders = @get_headers('http://www.achix.inf.br/penc/audios/'.$arquivo);
			//if (preg_match("|200|", $AgetHeaders[0]))
			//	return 'http://www.achix.inf.br/penc/audios/'.$arquivo;
			return ConfigVO::getUrlAudio().$arquivo;
		}
	}

    public function playerVideo($item, $tipo, $width='100%', $height='440') {
    	global $video_url;
    	
		if ($tipo == 1) {
			//$link_video = self::urlArquivo($item, 1);
			$link_video = ConfigVO::getUrlVideo().$item;
			$html_player = '<a href="'.$link_video.'" style="display:block;width:620px;height:430px" id="player"></a>';
			$html_player .= '<script type="text/javascript">flowplayer("player", "'.ConfigVO::URL_SITE.'js/flowplayer-3.1.5/flowplayer-3.1.5.swf");</script>';

        } elseif ($tipo == 2) {
            $link = explode('=', $item);
            $url = "http://www.youtube.com/v/$link[1]";
            $html_player = "<object width=\"".$width."\" height=\"".$height."\"><param name=\"movie\" value=\"$url\"></param><param name=\"wmode\" value=\"transparent\"></param><embed src=\"".$url."\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" width=\"".$width."\" height=\"".$height."\"></embed></object>";
        }
        return $html_player;
    }
	
	public static function getImagemVideo($arquivo, $url, $imagem, $size = 72, $width = 90, $height = 90) {
		if ($arquivo) {
			if (file_exists(ConfigVO::getDirVideo().'convertidos/'.$arquivo.'.png'))
				return '<img src="/exibir_imagem.php?img='.$arquivo.'.png&amp;tipo=15&amp;s='.$size.'" alt="" width="'.$width.'" height="'.$height.'" />';
		}
		elseif ($url) {
			$link = explode('=', $url);
			return '<img src="http://i3.ytimg.com/vi/'.str_replace('&feature', '', $link[1]).'/default.jpg" width="'.$width.'" height="'.$height.'" alt="" />';
		}
		elseif ($imagem) {
			return '<img src="/exibir_imagem.php?img='.$imagem.'&amp;tipo=4&amp;s='.$size.'" alt="" width="'.$width.'" height="'.$height.'" />';
		}
		return '';
	}

}
