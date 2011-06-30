// JavaScript Document
function MaisAutores(){

    if (document.getElementById('maisautores').style.display=='none'){
    //    alert("exibe");   
        exibe('maisautores');
		oculta('botaoexibe');
		 exibe('botaooculta');
      
        }
    else{
    //    alert("oculta!!!");   
	   // exibe('ocultaautores');
      //  oculta('maisautores');
      
        }
       
}// fim da funcao

function MenosAutores(){

 
    //    alert("exibe");   
        oculta('botaooculta');
		 oculta('maisautores');
		 exibe('botaoexibe');
      
  
}// fim da funcao


function exibe(cxa){
if (document.getElementById(cxa).style.display=='none'){
    document.getElementById(cxa).style.display='block';
    }else{
        document.getElementById(cxa).style.display='block';       
        }
}

function oculta(cxa){
if (document.getElementById(cxa).style.display=='block'){
    document.getElementById(cxa).style.display='none';
    }else{
        document.getElementById(cxa).style.display='none';
        }
}
// JavaScript Document