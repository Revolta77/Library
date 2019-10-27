<?php

class Table {

	public $keys, $head, $table = [];

	public function __construct( $data ){
		if ( !empty($data['head']) ) foreach ( $data['head'] as $key => $value ){
			$this->keys[] = $key;
			$this->head[] = $value;
		}

		if ( !empty($data['data']) ){
			$this->table = $data['data'];
		}

	}

	public function tableHead(){
		$ret = [];
		$ret['head'] = '<thead>';
		$ret['foot'] = '<tfoot>';

		$tr = $this->tableRow( $this->head, 'th' );
		$ret['head'] .= $tr;
		$ret['foot'] .= $tr;

		$ret['head'] .= '</thead>';
		$ret['foot'] .= '</tfoot>';

		return $ret;
	}

	public function tableTh( $value, $class = '' ){
		if ( $class != '' ){
			$class = ' class="' . $class . '"';
		}
		return '<th' . $class . '>' . $value . '</th>';
	}

	public function tableTd( $value ){
		return '<td>' . $value . '</td>';
	}

	public function tableRow( $data, $tag ){
		if ( !empty($data) ) {
			$ret = '<tr>';
			$x = 0;
			foreach ( $data as $value ) {
				if ( $tag == 'th' ) {
					$class = '';
					if ( $x == 0 ){
						$class = 'first';
					}
					$ret .= $this->tableTh( $value, $class );
				} else if ( $tag == 'td' ) {
					$ret .= $this->tableTd( $value );
				}
				$x++;
			}
			$ret .= '</tr>';
			return $ret;
		}
		return '';
	}

	public function tableBody(){

		if ( !empty($this->table) ){
			$ret = '<tbody>';
			foreach ( $this->table as $value ){
				$values = [];
				foreach ( $this->keys as $key ){
					$values[$key] = $value[$key];
				}
				$ret .= $this->tableRow( $values, 'td' );
			}
			$ret .= '</tbody>';
			return $ret;
		}
		return '';
	}

	public function createTable (){

		$head_foot = $this->tableHead();
		$body = $this->tableBody();

		$ret = '<table id="dtBasicExample" class="table table-striped table-bordered table-sm w-auto dataTable table-responsive" cellspacing="0" width="100%">';
		$ret .= $head_foot['head'];
		$ret .= $body;
		$ret .= $head_foot['foot'];
		$ret .= '</table>';
		return $ret;

	}

}