// JavaScript Document


// Hover na <tr>
$(document).ready(function() {
  $('table tbody tr').hover(
	function(){ $(this).addClass("over");}, 
	function(){ $(this).removeClass("over"); }
	);
 
});

// marcar todos checkbox
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