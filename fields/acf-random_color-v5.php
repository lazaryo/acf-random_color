<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_random_color') ) :


class acf_field_random_color extends acf_field {
	
	
	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function __construct( $settings ) {
		
		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/
		
		$this->name = 'random_color';
		
		
		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/
		
		$this->label = __('Random Color', 'acf-random_color');
		
		
		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/
		
		$this->category = 'basic';
		
		
		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/
		
		$this->defaults = array(
			'default_value'	=>	'#333333',
		);
		
		
		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('random_color', 'error');
		*/
		
		$this->l10n = array(
			'error'	=> __('Error! Please enter a valid hex value.', 'acf-random_color'),
		);
		
		
		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/
		
		$this->settings = $settings;
		
		
		// do not delete!
    	parent::__construct();
    	
	}
	
	
	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field_settings( $field ) {
		
		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/
		
		acf_render_field_setting( $field, array(
			'label'			=> __('Random Color','acf-random_color'),
			'instructions'	=> __('Include the # for the hex color','acf-random_color'),
			'type'			=> 'text',
			'name'			=> 'fields['.$key.'][random_color]',
			'value'		=> '$field['default_value']',
		));

	}
	
	
	
	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field( $field ) {
		
		
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
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	
	function input_admin_enqueue_scripts() {
		
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