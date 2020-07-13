<?php
/**
 * Craft Rewards plugin for Craft CMS 3.x
 *
 * Craft Commerce loyalty plugin
 *
 * @link      ron.agapito@icloud.com
 * @copyright Copyright (c) 2020 ron.agapito
 */

namespace ron\craftrewards\assetbundles\indexcpsection;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    ron.agapito
 * @package   CraftRewards
 * @since     1
 */
class IndexCPSectionAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@ron/craftrewards/assetbundles/indexcpsection/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/Index.js',
        ];

        $this->css = [
            'css/Index.css',
        ];

        parent::init();
    }
}
