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
 (function($){
    Craft.LevelEntryTypesIndex = Garnish.Base.extend({
        pixelsMap: {},
        structureMaxLevels: 20,

        $notifications: null,

        // Initialize
        init: function() {
            var self = this;

            // load up the pixels map
            for (i = 1; i < self.structureMaxLevels + 1; i++) {
                self.pixelsMap[(i * 44) - 16 + "px"] = i;
            }

            self.$notifications = $('#notifications .notification');

            // Hi-jack the `onDragStop` method in Craft.StructureTableSorter
            var structureTableSorter = Craft.StructureTableSorter.prototype;
            var onDragStop = structureTableSorter.onDragStop;
            structureTableSorter.onDragStop = function () {
                onDragStop.apply(this, arguments);
                var tableSorter = this
                self.validateEntryTypeLevels();
            };

            // Hi-jack the `onUpdateElements` method in Craft.BaseElementIndex
            var baseElementIndex = Craft.BaseElementIndex.prototype;
            var onUpdateElements = baseElementIndex.onUpdateElements;
            baseElementIndex.onUpdateElements = function () {
                onUpdateElements.apply(this, arguments);
                var elementIndex = this;
                self.validateEntryTypeLevels();
            }

            // Hi-jack the `onAppendElements` method in Craft.BaseElementIndexView
            var baseElementIndexView = Craft.BaseElementIndexView.prototype;
            var onAppendElements = baseElementIndexView.onAppendElements;
            baseElementIndexView.onAppendElements = function () {
                onAppendElements.apply(this, arguments);
                var elementIndexView = this;
                self.validateEntryTypeLevels();
            }
        },

        // Validates the entry types and levels of all entries on screen
        validateEntryTypeLevels: function() {
            var self = this;

            var structureId = $('[data-structure-id]').data('structure-id');
            var hasErrors = false;

            // Loop through entries in the table
            $('table[data-structure-id] td[data-attr="type"]').each(function () {
                padding = $(this).siblings('[data-title]').css('padding-left');
                level = self.pixelsMap[padding];
                entryTypeName = $(this).text();

                allowedLevels = LevelEntryTypesData.map[structureId][entryTypeName];
                limitedLevels = LevelEntryTypesData.limitedLevels[structureId];

                if (allowedLevels.includes(level) || !limitedLevels.includes(level)) {
                    $(this).parent().removeClass("entry-position-error");
                } else {
                    hasErrors = true;
                    $(this).parent().addClass("entry-position-error");
                }
            });

            // If there are errors, kill any existing notifications and display an error.
            if (hasErrors) {
                self.$notifications.fadeOut().remove();
                Craft.cp.displayError('One or more entries has a selected parent entry that does not allow child entries of this type. Select a new parent or change entry types.');
            }
        }
    });
})(jQuery);