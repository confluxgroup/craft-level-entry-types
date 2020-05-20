<?php
/**
 * Level Entry Types plugin for Craft CMS 3.x
 *
 * @link      https://confluxgroup.com
 * @copyright Copyright (c) 2020 Conflux Group, Inc.
 */

namespace ConfluxGroup\LevelEntryTypes\assetbundles\entryindexscreen;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class EntryIndexScreenAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * Initializes the bundle.
     */
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = "@plugin/assetbundles/entryindexscreen/dist";

        // define the dependencies
        $this->depends = [
            CpAsset::class,
        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            'js/EntryIndexScreen.js',
        ];

        $this->css = [
            'css/EntryIndexScreen.css',
        ];

        parent::init();
    }
}