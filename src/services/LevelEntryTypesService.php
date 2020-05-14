<?php
/**
 * Level Entry Types plugin for Craft CMS 3.x
 *
 * @link      https://confluxgroup.com
 * @copyright Copyright (c) 2020 Conflux Group, Inc.
 */

namespace ConfluxGroup\LevelEntryTypes\services;

use ConfluxGroup\LevelEntryTypes\LevelEntryTypes;

use Craft;
use craft\base\Component;
use craft\services\Sections;

/**
 * LevelEntryTypesService Service
 *
 * @author    Conflux Group, Inc.
 * @package   LevelEntryTypes
 * @since     1
 */
class LevelEntryTypesService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * @return mixed
     */
    public function getDisabledEntryTypes($sectionId, $parentLevel = 0)
    {

        $settings = LevelEntryTypes::$plugin->getSettings();

        $level = $parentLevel + 1;
        
        // Get all entry types for this section
        $entryTypes = Craft::$app->sections->getEntryTypesBySectionId($sectionId);

        $entryTypesArray = [];

        // Reorganize the entry types into a nicely formatted array
        foreach($entryTypes as $type)
        {
            $entryTypesArray[$type->handle] = (int) $type->id; 
        }

        // get section by id
        $section = Craft::$app->sections->getSectionById($sectionId);

        // get section handle so we can find it in the settings
        $sectionHandle = $section->handle;

        // get settings for this section
        $sectionEntryTypes = isset($settings['structures'][$sectionHandle][(int) $level]) ? $settings['structures'][$sectionHandle][(int) $level] : array_keys($entryTypesArray);

        foreach($sectionEntryTypes as $sectionEntryType)
        {
            unset($entryTypesArray[$sectionEntryType]);
             
        } 
        
        return array_values($entryTypesArray);
    }

    public function getSectionEntryTypeLevelMap()
    {
        $settings = LevelEntryTypes::$plugin->getSettings();

        // Get all entry types for this section
        $entryTypes = Craft::$app->sections->getAllEntryTypes();
        $sections = Craft::$app->sections->getAllSections();
        
        // Output array
        $output = [];
        $output['map'] = [];
        $output['limitedLevels'] = [];

        // Some map arrays
        $sectionHandleMap = [];
        $entryTypeHandleMap = [];
        $sectionEntryTypes = [];

        // Process the sections into the output array and section handle map
        foreach($sections as $section)
        {
            if($section->type == 'structure')
            {
                $output['map'][(string) $section->id] = [];
                $output['limitedLevels'][(string) $section->id] = [];
                $sectionHandleMap[$section->handle] = $section->id;
                $sectionEntryTypes[$section->id] = [];

            }
        }

        // Process the entry types into the output array and the entry handle map
        foreach($entryTypes as $entryType)
        {            
            $output['map'][$entryType->sectionId][$entryType->name] = [];
            $entryTypeHandleMap[$entryType->handle] = $entryType->name;
            
            $sectionEntryTypes[$entryType->sectionId][] = $entryType->name;
        }

        // Loop through the structures in our settings
        foreach($settings['structures'] as $structureHandle => $structureSettings)
        {
            $structureLevels = array_keys($structureSettings);

            //$limitedLevels[$sectionHandleMap[$structureHandle]] = $structureLevels;
            $output['limitedLevels'][$sectionHandleMap[$structureHandle]] = $structureLevels;

            // Loop through the levels in each structure
            foreach($structureSettings as $structureLevel => $structureLevelEntryTypes)
            {
                // Loop through the entry types in each level
                foreach($structureLevelEntryTypes as $structureLevelEntryTypeHandle)
                {
                    // Add to the output array
                    // this convuluted mess adds each level to the output
                    // add new level to output[section ID][entry type name]
                    $output['map'][ $sectionHandleMap[$structureHandle] ][ $entryTypeHandleMap[$structureLevelEntryTypeHandle]][] = $structureLevel;
                }
            }
            
            // Go through the output by entry type
            foreach($output['map'][$sectionHandleMap[$structureHandle]] as $outputSectionEntryTypeKey => $outputSectionEntryTypes)
            {
                // if no levels are defined for a given entry type
                // then default to all levels
                if(empty($outputSectionEntryTypes))
                {
                    $output['map'][$sectionHandleMap[$structureHandle]][$outputSectionEntryTypeKey] = $structureLevels;
                }
            }

        }

        return json_encode($output);
    }
}