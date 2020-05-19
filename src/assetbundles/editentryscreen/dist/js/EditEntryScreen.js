/**
 * Level Entry Types plugin for Craft CMS
 *
 * EditEntryScreen JS
 *
 * @author    Conflux Group, Inc.
 * @copyright Copyright (c) 2020 Conflux Group, Inc.
 * @link      https://confluxgroup.com
 * @package   LevelEntryTypes
 * @since     1
 */
(function($){
	Craft.LevelEntryTypesEntry = Garnish.Base.extend({
		// DOM elements
		$sectionId: null,
		$selectedParent: null,

		// Initialization method
		init: function() {
			var self = this;
			
			self.$sectionId = $("input[name='sectionId']").val();
			self.$selectedParent = $('#parentId').data('elementSelect').getElements().first().data('id');

		    self.refreshEntryTypes(self.$selectedParent, self.$sectionId);

			$('#parentId').data('elementSelect').on('selectElements', function(e) {
		    	self.$selectedParent = e.elements[0].id || 0;
		    	self.refreshEntryTypes(self.$selectedParent, self.$sectionId);
			});

			$('#parentId').data('elementSelect').on('removeElements', function(e) {
		    	self.$selectedParent = 0;
		    	self.refreshEntryTypes(self.$selectedParent, self.$sectionId);
			});

		    $("#entryType").on('change', function(){
		        self.$selectedParent = $('#parentId').data('elementSelect').getElements().first().data('id');
		        self.refreshEntryTypes(self.$selectedParent, self.$sectionId);
		    });
		},

		// URL param method for getting query params
		urlParam: function (name) {
			var self = this;

    		var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
		    if (results == null) {
		        return 0;
		    }
		    return results[1] || 0;
		},

		// Handles refreshing allowed entry types based on current
		// entry's parent and section
		refreshEntryTypes: function(parentId, sectionId) {
			var self = this;

			$.ajax({
		    	type: "GET",
		    	url: "/actions/level-entry-types/entry-types?parentId=" + parentId + "&sectionId=" + sectionId,
		    	async: true,
		    	dataType: "json"
		  	}).done(function (response) {

		        $("#entryType-field").removeClass("entry-position-error");

		    	$('#entryType option').attr('disabled', false);

		    	if (response.disabledEntryTypes) {
		      		response.disabledEntryTypes.forEach( function (entryType) {
		      			$('#entryType option[value=' + entryType + ']').attr('disabled', true);
		      		});
			    }

			    // Special logic for new entries only
			    if(self.urlParam('fresh') == 1)
			    {
			    	var firstEnabledOption = $('#entryType').children('option:enabled').eq(0);
			    	var selectedOption = $('#entryType').children('option:selected').eq(0);

			    	// If the current selected option isn't enabled, then force
			    	// switch to the first enabled entry type
			    	if(selectedOption.prop('disabled') )
			    	{
			    		firstEnabledOption.prop('selected',true);
			    		$('#entryType').trigger('change');
			    	}
			    }

			    // Check if current entry type is disabled and display an error
			    var enabledAndSelected = $('#entryType').children('option:disabled:selected').eq(0).length || 0;

			    if(enabledAndSelected > 0)
			    {
			    	Craft.cp.displayError('The selected parent entry does not allow child entries of this type. Select a new parent or change entry types.');

		            $("#entryType-field").addClass("entry-position-error");
			    }
			});
		}
	});	
})(jQuery);