// JavaScript Document

function init() {
	createExternalLinks();
}
// FORÇA LINKS A ABRIREM EM UMA NOVA JANELA

function createExternalLinks() {
    if(document.getElementsByTagName) {
        var anchors = document.getElementsByTagName('a');
        for(var i=0; i<anchors.length; i++) {
            var anchor = anchors[i];
            if(anchor.getAttribute("href") && anchor.getAttribute('rel')=='co-worker') { // <-- É necessário inserir rel="co-worker neighbor" no link
                anchor.target = '_blank';
                var title = anchor.title + ' (Este link abre uma nova janela)'; // <-- Insere este texto no final do Title do link
                anchor.title = title;
            }
        }
    }
}

// Hover na <tr>
$(document).ready(function() {
  $('table tbody tr').hover(
	function(){ $(this).addClass("over");},
	function(){ $(this).removeClass("over"); }
	);

});

// marcar todos checkbox
/*
$(document).ready(function() {
	$("table").find("input").attr("checked",""); // limpa os checkboxes
	var ifChecked = "0";
	$("input#check-all").click(function() {
	if(ifChecked == "0") {
	$("td").find("input").attr("checked","checked");
	ifChecked = "1";
	$('table tbody .check').blur().parent().parent().addClass("oven");
	}
	else {
	$("td").find("input").attr("checked","");
	ifChecked = "0";
	$('table tbody .check').blur().parent().parent().removeClass("oven");
	}
	});
});
*/

$(document).ready(function() {
	$("table").find("input[@type=checkbox]").attr("checked",""); // limpa os checkboxes
	var ifChecked = "0";
	$("input#check-all").click(function() {
	if(ifChecked == "0") {
	$("td").find("input[@type=checkbox]").attr("checked","checked");
	ifChecked = "1";
	$('table tbody .check').blur().parent().parent().addClass("oven");
	}
	else {
	$("td").find("input[@type=checkbox]").attr("checked","");
	ifChecked = "0";
	$('table tbody .check').blur().parent().parent().removeClass("oven");
	}
	});
});

// cores da tr impar
$(document).ready(function(){
    $("table tbody tr:nth-child(odd)").addClass("odd");
});

// marcar selecionado
$(document).ready(function(){
$('table tbody .check').click(function(){
       if ($(this).is(':checked')) $
(this).blur().parent().parent().addClass("oven");
       else $
(this).blur().parent().parent().removeClass("oven");
});
});
// ocultar
$(document).ready(function(){

	$(".seta").eq(7).addClass("active");
	$("div.fechada").eq(7).show();
	$(".seta").click(function(){
		$(this).next("div.fechada").slideToggle("slow")
		.siblings("div.fechada:visible").slideUp("slow");
		$(this).toggleClass("active");
		$(this).siblings("legend.seta").removeClass("active");
	});


// falecido
	$("#falecido").attr("checked",""); // limpa os checkboxes
	var ifChecked = "0";
	$("#falecido").click(function() {
		if(ifChecked == "0") {
		$(".display-none").slideToggle("slow");
		} else {

		}
	});
// falecido
/*
	$("#youtube").attr("checked",""); // limpa os checkboxes

	var ifChecked = "0";
	$("#youtube").click(function() {
		if(ifChecked == "0") {
		$(".display-none").slideToggle("slow");
		$("#anexo").slideToggle("slow");

		} else {

		}
	});
*/

// licenças
$("#licencas img").hide();
$('#direitos_0').is(':checked');
$('#direitos input:radio').click(function() {

	if ( $('#direitos_0, #direitos_1').is(':checked') ) {

		$("#lbl-alguns-direitos").html( "Alguns direitos reservados (Copyleft) " );
		$("#alguns-direitos").slideUp("slow");
		$("#alguns-direitos input:radio").attr("checked","");
		$("#licencas img").hide();
		$("#link").html(" ");
		}

	if ( $('#direitos_0').is(':checked') ) {
		$("#dp").show();
		}
	if ( $('#direitos_1').is(':checked') ) {
		$("#copyright").show();
		}
	if ( $('#direitos_0, #direitos_2').is(':checked') ) {
		$("#copyright").hide();
		}



	if ( $('#direitos_2').is(':checked') ) {

		$("#lbl-alguns-direitos").html( "Alguns direitos reservados (Copyleft) " + " (<strong class='green'>Responda as perguntas abaixo</strong>) " );
		$("#alguns-direitos").slideDown("slow");
		$("#licencas img").hide();
	}

	if ( $('#radio2').is(':checked') && $('#radio3').is(':checked') ) {

		$("#share").show();
		$("#remix").show();
		$("#by").show();
		$("#sa").hide();
		$("#nc").show();
		$("#nomod").hide();
		$("#link").html("<a href='http://creativecommons.org/licenses/by-nc/2.5/br/' target='_blank'>Atribuição-Uso Não-Comercial 2.5 Brasil</a>");
		// Atribuição-Uso Não-Comercial 2.5 Brasil
		// http://creativecommons.org/licenses/by-nc/2.5/br/
		}
	if ( $('#radio2').is(':checked') && $('#radio4').is(':checked') ) {

		$("#share").show();
		$("#remix").show();
		$("#by").show();
		$("#nc").show();
		$("#sa").show();
		$("#nomod").hide();
		$("#link").html("<a href='http://creativecommons.org/licenses/by-nc-sa/2.5/br/' target='_blank'>Atribuição-Uso Não-Comercial-Compartilhamento pela mesma Licença 2.5 Brasil</a>");
		// Atribuição-Uso Não-Comercial-Compartilhamento pela mesma Licença 2.5 Brasil
		// http://creativecommons.org/licenses/by-nc-sa/2.5/br/
		}
	if ( $('#radio2').is(':checked') && $('#radio5').is(':checked') ) { // obra derivada Nao

		$("#share").show();
		$("#remix").hide();
		$("#by").show();
		$("#nc").show();
		$("#sa").hide();
		$("#nomod").show();
		$("#link").html("<a href='http://creativecommons.org/licenses/by-nc-nd/2.5/br/' target='_blank'>Atribuição-Uso Não-Comercial-Vedada a Criação de Obras Derivadas 2.5 Brasil</a>");
		// Atribuição-Uso Não-Comercial-Vedada a Criação de Obras Derivadas 2.5 Brasil
		// http://creativecommons.org/licenses/by-nc-nd/2.5/br/
		}

	if ( $('#radio').is(':checked') && $('#radio3').is(':checked') ) { // obra derivada Nao

		$("#share").show();
		$("#remix").show();
		$("#by").show();
		$("#nc").hide();
		$("#sa").hide();
		$("#nomod").hide();
		$("#link").html("<a href='http://creativecommons.org/licenses/by/2.5/br/' target='_blank'>Atribuição 2.5 Brasil</a>");
		// Atribuição 2.5 Brasil
		// http://creativecommons.org/licenses/by/2.5/br/
		}
	if ( $('#radio').is(':checked') && $('#radio4').is(':checked') ) { // obra derivada Nao

		$("#share").show();
		$("#remix").show();
		$("#by").show();
		$("#nc").hide();
		$("#sa").show();
		$("#nomod").hide();
		$("#link").html("<a href='http://creativecommons.org/licenses/by-sa/2.5/br/' target='_blank'>Atribuição-Compartilhamento pela mesma Licença 2.5 Brasil</a>");
		// Atribuição-Compartilhamento pela mesma Licença 2.5 Brasil
		// http://creativecommons.org/licenses/by-sa/2.5/br/
		}
if ( $('#radio').is(':checked') && $('#radio5').is(':checked') ) { // obra derivada Nao

		$("#share").show();
		$("#remix").hide();
		$("#by").show();
		$("#nc").hide();
		$("#sa").hide();
		$("#nomod").show();
		$("#link").html("<a href='http://creativecommons.org/licenses/by-nd/2.5/br/' target='_blank'>Atribuição-Vedada a Criação de Obras Derivadas 2.5 Brasil</a>");
		// Atribuição-Vedada a Criação de Obras Derivadas 2.5 Brasil
		// http://creativecommons.org/licenses/by-nd/2.5/br/
		}
});

});

// corner

(function($) {

$.fn.corner = function(o) {
    var ie6 = $.browser.msie && /MSIE 6.0/.test(navigator.userAgent);
    function sz(el, p) { return parseInt($.css(el,p))||0; };
    function hex2(s) {
        var s = parseInt(s).toString(16);
        return ( s.length < 2 ) ? '0'+s : s;
    };
    function gpc(node) {
        for ( ; node && node.nodeName.toLowerCase() != 'html'; node = node.parentNode ) {
            var v = $.css(node,'backgroundColor');
            if ( v.indexOf('rgb') >= 0 ) {
                if ($.browser.safari && v == 'rgba(0, 0, 0, 0)')
                    continue;
                var rgb = v.match(/\d+/g);
                return '#'+ hex2(rgb[0]) + hex2(rgb[1]) + hex2(rgb[2]);
            }
            if ( v && v != 'transparent' )
                return v;
        }
        return '#ffffff';
    };
    function getW(i) {
        switch(fx) {
        case 'round':  return Math.round(width*(1-Math.cos(Math.asin(i/width))));
        case 'cool':   return Math.round(width*(1+Math.cos(Math.asin(i/width))));
        case 'sharp':  return Math.round(width*(1-Math.cos(Math.acos(i/width))));
        case 'bite':   return Math.round(width*(Math.cos(Math.asin((width-i-1)/width))));
        case 'slide':  return Math.round(width*(Math.atan2(i,width/i)));
        case 'jut':    return Math.round(width*(Math.atan2(width,(width-i-1))));
        case 'curl':   return Math.round(width*(Math.atan(i)));
        case 'tear':   return Math.round(width*(Math.cos(i)));
        case 'wicked': return Math.round(width*(Math.tan(i)));
        case 'long':   return Math.round(width*(Math.sqrt(i)));
        case 'sculpt': return Math.round(width*(Math.log((width-i-1),width)));
        case 'dog':    return (i&1) ? (i+1) : width;
        case 'dog2':   return (i&2) ? (i+1) : width;
        case 'dog3':   return (i&3) ? (i+1) : width;
        case 'fray':   return (i%2)*width;
        case 'notch':  return width;
        case 'bevel':  return i+1;
        }
    };
    o = (o||"").toLowerCase();
    var keep = /keep/.test(o);                       // keep borders?
    var cc = ((o.match(/cc:(#[0-9a-f]+)/)||[])[1]);  // corner color
    var sc = ((o.match(/sc:(#[0-9a-f]+)/)||[])[1]);  // strip color
    var width = parseInt((o.match(/(\d+)px/)||[])[1]) || 10; // corner width
    var re = /round|bevel|notch|bite|cool|sharp|slide|jut|curl|tear|fray|wicked|sculpt|long|dog3|dog2|dog/;
    var fx = ((o.match(re)||['round'])[0]);
    var edges = { T:0, B:1 };
    var opts = {
        TL:  /top|tl/.test(o),       TR:  /top|tr/.test(o),
        BL:  /bottom|bl/.test(o),    BR:  /bottom|br/.test(o)
    };
    if ( !opts.TL && !opts.TR && !opts.BL && !opts.BR )
        opts = { TL:1, TR:1, BL:1, BR:1 };
    var strip = document.createElement('div');
    strip.style.overflow = 'hidden';
    strip.style.height = '1px';
    strip.style.backgroundColor = sc || 'transparent';
    strip.style.borderStyle = 'solid';
    return this.each(function(index){
        var pad = {
            T: parseInt($.css(this,'paddingTop'))||0,     R: parseInt($.css(this,'paddingRight'))||0,
            B: parseInt($.css(this,'paddingBottom'))||0,  L: parseInt($.css(this,'paddingLeft'))||0
        };

        if ($.browser.msie) this.style.zoom = 1; // force 'hasLayout' in IE
        if (!keep) this.style.border = 'none';
        strip.style.borderColor = cc || gpc(this.parentNode);
        var cssHeight = $.curCSS(this, 'height');

        for (var j in edges) {
            var bot = edges[j];
            // only add stips if needed
            if ((bot && (opts.BL || opts.BR)) || (!bot && (opts.TL || opts.TR))) {
                strip.style.borderStyle = 'none '+(opts[j+'R']?'solid':'none')+' none '+(opts[j+'L']?'solid':'none');
                var d = document.createElement('div');
                $(d).addClass('jquery-corner');
                var ds = d.style;

                bot ? this.appendChild(d) : this.insertBefore(d, this.firstChild);

                if (bot && cssHeight != 'auto') {
                    if ($.css(this,'position') == 'static')
                        this.style.position = 'relative';
                    ds.position = 'absolute';
                    ds.bottom = ds.left = ds.padding = ds.margin = '0';
                    /*if ($.browser.msie)
                        ds.setExpression('width', 'this.parentNode.offsetWidth');
                    else*/
                        ds.width = '100%';
                }
                else if (!bot && $.browser.msie) {
                    if ($.css(this,'position') == 'static')
                        this.style.position = 'relative';
                    ds.position = 'absolute';
                    ds.top = ds.left = ds.right = ds.padding = ds.margin = '0';

                    // fix ie6 problem when blocked element has a border width
                    var bw = 0;
                    if (ie6 || !$.boxModel)
                        bw = sz(this,'borderLeftWidth') + sz(this,'borderRightWidth');
                   // ie6 ? ds.setExpression('width', 'this.parentNode.offsetWidth - '+bw+'+ "px"') : ds.width = '100%';
                }
                else {
                    ds.margin = !bot ? '-'+pad.T+'px -'+pad.R+'px '+(pad.T-width)+'px -'+pad.L+'px' :
                                        (pad.B-width)+'px -'+pad.R+'px -'+pad.B+'px -'+pad.L+'px';
                }

                for (var i=0; i < width; i++) {
                    var w = Math.max(0,getW(i));
                    var e = strip.cloneNode(false);
                    e.style.borderWidth = '0 '+(opts[j+'R']?w:0)+'px 0 '+(opts[j+'L']?w:0)+'px';
                    bot ? d.appendChild(e) : d.insertBefore(e, d.firstChild);
                }
            }
        }
    });
};

$.fn.uncorner = function(o) { return $('.jquery-corner', this).remove(); };

})(jQuery);

$(function(){
       // $('div.inner').wrap('<div class="outer"></form>');
		$("#busca, #botoes, #info, #box-busca").corner("round 8px");
		$(".titulo").corner("round 8px top");
		$(".box").corner("round 8px bottom");
	});