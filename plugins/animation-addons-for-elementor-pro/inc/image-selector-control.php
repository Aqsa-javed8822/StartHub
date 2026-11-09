<?php
namespace Elementor\CustomControl;

use \Elementor\Base_Data_Control;

class ImageSelector_Control extends Base_Data_Control {


	const ImageSelector = 'wcf_image_selector';

	/**
	 * Set control type.
	 */
	public function get_type() {
		return self::ImageSelector;
	}

	/**
	 * Enqueue control scripts and styles.
	 */
	public function enqueue() {
		wp_enqueue_style( 'image-selector', WCF_ADDONS_PRO_URL . '/assets/css/image-selector.css' );
	}

	/**
	 * Set default settings
	 */
	protected function get_default_settings() {
		return [
			'label_block' => true,
			'toggle' => true,
			'options' => [],
		];
	}

	/**
	 * control field markup
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid('{{ value }}');
		?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-wcf-image-selector-wrapper">
                <# _.each( data.options, function( options, value ) { #>
                <input id="<?php echo $control_uid; ?>" type="radio" name="elementor-image-selector-{{ data.name }}-{{ data._cid }}" value="{{ value }}" data-setting="{{ data.name }}">
                <label class="elementor-image-selector-label tooltip-target" for="<?php echo $control_uid; ?>" data-tooltip="{{ options.title }}" title="{{ options.title }}">
                    <img src="{{ options.url }}" alt="{{ options.title }}">
                    <span class="elementor-screen-only">{{{ options.title }}}</span>
                </label>
                <# } ); #>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
		<?php
	}

}
