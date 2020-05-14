/**
 * Level Entry Types plugin for Craft CMS
 *
 * EntryIndexScreen JS
 *
 * @author    Conflux Group, Inc.
 * @copyright Copyright (c) 2020 Conflux Group, Inc.
 * @link      https://confluxgroup.com
 * @package   LevelEntryTypes
 * @since     1
 */

 $.validateEntryTypeLevels = function () {
    var structureId = $('[data-structure-id]').data('structure-id');
    var structureMaxLevels = $('[data-max-levels]').data('max-levels');

    var pixelsMap = {};

    for (i = 1; i < structureMaxLevels + 1; i++) {
        pixelsMap[(i * 44) - 16 + "px"] = i;
    }

    var hasErrors = false;

    $('table[data-structure-id] td[data-attr="type"]').each(function () {
        padding = $(this).siblings('[data-title]').css('padding-left');
        level = pixelsMap[padding];
        entryTypeName = $(this).text();

        allowedLevels = Craft.levelEntryTypes.map[structureId][entryTypeName];

        if (allowedLevels.includes(level)) {
            $(this).parent().removeClass("entry-position-error");
        } else {
            hasErrors = true;
            $(this).parent().addClass("entry-position-error");
        }
    });

    if (hasErrors) {
        Craft.cp.displayError('One or more entries has a selected parent entry that does not allow child entries of this type. Select a new parent or change entry types.');
    }
 }


$(document).ready(function () {

    // Hi-jack the `onSortChange` method in Craft.StructureTableSorter
    var structureTableSorter = Craft.StructureTableSorter.prototype;
    var onDragStop = structureTableSorter.onDragStop;
    structureTableSorter.onDragStop = function () {

        onDragStop.apply(this, arguments);
        var tableSorter = this

        $.validateEntryTypeLevels();
    };

    var baseElementIndex = Craft.BaseElementIndex.prototype;
    var onUpdateElements = baseElementIndex.onUpdateElements;
    baseElementIndex.onUpdateElements = function () {

        onUpdateElements.apply(this, arguments);
        var elementIndex = this;

        $.validateEntryTypeLevels();
    }

    var baseElementIndexView = Craft.BaseElementIndexView.prototype;
    var onAppendElements = baseElementIndexView.onAppendElements;
    baseElementIndexView.onAppendElements = function () {

        onAppendElements.apply(this, arguments);
        var elementIndexView = this;

        $.validateEntryTypeLevels();
    }
});