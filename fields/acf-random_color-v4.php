<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_random_color') ) :


class acf_field_random_color extends acf_field {
	
	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options
		
		
	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function __construct( $settings )
	{
		// vars
		$this->name = 'random_color';
		$this->label = __('Random Color');
		$this->category = __("Basic",'acf'); // Basic, Content, Choice, etc
		$this->defaults = array(
			// add default here to merge into your field. 
			// This makes life easy when creating the field options as you don't need to use any if( isset('') ) logic. eg:
			//'preview_size' => 'thumbnail'
            'default_value'	=>	'#333333',
		);
		
		
		// do not delete!
    	parent::__construct();
    	
    	
    	// settings
		$this->settings = $settings;

	}
	
	
	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like below) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/
	
	function create_options( $field )
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/
		
		// key is needed in the field names to correctly save the data
		$key = $field['name'];
		
		
		// Create Field Options HTML
		?>
        <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label><?php _e("Default",'acf'); ?></label>
                <p class="description"><?php _e("Include the # for the hex color",'acf'); ?></p>
            </td>
            <td>
                <?php

                do_action('acf/create_field', array(
                    'type'		=>	'text',
                    'name'		=>	'fields['.$key.'][random_color]',
                    'value'		=>	$field['default_value'],
                ));

                ?>
            </td>
        </tr>
		<?php
		
	}
	
	
	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function create_field( $field )
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/
		
		// perhaps use $field['preview_size'] to alter the markup?
		
		
		// create Field HTML
		?>
		<div id="rn-color" style="color: #F9F9F9">
           <pre style="color:#333"><?php print_r($field); ?></pre>
            <input id="spec" type="text">
            <input name="<?php echo $field['name']; ?>" type="hidden" value="<?php echo $field['value']; ?>">
            <input id="rn" class="button" type="button" value="Random Color" style="height: 33px">
		</div>
		<script type="text/javascript">
            (function($) {
                function getRandomColor() {
                    var letters = '01234ABCDEF';
                    var color = '#';
                    for (var i = 0; i < 6; i++ ) {
                        color += letters[Math.floor(Math.random() * 11)];
                    }

                    if (tinycolor(color).isDark()) {
                        console.log(color + ' is too dark');
                        color = tinycolor(color).lighten(20).toString();
                        return color
                    } else {
                        return color
                    }
                }
                var saved = '<?php echo $field['value']; ?>';
                if (saved == '#333333' || saved == '#333') {
//                    $('#rn').attr('disabled', true);
                } else {
                    $('#rn').attr('disabled', false);
                }
                
                $('#rn-color .button').on('click', function(){
                    $('#spec').spectrum({
                        color: getRandomColor(),
                        showInput: true,
                        allowEmpty: false,
                        className: "piece-spectrum",
                        showInitial: true,
                        preferredFormat: "hex",
                        chooseText: "Confirm",
                        cancelText: "Dismiss",
                        change: function(color) {
                            var new_color = color.toHexString().toUpperCase();
                            console.log(getRandomColor());
                            console.log(new_color);
                            if (new_color == '#333333' || new_color == '#333') {
//                    $('#rn').attr('disabled', true);
                } else {
                    $('#rn').attr('disabled', false);
                }
                            alert('woah7');
                        }
                    });
                    
                    $('.piece-spectrum').addClass('button');
                    $('.piece-spectrum').css('height', '33px');
                    $('.sp-container').css('height', 'auto');
                    $('.sp-picker-container').css('border', 'none');
                    $('.sp-replacer.sp-light.piece-spectrum.button > *').css('line-height', '26px');
                    $('.sp-preview').css('margin-top', '5px');
                });
                
                $('#spec').spectrum({
                        color: '<?php echo $field['value']; ?>',
                        showInput: true,
                        allowEmpty: false,
                        className: "piece-spectrum",
                        showInitial: true,
                        preferredFormat: "hex",
                        chooseText: "Confirm",
                        cancelText: "Dismiss",
                        change: function(color) {
                            var new_color = color.toHexString().toUpperCase();
                            console.log(getRandomColor());
                            console.log(new_color);
                            if (new_color != '#333333' || new_color != '#333') {
                                $('#rn').attr('disabled', true);
                            } else {
                                $('#rn').attr('disabled', false);
                            }
                            alert('woah');
                        }
                });
                
                $('.piece-spectrum').addClass('button');
                $('.piece-spectrum').css('height', '33px');
                $('.sp-container').css('height', 'auto');
                $('.sp-picker-container').css('border', 'none');
                $('.sp-replacer.sp-light.piece-spectrum.button > *').css('line-height', '26px');
                $('.sp-preview').css('margin-top', '5px');
            })(jQuery);
        </script>
		<?php
	}
	
	
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your create_field() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_enqueue_scripts()
	{
		// Note: This function can be removed if not used
		
		
		// vars
		$url = $this->settings['url'];
		$version = $this->settings['version'];
		
		
		// register & include JS
		wp_register_script( 'jquery', "https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js", false, null );
		wp_enqueue_script('jquery');
		wp_register_script( 'tinycolor', "{$url}assets/js/tinycolor-min.js", array('acf-input'), $version );
		wp_enqueue_script('tinycolor');
		wp_register_script( 'acf-input-random_color', "{$url}assets/js/spectrum.js", array('acf-input'), $version );
		wp_enqueue_script('acf-input-random_color');
		
		
		// register & include CSS
		wp_register_style( 'acf-input-random_color', "{$url}assets/css/spectrum.css", array('acf-input'), $version );
		wp_enqueue_style('acf-input-random_color');
		
	}
	
	
	
	/*
	*  load_value()
	*
		*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value found in the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the value to be saved in the database
	*/
	
	function load_value( $value, $post_id, $field )
	{
		// Note: This function can be removed if not used
		return $value;
	}
	
	
	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is updated in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value which will be saved in the database
	*  @param	$post_id - the $post_id of which the value will be saved
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the modified value
	*/
	
	function update_value( $value, $post_id, $field )
	{
		// Note: This function can be removed if not used
		return $value;
	}
	
	
	/*
	*  format_value()
	*
	*  This filter is applied to the $value after it is loaded from the db and before it is passed to the create_field action
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/
	
	function format_value( $value, $post_id, $field )
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/
		
		// perhaps use $field['preview_size'] to alter the $value?
		
		
		// Note: This function can be removed if not used
		return $value;
	}
	
	
	/*
	*  format_value_for_api()
	*
	*  This filter is applied to the $value after it is loaded from the db and before it is passed back to the API functions such as the_field
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/
	
	function format_value_for_api( $value, $post_id, $field )
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/
		
		// perhaps use $field['preview_size'] to alter the $value?
		
		
		// Note: This function can be removed if not used
		return $value;
	}
	
	
	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$field - the field array holding all the field options
	*/
	
	function load_field( $field )
	{
		// Note: This function can be removed if not used
		return $field;
	}
	
	
	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*  @param	$post_id - the field group ID (post_type = acf)
	*
	*  @return	$field - the modified field
	*/

	function update_field( $field, $post_id )
	{
		// Note: This function can be removed if not used
		return $field;
	}

}


// initialize
new acf_field_random_color( $this->settings );


// class_exists check
endif;

?>