# Craft Rewards plugin for Craft CMS 3.x

Craft Commerce loyalty plugin


## Requirements

This plugin requires
 
 * Craft CMS 3.0 or later.
 * Craft Commerce 3


## Installation

To install the plugin manually, follow these instructions.

1.) Open your composer.json and add the following to require and repositories:

           "require":{
                       "ron/craft-rewards": "^1.0",
                      }
                      
           "repositories": [
              {
                "type": "path",
                "url": PATH TO PLUGIN
              }
            ]
           



 2.) Then tell Composer to load the plugin: 

        composer update



 3.) In the Control Panel, go to Settings → Plugins and click the “Install” button for Craft Rewards.

This plugin will soon be available from Craft Plugin  Store
## Craft Rewards Overview

This plugin adds a reward / loyalty feature for Craft Commerce shops.
Users will earn points for every transactions made and automatically consume points as discount.

Admins can also view and update  user points from the dashboard.

This plugin will soon have additional settings such as :

* Formula for earning points
* Formula for spending points
* Set point expiration

## Configuring Craft Rewards

This plugin currently have fixed value per point. $1 = 1 point.
Users will earn 1 point for every $100 spent. Available point will also expire if not consumed after a month.

## Craft Rewards Roadmap

-

Brought to you by [ron.agapito](ron.agapito@icloud.com)
