(function( wp, $ ){
	// nice shortcut
	var api = wp.customize;
	/**
	 * The Customizer looks for wp.customizer.controlConstructor[type] functions
	 * where type == the type member of a WP_Customize_Control
	 */
	api.controlConstructor.advancedUpload = api.Control.extend({
		/**
		 * This method is called when the control is ready to run.
		 * Do all of your setup and event binding here.
		 */
		ready: function() {
			// this.container is a jQuery object of your container

			// grab the bits of data from the title for specifying this control
			var data = this.container.find( '.customize-control-title' ).data();

			// Use specific l10n data for this control where available
			this.l10n = data.l10n;
			// Grab mime type
			this.mime = data.mime;

			// Set up img, img container, and button elements. Cache for re-use.
			this.$button = $( '<a class="button upload-button" href="#">' + this.l10n.upload + '</a>' ).appendTo( this.container );
			this.$imgContainer = $( '<div><a href="#">' + this.l10n.remove + '</a></div> ' ).insertBefore( this.$button );
			this.$img = $( '<img class="advanced-thumbnail" />' ).prependTo( this.$imgContainer );

			// handy shortcut so we don't have to us _.bind every time we add a callback
			_.bindAll( this, 'removeImg', 'upload', 'render', 'pick' );

			// Ensure clicking "remove image" removes the image.
			this.$imgContainer.find( 'a' ).on( 'click', this.removeImg );
			// Bind upload button
			this.$button.on( 'click', this.upload );

			// Call render method whenever setting is changed.
			this.setting.bind( 'change', this.render );
			// Do initial rendering.
			this.render();
		},
		/**
		 * Remember that _.bind was used to maintain `this` as the control
		 * object rather than the usual jQuery way of binding to the DOM element.
		 */
		upload: function( event ) {
			event.preventDefault();

			if ( ! this.frame )
				this.initFrame();

			this.frame.open();
		},
		/**
		 * Set the media frame so that it can be reused and accessed when needed.
		 */
		initFrame: function() {
			this.frame = wp.media({
				// The title of the media modal
				title: this.l10n.choose,
				// restrict to specified mime type
				library: {
					type: this.mime
				},
				// Customize the submit button.
				button: {
					// Set the text of the button.
					text: this.l10n.set
				},
				// Just one, thanks.
				multiple: false
			});

			// When an image is selected, run a callback.
			this.frame.on( 'select', this.pick );
		},
		/**
		 * Fired when an image is selected in the media modal. Gets the selected
		 * image information, and sets it within the control.
		 */
		pick: function() {
			// get the attachment from the modal frame
			var attachment = this.frame.state().get( 'selection' ).first().toJSON();
			attachment = this.reduceMembers( attachment );
			// set the setting - the callback will take care of rendering
			this.setting( attachment );
		},
		/**
		 * Reduces the attachment object to just the few desired members.
		 * @param  {object} attachment An attachment object provided by the
		 *                             medial modal.
		 * @return {object}            A reduced media object.
		 */
		reduceMembers: function( attachment ) {
			var desired = [ 'id', 'sizes', 'url' ];
			var output = {};
			$.each( desired, function( i, key ){
				output[key] = attachment[key];
			});
			return output;
		},
		/**
		 * Called on init and whenever a setting is changed. Shows the thumbnail
		 * when there is one or the upload button when there isn't.
		 */
		render: function() {
			var value = this.setting();
			if ( value && value.url ) {
				// Valid URL. Show a preview thumbnail.
				this.$img.attr( 'src', value.url );
				this.$imgContainer.show();
				this.$button.hide();
			} else {
				// Hide thumbnail, show button
				this.$button.show();
				this.$imgContainer.hide();
			}
		},
		/**
		 * Called when the "Remove Image" link is clicked. Sets thes setting back
		 * to its default state.
		 * @param  {object} event jQuery Event object from click event
		 */
		removeImg: function( event ) {
			event.preventDefault();
			this.setting( {
				url: '',
				id: 0
			} );
		}

	});

})( this.wp, jQuery );