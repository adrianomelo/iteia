// JavaScript Document
jQuery(function($){

			/*$.datepicker.setDefaults({showOn: 'both', buttonImageOnly: true, 
			buttonImage: 'jscripts/calendar/ico-calendar.gif', buttonText: 'Calendar'});*/
			
			$('.calendario').attachDatepicker({beforeShow: customRange}); 
 
			// Customize two date pickers to work as a date range 
			function customRange(input) { 
				return {minDate: (input.id == 'dTo' ? $('#dFrom').getDatepickerDate() : null), 
					maxDate: (input.id == 'dFrom' ? $('#dTo').getDatepickerDate() : null)}; 
			} 
			});

/* Brazilian initialisation for the jQuery UI date picker plugin. */
/* Written by Leonildo Costa Silva (leocsilva@gmail.com). */
			
			jQuery(function($){
				$.datepicker.regional['pt-BR'] = {clearText: 'Limpar', clearStatus: '',
					closeText: 'Fechar', closeStatus: '',
					prevText: '&lt;', prevStatus: '',
					nextText: '&gt;', nextStatus: '',
					currentText: 'Hoje', currentStatus: '',
					monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho',
					'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
					monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun',
					'Jul','Ago','Set','Out','Nov','Dez'],
					monthStatus: '', yearStatus: '',
					weekHeader: 'Sm', weekStatus: '',
					dayNames: ['Domingo','Segunda-feira','Ter&ccedil;a-feira','Quarta-feira','Quinta-feira','Sexta-feira','S&aacute;bado'],
					dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
					dayNamesMin: ['D','S','T','Q','Q','S','S'],
					dayStatus: 'DD', dateStatus: 'D, M d',
					dateFormat: 'dd/mm/yy', firstDay: 0, 
					initStatus: '', isRTL: false};
				$.datepicker.setDefaults($.datepicker.regional['pt-BR']);
			});
			