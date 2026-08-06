( function ( blocks, element, blockEditor, components, i18n ) {
	var el = element.createElement;
	var __ = i18n.__;
	var useBlockProps = blockEditor.useBlockProps;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;
	var Placeholder = components.Placeholder;

	blocks.registerBlockType( 'ecwp/calendar', {
		edit: function ( props ) {
			return el(
				'div',
				useBlockProps(),
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __( 'Calendar Settings', 'monthly-events-calendar' ) },
						el( SelectControl, {
							label: __( 'Default view', 'monthly-events-calendar' ),
							value: props.attributes.view,
							options: [
								{ label: __( 'Month', 'monthly-events-calendar' ), value: '' },
								{ label: __( 'Day', 'monthly-events-calendar' ), value: 'day' },
								{ label: __( 'List', 'monthly-events-calendar' ), value: 'list' }
							],
							onChange: function ( value ) {
								props.setAttributes( { view: value } );
							}
						} )
					)
				),
				el( Placeholder, {
					icon: 'calendar-alt',
					label: __( 'Monthly Events Calendar', 'monthly-events-calendar' ),
					instructions: __( 'The events calendar will appear here on the published page.', 'monthly-events-calendar' )
				} )
			);
		},
		save: function () {
			return null;
		}
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n );