<?php


class Form {

	public function __construct() {

	}

	public function form ( $array = [], $class = '' ){

		if( $class != '' ){
			$class = ' class="' . $class . '"';
		}
		$ret = '<form' . $class . '>';
		foreach ( $array as $data ){
			if( isset($data['row']) ){
				$ret .= $this->row( $data['row'] );
			} else {
				$ret .= $this->content( $data );
			}
		}
		$ret .= '</form>';
		return $ret;
	}

	public function input( $class, $id, $name ){
		return '<div class="' . $class . '">
					<label for="' . $id . '">' . $name . '</label>
            		<input type="text" class="form-control bg-light" id="' . $id . '" placeholder="' . $name . '">
        		</div>';
	}

	public function content( $data ){

		$ret = '';
		if ( isset($data['type']) && $data['type'] == 'input' ){
			$ret .= $this->input( $data['class'], $data['id'], $data['name'] );
		} elseif ( isset($data['type']) && $data['type'] == 'select' ){
			$ret .= $this->select( $data['class'], $data['id'], $data['name'], $data['data'] );
		} elseif ( isset($data['type']) && $data['type'] == 'button' ){
			$ret .= $this->button( $data['type'], $data['class'], $data['name'], $data['onclick'] );
		}
		return $ret;
	}

	public function row( $array ){
		$ret = '<div class="form-row">';
		foreach ( $array as $data ){
			$ret .= $this->content( $data );
		}
		$ret .= '</div>';
		return $ret;
	}

	public function button( $type, $class, $name, $onclick = '' ){
		if( $onclick != ''){
			$onclick = ' onclick="' . $onclick . '"';
		}

		return '<div class="row">
    		<div class="col-lg-12">
        		<button type="' . $type . '" class="' . $class . '"' . $onclick . ' disabled>' . $name . '</button>
    		</div>
		</div>';
	}

	public function select( $class, $id, $name, $data = [] ){
		$ret =
			'<div class="' . $class . '">
				<label for="' . $id . '">' . $name . '</label>
				<select id="' . $id . '" class="demo-default selectized" placeholder="' . $name . '" tabindex="-1" style="display: none;">';
		$ret .= '<option value = ""></option>';
		if ( !empty( $data ) ) foreach ( $data as $key => $value){
			$ret .= '<option value = "' . $value['category_id'] . '">' . $value['category_name'] . '</option>';
		}
		$ret .= '</select>
			</div>';
		return $ret;
	}

}