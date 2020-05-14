<?php
/**
 * Level Entry Types plugin for Craft CMS 3.x
 *
 * @link      https://confluxgroup.com
 * @copyright Copyright (c) 2020 Conflux Group, Inc.
 */

namespace ConfluxGroup\LevelEntryTypes\assetbundles\editentryscreen;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * EditEntryScreenAsset AssetBundle
 *
 * @author    Conflux Group, Inc.
 * @package   LevelEntryTypes
 * @since     1
 */
class EditEntryScreenAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * Initializes the bundle.
     */
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = "@plugin/assetbundles/editentryscreen/dist";

        // define the dependencies
        $this->depends = [
            CpAsset::class,
        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            'js/EditEntryScreen.js',
        ];

        $this->css = [
            //'css/Jem.css',
        ];

        parent::init();
    }
}
