<?php

namespace ConfluxGroup\LevelEntryTypes\controllers;

use ConfluxGroup\LevelEntryTypes\LevelEntryTypes;

use Craft;
use craft\web\Controller;

use craft\elements\Entry;

class EntryTypesController extends Controller
{

    // Protected Properties
    // =========================================================================

    protected $allowAnonymous = [];

    public function actionIndex()
    {
        $parentId = Craft::$app->request->getQueryParam('parentId') ?? 0;
        $sectionId = Craft::$app->request->getQueryParam('sectionId');

        if(!$sectionId)
        {
            return;
        }

        $parentEntry = Entry::find()->id($parentId)->one() ?? 0;

        $level = 0;

        if($parentEntry)
        {
            $level = $parentEntry->level;
        }



        $entryTypes = LevelEntryTypes::$plugin->levelEntryTypesService->get($sectionId, $level);

        $result = json_encode([
            'parentId' => $parentId, 
            'sectionId' => $sectionId, 
            'parentLevel' => $level,
            'disabledEntryTypes' => $entryTypes

        ]);

        return $result;
    }


}
