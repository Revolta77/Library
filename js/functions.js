
$(document).ready(function () {
	$('#dtBasicExample').DataTable({
		"searching": true,
		"pagingType": "simple_numbers"
	});
	$('.dataTables_length').addClass('bs-select');


	$('#category').selectize({
		create: true,
		createOnBlur: true,
		sortField: 'text'
	}, {
		onChange: function(value) {
			console.log(this);
			console.log($(this).val());
		}
	});


	$( "#name" ).autocomplete({
		source: names
	});
	$( "#author" ).autocomplete({
		source: authors
	});

	$('#price').on('keypress', function (e) {
		var value = $(this).val();
		if (e.which == 46) {
			if (value.indexOf('.') != -1 || value.indexOf(',') != -1) {
				return false;
			}
		}
		if (e.which == 44) {
			if (value.indexOf('.') != -1 || value.indexOf(',') != -1) {
				return false;
			}
		}

		if (e.which != 8 && e.which != 0 && e.which != 46 && e.which != 44 && (e.which < 48 || e.which > 57)) {
			return false;
		}

		if ( ( value.indexOf('.') != -1 && value.split('.')[1].length >= 2 ) || ( value.indexOf(',') != -1 && value.split(',')[1].length >= 2 ) ){
			return false;
		}
	});

	$('form input, form select#category').on('change keyup mouseup', function(){
		var value, elem;
		var validation = false;
		if( $(this).is('select')){
			value = $(this).find(":selected").text();
			elem = $('form input#category-selectized');
			validation = true;

		} else if ( $(this).attr('id') != 'category-selectized' ){
			value = $(this).val();
			elem = $(this);
			validation = true;
		}
		if ( validation ){
			if( !value.length ){
				if( elem.hasClass('is-valid') ){
					elem.removeClass('is-valid');
				}
				if( !elem.hasClass('is-invalid') ){
					elem.addClass('is-invalid');
				}
			} else {
				if( !elem.hasClass('is-valid') ){
					elem.addClass('is-valid');
				}
				if( elem.hasClass('is-invalid') ){
					elem.removeClass('is-invalid');
				}
			}
			var allValid = true;
			$('form input').each(function(){
				if( !$(this).hasClass('is-valid')){
					allValid = false;
				}
			});
			if( allValid ){
				$('form button').prop("disabled", false);
			} else {
				$('form button').prop("disabled", true);
			}
		}

	});
});

function addBook(){
	var values = [];
	var row = [];
	$('form input').each(function( k,v ){
		var id, value;
		if( $(this).attr('type') == 'select-one' ){

			id = $(this).attr('id').replace('-selectized', '');
			value = $('#' + id ).val();

			values[k] = {
				'value': value,
				'id': id,
			};
		} else {

			id = $(this).attr('id');
			value = $(this).val();

			values[k] = {
				'value': value,
				'id': id,
			};
		}
		row[id] = value;

	});


	$.post( '/ajax/addBook.php', { data: values} )
		.done(function( response ) {
			var title = 'Kniha';
			var message = 'bola uložena do databázy.';
			var type  = 'success';
			if( response == 'no_data'){
				title = 'Dáta';
				message = 'niesú kompletné.';
				type  = 'warning';
			} else if( response == 'db_error' ){
				title = 'Kniha';
				message = 'nebola uložena. Skúste opakovať pridanie.';
				type  = 'danger';
			} else if( response == 'book_is_in_db'){
				title = 'Kniha';
				message = 'sa už nachádza v databáze.';
				type  = '';
			}
			if( type != '' ){
				$.notify({
					title: '<strong>' + title + '</strong>',
					message: message
				},{
					type: type,
				});
			} else {
				$.notify({
					title: '<strong>' + title + '</strong>',
					message: message
				});
			}

			if( type == 'success' ){
				var table = $('#dtBasicExample').dataTable();
				if( !$.inArray(row.author, authors) ){
					authors.push(row.author);
					$( "#author" ).autocomplete({
						source: authors
					});
				}
				if( !$.inArray(row.name, names) ){
					names.push(row.name);
					$( "#name" ).autocomplete({
						source: names
					});
				}

				table.DataTable().row.add([ row.name, row.isbn, row.price, row.category, row.author ]).draw()
			}

		});
}



