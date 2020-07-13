<?php
/**
 * Craft Rewards plugin for Craft CMS 3.x
 *
 * Craft Commerce loyalty plugin
 *
 * @link      ron.agapito@icloud.com
 * @copyright Copyright (c) 2020 ron.agapito
 */

namespace ron\craftrewards\assetbundles\craftrewards;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    ron.agapito
 * @package   CraftRewards
 * @since     1
 */
class CraftRewardsAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@ron/craftrewards/assetbundles/craftrewards/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/CraftRewards.js',
        ];

        $this->css = [
            'css/CraftRewards.css',
        ];

        parent::init();
    }
}
