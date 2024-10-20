( function( api ) {
	// Extends our custom "kids-campus" section.
	api.sectionConstructor['kids-campus'] = api.Section.extend( {
		// No events for this type of section.
		attachEvents: function () {},
		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );
} )( wp.customize );