<?php 
	
	class HelperForm extends Model{
		
		public $module;
		public $name_controller;
		public $identifier ;
		public $token ; 
		public $tpl_vars;

		public $langid = 1;

		public function generateForm( $fields, $data=array() ){

			

				$output =  '<form action="" method="post">';
				foreach( $fields as $field ){  
					$output .= '<fieldset>';
				//  echo '<pre>'.print_r( $field ,1 );die; 
					$output .= '<legend>'.$field['form']['legend']['title'].'</legend>';
					$output .='<div class="forms-content">';
				 	$output .= $this->_renderFormByFields(  $field['form']['input'], $data );
					$output .='</div>'; 
					$output .='</fieldset>';
				}
				

		
				$output .= '</form>';
			return $output;
		}	

		/**
	 * render widget setting form with passed  fields. And auto fill data values in inputs.
	 */
	protected function _renderFormByFields( $fields, $data ){
		
	
		$data = $this->tpl_vars['fields_value'];

		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();

		$output = '<table class="form">';

		$default_value= '';
		foreach( $fields as $widget => $field ){
			
			$input = '';
 			$val = isset($data[$field['name']])?$data[$field['name']]:"";
 			$default_value= $val;
			$attrs = isset($fields['attrs'])?$fields['attrs']:""; 

			$attrs .= isset( $field['class'] )? ' class="'.$field['class'].' form-control"' :'class="form-control"';
			$attrs .= isset( $field['id'] )? ' id="'.$field['id'].'"' :"";


			$widget = 'html';

			if( $field['type'] == 'hidden' ){
				$output .= '<tr><td colspan="2"><input  '.$attrs.' type="hidden" name="'.$field['name'].'" value="'.$val.'"></td</tr>';
				continue;
			}

 			$output .= '<tr>';
 			$output .=  '<td>'.$field['label'].'</td>';

 			switch( $field['type']  ){
 				case 'switch':
 					$input .= '<select  '.$attrs.' name="'.$field['name'].'">';
 					 	
					foreach( $field['values'] as $val ){
						if($default_value == $val['value']){
							$input .= '<option value="'.$val['value'].'" selected="selected">'.$val['label'].'</option>';
						}else{
							$input .= '<option value="'.$val['value'].'">'.$val['label'].'</option>';
						}
					}
 					$input .= '</select>';

 					break;	
 				case 'text':  
 					if( is_array($val) ){
 						$val = implode( ",", $val );
 					}
 					if( isset($field['lang']) ){
 				
						 
						 foreach( $languages as $language ){
						 	$input .= '<div class="input-language'.$language['language_id'].' switch-langs clearfix">';
						 	$input .= '<p><label>'.$language['name'].' : </label></p>';
						 	
						 	$fname = $field['name'].'_'.$language['language_id'];
						  	$val = isset($data[$fname])?$data[$fname]:"";

						 	$input .= '<input  '.$attrs.' type="text" value="'.$val.'" name="'.$fname.'">';
						 	$input .= '</div>';
						 }

 					}else { 					
 						$input .= '<input   '.$attrs.' type="text" name="'.$field['name'].'" value="'.$val.'">';
 					}	

 					break;

 				case 'date':
 					$input .= '<div class="input-group date">';
 					$input .= '<input class="form-control" type="text" name="'.$field['name'].'" value="'.$val.'" data-date-format="YYYY-MM-DD"/>';
 					$input .= '<span class="input-group-btn"><button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button></span>';
 					$input .= '</div>';
 					$input .= '<script type="text/javascript">
 						// Date
						$(\'.date\').datetimepicker({
							pickTime: false
						});

						$(\'.time\').datetimepicker({
							pickDate: false
						});

						$(\'.datetime\').datetimepicker({
							pickDate: true,
							pickTime: true
						});
					</script>';
 					break;
 				case 'select-multiple':
 					$input .= '<select  '.$attrs.' name="'.$field['name'].'[]" multiple="multiple">';
 					$values =  isset($data[$field['name']]) ? $data[$field['name']] : array();
 
					foreach( $field['options'] as $val ){
						if( !empty($values) && in_array($val['category_id'],$values) ) {
							$input .= '<option value="'.$val['category_id'].'" selected="selected">'.$val['name'].'</option>';
						} else {
							$input .= '<option value="'.$val['category_id'].'">'.$val['name'].'</option>';
						}
					}
 					$input .= '</select>';
 					break;
 				case 'links-category':
 					$this->load->model('catalog/category');
 					$categories = $this->model_catalog_category->getCategories( array('limit' => 999999999, 'start'=>0 ) );

 					$input .= '<div class="table-responsive">
								<table id="attribute" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left">Link</td>
											<td></td>
										</tr>
									</thead><tbody><tr></tr>';
								$attribute_row = 0;
					$links = isset($data['links'])?$data['links']:array();
					foreach ($links as $link) {
						if(!empty($link)) {
							$input .= '<tr id="attribute-row'.$attribute_row.'">';
							$input .= '<td class="text-left">';
							$input .= '		<select class="form-control" name="links['.$attribute_row.']">';
											    foreach ($categories as $category) {
											    	$selected = ($link == $category['category_id'])?"selected='selected'":'';
							$input .= '			<option '.$selected.' value="'.$category["category_id"].'">'.$category["name"].'</option>';
											    }
							$input .= '     </select>';
							$input .= '</td>';
							$input .= '<td class="text-left removerow"><button type="button" data-toggle="tooltip" title="" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
							$input .= '</tr>';
							$attribute_row++;
						}
					}
					$input .= '</tbody>';
					$input .= '<tfoot>';
					$input .= '<tr>';
					$input .= '		<td colspan="1"></td>';
					$input .= '		<td class="text-left"><button type="button" onclick="addAttribute();" data-toggle="tooltip" title="" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
					$input .= '</tr></tfoot>';
					$input .= '</table></div>';

					// script
					$input .= '<script type="text/javascript">';
					$input .= '$(".removerow").click(function(){ $(this).parent().remove(); }); ';
					$input .= ' var attribute_row = '.$attribute_row.';';

					$input .= 'function addAttribute() {';
					$input .= 'html  = \'<tr id="attribute-row\' + attribute_row + \'">\';';
					$input .= 'html += \'  <td class="text-left">\'; ';
					$input .= 'html += \'  	<select class="form-control" name="links[\' + attribute_row + \']">\'; ';
									foreach ($categories as $category) {
					$input .= 'html += \'  		<option value="'.$category['category_id'].'">'.$category["name"].'</option>\'; ';
									}
					$input .= 'html += \'  </select>\'; ';
					$input .= 'html += \'  </td>\'; ';

					$input .= 'html += \'  <td class="text-left removerow"><button type="button"  data-toggle="tooltip" title="" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>\'; ';

					$input .= 'html += \'</tr>\'; ';

					$input .= '$("#attribute tbody").append(html); ';

					$input .= '$(".removerow").click(function(){ $(this).parent().remove(); }); ';
						
					$input .= 'attribute_row++;';
					$input .= '}';

					$input .= '</script>';

 					break;
 				case 'featured-category':
 					$this->load->model('catalog/category');
 					$categories = $this->model_catalog_category->getCategories( array('limit' => 999999999, 'start'=>0 ) );

 					$this->load->model('tool/image');
 					$placeholder = $this->model_tool_image->resize('no_image.png', 25, 25);
 					
 					$categories_featured = isset($data['categories_featured'])?$data['categories_featured']:array();

 					$input .= '<div class="table-responsive">
								<table id="attribute" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left">Image Icon</td>
											<td class="text-left">Category name</td>
											<td></td>
										</tr>
									</thead><tbody>';
								$attribute_row = 0;

					//echo "<pre>"; print_r($categories_featured); die;
					if(!empty($categories_featured)) {
						foreach ($categories_featured as $category_featured) {

							$thumb = $this->model_tool_image->resize($category_featured['image'], 30, 30);

							$img = isset($thumb)?$thumb:$placeholder;

							$input .= '<tr id="attribute-row'.$attribute_row.'">';

							$input .= '<td class="text-left"><a href="" id="thumb-image'.$attribute_row.'" data-toggle="image" class="img-thumbnail">';
							$input .= '<img src="'.$img.'" alt="" title="" data-placeholder="'.$placeholder.'" /></a>';
							$input .= '     <input type="hidden" name="categories_featured['.$attribute_row.'][image]" value="'.$category_featured['image'].'" id="input-image'.$attribute_row.'" /></td>';
		                	$input .= '<td class="text-left">';
							$input .= '		<select class="form-control" name="categories_featured['.$attribute_row.'][id]">';
											    foreach ($categories as $category) {
											    	$selected = ($category_featured['id'] == $category['category_id'])?"selected='selected'":'';
							$input .= '			<option '.$selected.' value="'.$category["category_id"].'">'.$category["name"].'</option>';
											    }
							$input .= '     </select>';
							$input .= '</td>';
							$input .= '<td class="text-left removerow"><button type="button" data-toggle="tooltip" title="" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';

							$input .= '</tr>';
							$attribute_row++;
						}
					}
					$input .= '</tbody>';

					$input .= '<tfoot>';
					$input .= '<tr>';
					$input .= '		<td colspan="2"></td>';
					$input .= '		<td class="text-left"><button type="button" onclick="addAttribute();" data-toggle="tooltip" title="" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
					$input .= '</tr></tfoot>';
					$input .= '</table></div>';


					// script
					$input .= '<script type="text/javascript">';
					$input .= '$(".removerow").click(function(){ $(this).parent().remove(); }); ';
					$input .= ' var attribute_row = '.$attribute_row.';';

					$input .= 'function addAttribute() {';
					$input .= 'html  = \'<tr id="attribute-row\' + attribute_row + \'">\';';
				
					$input .= 'html += \'  <td class="text-left"><a href="" id="thumb-image\'+ attribute_row +\'" data-toggle="image" class="img-thumbnail"><img src="'.$placeholder.'" alt="" title="" data-placeholder="'.$placeholder.'" /></a><input type="hidden" name="categories_featured[\' + attribute_row + \'][image]" value="" id="input-image\' + attribute_row + \'" /></td>\'; ';
			

					$input .= 'html += \'  <td class="text-left">\'; ';
					$input .= 'html += \'  	<select class="form-control" name="categories_featured[\' + attribute_row + \'][id]">\'; ';
									foreach ($categories as $category) {
					$input .= 'html += \'  		<option value="'.$category['category_id'].'">'.$category["name"].'</option>\'; ';
									}
					$input .= 'html += \'  </select>\'; ';
					$input .= 'html += \'  </td>\'; ';

					$input .= 'html += \'  <td class="text-left removerow"><button type="button"  data-toggle="tooltip" title="" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>\'; ';

					$input .= 'html += \'</tr>\'; ';

					$input .= '$("#attribute tbody").append(html); ';

					$input .= '$(".removerow").click(function(){ $(this).parent().remove(); }); ';
						
					$input .= 'attribute_row++;';
					$input .= '}';

					$input .= '</script>';

 					break;
 				case 'image':
 					$this->load->model('tool/image');
 					$placeholder = $this->model_tool_image->resize('no_image.png', 50, 50);

 					$thumb = $this->model_tool_image->resize($data['banner_img'], 50, 50);

 					$image = empty($thumb)?$placeholder:$thumb;

	 				$input .= '<a href="" id="thumb-image" data-toggle="image" class="img-thumbnail">';
					$input .= '<img src="'.$image.'" alt="" title="" data-placeholder="'.$placeholder.'" /></a>';
					$input .= '<input type="hidden" name="'.$field['name'].'" value="'.$data['banner_img'].'" id="input-image" />';

 					break;
 				case 'file':
 						$this->load->model('tool/image');
 						if ($val && file_exists(DIR_IMAGE . $val)) {
							$image = $val;
						} else {
							$image = 'no_image.jpg';
						}
 						$thumb = $this->model_tool_image->resize($image, 100, 100);
 						$no_image = $this->model_tool_image->resize('no_image.jpg', 100, 100);
 						$token = $this->session->data['token'];
 						$input .= '<div class="image"><img src="'.$thumb.'" alt="" id="thumb'.$widget.'" />
                          <input type="hidden" name="params['.$widget.']" value="'.$val.'" id="image'.$widget.'"  />
                          <br />
                          <a onclick="image_upload(\'image'.$widget.'\', \'thumb'.$widget.'\');">'.$this->language->get("text_browse").'</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb'.$widget.'\').attr(\'src\', \''.$no_image.'\'); $(\'#image'.$no_image.'\').attr(\'value\', \'\');">'.$this->language->get("text_clear").'</a></div>';
                        $input .= '
                        <script type="text/javascript"><!--
						function image_upload(field, thumb) {
							$(\'#dialog\').remove();
							
							$(\'#content\').prepend(\'<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token='.$token.'&field=\' + encodeURIComponent(field) + \'" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>\');
							
							$(\'#dialog\').dialog({
								title: \''.$this->language->get("text_image_manager").'\',
								close: function (event, ui) {
									if ($(\'#\' + field).attr(\'value\')) {
										$.ajax({
											url: \'index.php?route=common/filemanager/image&token='.$token.'&image=\' + encodeURIComponent($(\'#\' + field).attr(\'value\')),
											dataType: \'text\',
											success: function(data) {
												$(\'#\' + thumb).replaceWith(\'<img src="\' + data + \'" alt="" id="\' + thumb + \'" />\');
											}
										});
									}
								},	
								bgiframe: false,
								width: 700,
								height: 400,
								resizable: false,
								modal: false
							});
						};
						//--></script>
                        ';
 				break;
				
				case 'ajax_product':
					
					$id = isset($field['key'])?$field['key']:'0';

					$token = $this->session->data['token'];
					$input .=	'<input type="text" name="choose_product_'.$id.'" value=""  id="input-product" class="form-control" />';
					$input .=		'<div id="choose-product-'.$id.'" class="well well-sm" style="height: 150px; overflow: auto;">';
							foreach ($field['products'] as $key => $product) {
							$input .=	'<div id="choose-product-'.$id.'">
											<i class="fa fa-minus-circle"></i> '.$product.'
											<input type="hidden" name="'.$field['name'].'" value="'.$key.'" />
										</div>';
							}
					$input .=		'</div>';
					$input .='
						<script src="view/javascript/common.js" type="text/javascript"></script>
						<script type="text/javascript">
							$("input[name=\'choose_product_'.$id.'\']").autocomplete({
								\'source\': function(request, response) {
									$.ajax({
										url: \'index.php?route=catalog/product/autocomplete&token='.$token.'&filter_name=\'+encodeURIComponent(request),
										dataType: \'json\',			
										success: function(json) {
											response($.map(json, function(item) {
												return {
													label: item[\'name\'],
													value: item[\'product_id\']
												}
											}));
										}
									});
								},
								\'select\': function(item) {
									$("input[name=\'choose_product_'.$id.'\']").val(\'\');
									
									$(\'#choose-product-'.$id.'\' + item[\'value\']).remove();
									
									$(\'#choose-product-'.$id.'\').append(\'<div id="choose-product-'.$id.'\' + item[\'value\'] + \'"><i class="fa fa-minus-circle"></i> \' + item[\'label\'] + \'<input type="hidden" name="'.$field['name'].'" value="\' + item[\'value\'] + \'" /></div>\');	
								}	
							});

							$(\'#choose-product-'.$id.'\').delegate(\'.fa-minus-circle\', \'click\', function() {
								$(this).parent().remove();
							});
						</script>
						'; 
					
 				break;		
 				case 'image':
 					$this->load->model('tool/image');
 					$placeholder = $this->model_tool_image->resize('no_image.png', 50, 50);

 					$thumb = $this->model_tool_image->resize($data['banner_img'], 50, 50);

 					$image = empty($thumb)?$placeholder:$thumb;

	 				$input .= '<a href="" id="thumb-image" data-toggle="image" class="img-thumbnail">';
					$input .= '<img src="'.$image.'" alt="" title="" data-placeholder="'.$placeholder.'" /></a>';
					$input .= '<input type="hidden" name="'.$field['name'].'" value="'.$data['banner_img'].'" id="input-image" />';

 					break;				
 				case 'select':
 					$input .= '<select  '.$attrs.' name="'.$field['name'].'">';
 					 
 					$default_value =  isset($data[$field['name']]) ? $data[$field['name']] : "";
 
 						foreach( $field['options']['query'] as $val ){
 							if($default_value == $val[$field['options']['id']] ){
 								$input .= '<option value="'.$val[$field['options']['id']].'" selected="selected">'.$val[$field['options']['name']].'</option>';
 							}else{
 								$input .= '<option value="'.$val[$field['options']['id']].'">'.$val[$field['options']['name']].'</option>';
 							}
 							 
 						}
 					$input .= '</select>';
 					
 					break;
 				case 'textarea':
 					

 					if( isset($field['lang']) ){
						 
						 foreach( $languages as $language ){
						 	$input .= '<div class="input-language'.$language['language_id'].' switch-langs clearfix">';
						 	$input .= '<p><label>'.$language['name'].' : </label></p>';
						  	
						 	$fname = $field['name'].'_'.$language['language_id'];
						  	$val = isset($data[$fname])?$data[$fname]:"";

						 	$input .= '<textarea class="input-langs form-control" '.$attrs.' type="text" id="content'.$fname.'"  name="'.$fname.'">'.$val.'</textarea>';
						 	$input .= '</div>';
						 }

						 if( $field['autoload_rte'] ) {
							 $input .= '<script type="text/javascript">';

							  foreach( $languages as $language ){
							 	$input .= "
							 		//	$('#content".$field['name'].'_'.$language['language_id']."').destroy();
									//	$('#content".$field['name'].'_'.$language['language_id']."').summernote({'height':300});
							 	";
							 }
							 $input .= '</script>';
						}	 
 					}else {
 					  
 						$input .= '<textarea  '.$attrs.' style="height:80px" name="'.$field['name'].'">'.$val.'</textarea>';
 					}	
 					break;	
 			}
 			$btn = '';

 		// 	echo '<pre>'.print_r( $languages,1 );die;
 			if( isset($field['lang']) ){
 				$btn = ' <div class="btn-group group-langs">
						  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						    '.$this->language->get('Switch Language').' <span class="caret"></span>
						  </button>
						  <ul class="dropdown-menu" role="menu">';

				foreach( $languages as $language ){		  
					$btn .=	   '<li data-lang="input-language'.$language['language_id'].'"><div >'. $language['name'].'</div></li>';
				}	
				$btn .=		'</ul>
						</div> ';
 			}
 			$output .= '<td><div class="input-wrap">'.$input.$btn.(isset($field['desc']) && $field['desc']? '<div class="input-desc">'.$field['desc'].'</div>':"").'</div></td>';
			$output .= '</tr>';
 		}	
 		
 		$output .= '</table>';


 		$output .= '<script type="text/javascript">
 				reloadLanguage( "input-language'.$this->config->get('config_language_id').'" );
 		';
 		$output .='</script>';

 		return $output;
	}
	}
?>