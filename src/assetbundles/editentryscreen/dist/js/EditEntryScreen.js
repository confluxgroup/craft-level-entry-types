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

 $.urlParam = function (name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results == null) {
        return 0;
    }
    return results[1] || 0;
}

$.refreshEntryTypes = function(parentId, sectionId) {
	$.ajax({
    	type: "GET",
    	url: "/actions/level-entry-types/entry-types?parentId=" + parentId + "&sectionId=" + sectionId,
    	async: true,
    	dataType: "json"
  	}).done(function (response) {
    	
    	$('#entryType option').attr('disabled', false);

    	if (response.disabledEntryTypes) {
      		response.disabledEntryTypes.forEach( function (entryType) {
      			$('#entryType option[value=' + entryType + ']').attr('disabled', true);
      		});
	    }

	    // Special logic for new entries only
	    if($.urlParam('fresh') == 1)
	    {
	    	firstEnabledOption = $('#entryType').children('option:enabled').eq(0);
	    	selectedOption = $('#entryType').children('option:selected').eq(0);
	    	
	    	// If the current selected option isn't enabled, then force 
	    	// switch to the first enabled entry type
	    	if(selectedOption.prop('disabled') )
	    	{
	    		firstEnabledOption.prop('selected',true);
	    		$('#entryType').trigger('change');
	    	}
	    }

	    // Check if current entry type is disabled and display an error
	    enabledAndSelected = $('#entryType').children('option:disabled:selected').eq(0).length || 0;
	    
	    if(enabledAndSelected > 0)
	    {
	    	Craft.cp.displayError('The selected parent entry does not allow child entries of this type. Select a new parent or change entry types.');
	    }
	});
}


$(function () {
	var sectionId = $("input[name='sectionId']").val();

	selectedParent = $('#parentId').data('elementSelect').getElements().first().data('id');
    $.refreshEntryTypes(selectedParent, sectionId);

	$('#parentId').data('elementSelect').on('selectElements', function(e) {
    	selectedParent = e.elements[0].id || 0;
    	$.refreshEntryTypes(selectedParent, sectionId);
	});

	$('#parentId').data('elementSelect').on('removeElements', function(e) {
    	selectedParent = 0;
    	$.refreshEntryTypes(selectedParent, sectionId);
	});

});	


