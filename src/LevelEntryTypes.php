<?php
/**
 * Level Entry Types plugin for Craft CMS 3.x
 *
 * Allows developers to limit the entry types available at each level of a structure section.
 *
 * @link      https://confluxgroup.com
 * @copyright Copyright (c) 2020 Conflux Group, In.c
 */

namespace ConfluxGroup\LevelEntryTypes;


use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;

use yii\base\Event;

use craft\web\View;
use craft\events\RegisterTemplateRootsEvent;

use ConfluxGroup\LevelEntryTypes\assetbundles\editentryscreen\EditEntryScreenAsset;
use ConfluxGroup\LevelEntryTypes\assetbundles\entryindexscreen\EntryIndexScreenAsset;

use ConfluxGroup\LevelEntryTypes\services\LevelEntryTypesService;
use ConfluxGroup\LevelEntryTypes\models\Settings;
/**
 *
 * @author    Conflux Group, In.c
 * @package   LevelEntryTypes
 * @since     0.1.0
 *
 */
class LevelEntryTypes extends Plugin
{
    // Static Properties
    // =========================================================================
    public static $plugin;

    // Public Properties
    // =========================================================================
    public $schemaVersion = '0.1.0';
    public $hasCpSettings = false;
    public $hasCpSection = false;

    // Public Methods
    // =========================================================================
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Craft::setAlias('@plugin', $this->getBasePath());

        $this->setComponents([
            'levelEntryTypesService' => LevelEntryTypesService::class

        ]);

        // Only fire event on the admin/entries section of the CP
        if(Craft::$app->getRequest()->isCpRequest && Craft::$app->getRequest()->getSegment(1) == 'entries')
        {
            // Edit Entry Screen
            if(Craft::$app->getRequest()->getSegment(3) != '')
            {
                Event::on(
                    View::class,
                    View::EVENT_BEFORE_RENDER_TEMPLATE,
                    function() {
                        Craft::$app->getView()->registerAssetBundle(EditEntryScreenAsset::class);
                    }
                );

            }
            // Entry Index
            else
            {
               Event::on(
                    View::class,
                    View::EVENT_BEFORE_RENDER_TEMPLATE,
                    function() {
                        Craft::$app->getView()->registerAssetBundle(EntryIndexScreenAsset::class);
                    
                        // Inject SectionId -> Entry Type Name -> allowed levels mapping
                        Craft::$app->getView()->registerJs('Craft.levelEntryTypes = ' . $this->levelEntryTypesService->getSectionEntryTypeLevelMap(), View::POS_END);
                    }
                ); 
            }                
        }

        Craft::info(
            Craft::t(
                'level-entry-types',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    protected function createSettingsModel()
    {
        return new Settings();
    }

}