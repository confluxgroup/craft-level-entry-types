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

        // Some map arrays
        $sectionHandleMap = [];
        $entryTypeHandleMap = [];

        // Process the sections into the output array and section handle map
        foreach($sections as $section)
        {
            if($section->type == 'structure')
            {
                $output[(string) $section->id] = [];
                $sectionHandleMap[$section->handle] = $section->id;
            }
        }

        // Process the entry types into the output array and the entry handle map
        foreach($entryTypes as $entryType)
        {            
            $output[$entryType->sectionId][$entryType->name] = [];
            $entryTypeHandleMap[$entryType->handle] = $entryType->name;
        }

        // Loop through the structures in our settings
        foreach($settings['structures'] as $structureHandle => $structureSettings)
        {
            // Loop through the levels in each structure
            foreach($structureSettings as $structureLevel => $structureLevelEntryTypes)
            {
                // Loop through the entry types in each level
                foreach($structureLevelEntryTypes as $structureLevelEntryTypeHandle)
                {
                    // Add to the output array
                    // this convuluted mess adds each level to the output
                    // add new level to output[section ID][entry type name]
                    $output[ $sectionHandleMap[$structureHandle] ][ $entryTypeHandleMap[$structureLevelEntryTypeHandle]][] = $structureLevel;
                }
            }
        }

        //dd($output);

        return json_encode($output);


    }
}