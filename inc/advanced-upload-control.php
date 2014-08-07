<?php

class MW_Advanced_Upload_Control extends WP_Customize_Control {

	public function __construct( $wp_customize, $control_id, $args = array() ) {

		// declare these first so they can be overridden
		$this->l10n = array(
			'remove' => __( 'Remove Image', 'mwau' ),
			'choose' => __( 'Choose an Image', 'mwau' ),
			'set' =>    __( 'Set as Your Image', 'mwau' ),
			'upload' => __( 'Pick or Upload Image', 'mwau' )
		);

		parent::__construct( $wp_customize, $control_id, $args );
	}

	// this will be critical for your JS constructor
	public $type = 'advancedUpload';

	// this allows overriding of global labels by a specific control
	public $l10n = array();

	// the type of files that should be allowed by the media modal
	public $mime_type = 'image';

	public function enqueue() {
		// enqueues all needed media resources
		wp_enqueue_media();

		// Finally, ensure our control script and style are enqueued
		wp_enqueue_script( 'advanced-upload-control' );
		wp_enqueue_style( 'advanced-upload-control' );
	}

	public function render_content() {
		// We do this to allow the upload control to specify certain labels
		$l10n = json_encode( $this->l10n );

		printf(
			'<span class="customize-control-title" data-l10n="%s" data-mime="%s">%s</span>',
			esc_attr( $l10n ),
			esc_attr( $this->mime_type ),
			esc_html( $this->label )
		);
	}
}