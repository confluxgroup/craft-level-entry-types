/**
 * Level Entry Types plugin for Craft CMS
 *
 * LevelEntryTypes JS
 *
 * @author    Conflux Group, Inc.
 * @copyright Copyright (c) 2020 Conflux Group, Inc.
 * @link      https://confluxgroup.com
 * @package   LevelEntryTypes
 * @since     1
 */

 $.urlParam = function (name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)')
                      .exec(window.location.href);
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

	    if($.urlParam('fresh') == 1)
	    {
	    	$('#entryType').children('option:enabled').eq(0).prop('selected',true);
	    	$('#entryType').trigger('change');
	    }
	});
}


$(function () {
	var sectionId = $("input[name='sectionId']").val();

	selectedParent = $('#parentId').data('elementSelect').getElements().first().data('id');
    $.refreshEntryTypes(selectedParent, sectionId);

	$('#parentId').data('elementSelect').on('selectElements', function(e) {
    	selectedParent = e.elements[0].id ?? 0;
    	$.refreshEntryTypes(selectedParent, sectionId);
	});

	$('#parentId').data('elementSelect').on('removeElements', function(e) {
    	selectedParent = 0;
    	$.refreshEntryTypes(selectedParent, sectionId);
	});

});	


