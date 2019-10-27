<body class = "container col-md-7 text-gold" >
<?php

include ( CLASSPATH . 'form.php' );
include ( CLASSPATH . 'books.php');
include ( CLASSPATH . 'table.php');

$books = new Books();

$form_data = [];
$form_data[] = [ 'type' => 'input', 'class' => 'form-group', 'id' => 'name', 'name' => 'Názov knihy' ];
$form_data[]['row'] = [
	[ 'type' => 'input', 'class' => 'form-group col-md-6', 'id' => 'isbn', 'name' => 'ISBN' ],
	[ 'type' => 'input', 'class' => 'form-group col-md-6', 'id' => 'price', 'name' => 'Cena' ],
];
$form_data[]['row'] = [
	[ 'type' => 'select', 'class' => 'form-group col-md-6', 'id' => 'category', 'name' => 'Kategória', 'data' => $books->getCategories() ],
	[ 'type' => 'input', 'class' => 'form-group col-md-6', 'id' => 'author', 'name' => 'Autor' ],
];
$form_data[] = [ 'type' => 'button', 'class' => 'btn btn-dark float-right', 'name' => 'Pridať knihu do knižnice', 'onclick' => 'addBook();' ];

$form = new Form();
$form_html = $form->form($form_data);

$table_data = [];
$table_data['head'] = [ 'name' => 'Názov', 'isbn' => 'ISBN', 'price' => 'Cena', 'category_name' => 'Kategória', 'author_name' => 'Autor' ];
$table_data['data'] = $books->getBooks();

?>
<script type="application/javascript">
	var authors = <?php echo json_encode(array_values($books->getAuthors()));?>;
	var names = <?php echo json_encode(array_values($books->getNames()));?>;
</script>
<?php

$table = new Table($table_data);
$table_html = $table->createTable();

?>
<p class="h1 text-center mt-3">Knižnica</p>
<div class="container p-3 rounded-lg backgrounded">
    <?php
	    echo $form_html;
    ?>
</div>
<hr>
<div class="container p-3 rounded-lg backgrounded">
	<?php
	echo $table_html;
	?>
</div>

</body>
