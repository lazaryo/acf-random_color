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
		// create Field HTML
		?>
		<div id="rn-color" style="color: #F9F9F9">
            <pre id="pre" style="color:#333"><?php print_r($field); ?></pre>
            <input id="spec" type="text">
            <input id="rn" class="button" type="button" value="Random Color">
            <input id="save-color" class="button" type="button" value="Save">
            
            <!-- storing the hex value for use -->
            <input id="hidden" name="<?php echo $field['name']; ?>" type="hidden" value="<?php echo $field['value']; ?>">
		</div>
		<script type="text/javascript">
            (function($) {
                // generate a random color and return it
                // add or remove charcters to further diversify the colors
                function getRandomColor() {
                    var letters = '01234ABCDEF';
                    var color = '#';
                    for (var i = 0; i < 6; i++ ) {
                        color += letters[Math.floor(Math.random() * 11)];
                    }

                    if (tinycolor(color).isDark()) {
                        color = tinycolor(color).lighten(20).toString();
                        return color
                    } else {
                        return color
                    }
                }
                
                // retrieve the saved value of the post
                var saved = '<?php echo $field['value']; ?>';
                
                // initialize the spectrum
                $('#spec').spectrum({
                        color: '<?php echo $field['value']; ?>',
                        showInput: true,
                        allowEmpty: false,
                        className: "piece-spectrum",
                        showInitial: true,
                        preferredFormat: "hex",
                        chooseText: "Confirm",
                        cancelText: "Dismiss",
                        hide: function(color) {
                            var new_color = color.toHexString().toUpperCase();
                            $('#hidden').val(new_color);
                            $('#save-color').hide();
                            $('#rn').attr('disabled', false);
                        },
                        change: function(color) {
                            var new_color = color.toHexString().toUpperCase();
                            console.log(new_color);
                            $('#hidden').val(new_color);
                            $('#save-color').hide();
                            $('#rn').attr('disabled', false);
                        }
                });
                
                $('.piece-spectrum').addClass('button');
                $('.sp-choose').addClass('button');
                
                // generate a new spectrum for new color
                $('#rn-color #rn').on('click', function(){
                    $('#spec').spectrum({
                        color: getRandomColor(),
                        showInput: true,
                        allowEmpty: false,
                        className: "piece-spectrum",
                        showInitial: true,
                        preferredFormat: "hex",
                        chooseText: "Confirm",
                        cancelText: "Dismiss",
                        hide: function(color) {
                            var new_color = color.toHexString().toUpperCase();
                            $('#hidden').val(new_color);
                            $('#save-color').hide();
                            $('#rn').attr('disabled', false);
                        },
                        change: function(color) {
                            var new_color = color.toHexString().toUpperCase();
                            console.log(new_color);
                            $('#hidden').val(new_color);
                            $('#save-color').hide();
                            $('#rn').attr('disabled', false);
                        }
                    });
                    $('#save-color').show();
                    
                    // for mimicking styles of WP buttons
                    $('.piece-spectrum').addClass('button');
                    $('.sp-choose').addClass('button');
                });
                
                // save new color before updating field
                $('#save-color').on('click', function(){
                    var new_color = tinycolor($('.sp-preview-inner').css('background-color'));
                    new_color = new_color.toHexString().toUpperCase();
                    $('#hidden').val(new_color);
                    $('#rn').attr('disabled', true);
                    $(this).hide();
                });
                
                // disabling random color button initially
                var postColor = $('#hidden').val();
                var defaultColor = '<?php echo $field['default_value']; ?>';
                if (postColor == defaultColor || postColor == '#333') {
                    $('#rn').attr('disabled', false);
                } else {
                    $('#rn').attr('disabled', true);
                }
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
        
		// wp_register_script( 'jquery-3.1.1', "{$url}assets/js/jquery-3.1.1.js", false, null );
		// wp_enqueue_script('jquery-3.1.1');
        
		wp_register_script( 'tinycolor', "{$url}assets/js/tinycolor-min.js", array('acf-input'), $version );
		wp_enqueue_script('tinycolor');

		wp_register_script( 'spectrum', "{$url}assets/js/spectrum.js", array('acf-input'), $version );
		wp_enqueue_script('spectrum');
        
		wp_register_script( 'input-script', "{$url}assets/js/input.js", array('acf-input'), $version );
		wp_enqueue_script('input-script');
		
		
		// register & include CSS
		wp_register_style( 'spectrum', "{$url}assets/css/spectrum.css", array('acf-input'), $version );
		wp_enqueue_style('spectrum');

		wp_register_style( 'input-styling', "{$url}assets/css/input.css", array('acf-input'), $version );
		wp_enqueue_style('input-styling');		
	}
}


// initialize
new acf_field_random_color( $this->settings );


// class_exists check
endif;

?>