<?php
/**
 * Craft Rewards plugin for Craft CMS 3.x
 *
 * Craft Commerce loyalty plugin
 *
 * @link      ron.agapito@icloud.com
 * @copyright Copyright (c) 2020 ron.agapito
 */

namespace ron\craftrewards\services;

use craft\commerce\web\assets\commercecp\CommerceCpAsset;
use craft\db\Query;
use ron\craftrewards\CraftRewards;

use Craft;
use craft\base\Component;

/**
 * @author    ron.agapito
 * @package   CraftRewards
 * @since     1
 */
class CraftRewardsService extends Component
{
    // Public Methods
    // =========================================================================

    /*
     * @return mixed
     */

    public function addEditUserRewardsTab( array &$context)
    {
        $context['tabs']['rewards'] = [
            'label' => "Rewards",
            'url' => '#rewards'
        ];
    }

    public function addEditUserRewardsTabContent(array $context): string
    {
        $id = $context['user']->id;

        if (!$id) {
            return '';
        }

      #  Craft::$app->getView()->registerAssetBundle(CommerceCpAsset::class);
        return Craft::$app->getView()->renderTemplate('craft-rewards/usertab', [
            'user' => $this->getAllPoints( $id)[0],
        ]);
    }
    public function getAllPoints( $uid = null)
    {
 $where = [];
 if($uid)
     $where = ["a.id" => $uid];

        $users = (new Query())
            ->select(['a.id', 'b.points', 'b.points_redeemed', 'b.points_expired', 'a.firstName', 'a.lastName', 'a.email'])
            ->from("{{%users}} a")->leftJoin("{{%craftrewards_points}} b", "a.id = b.uid")->where($where)
            ->all();

        return $users;

    }

    public function getUserPoints(int $id, $allFields = false)
    {


        $points = 0;

        $p = (new Query())
            ->select(['*'])
            ->from("{{%craftrewards_points}}")->where(["uid" => $id])
            ->one();

        if ($p && $p['points'] > 0)
            $points = $p['points'];
        $isExpired = $p && (strtotime($p["dateLastPurchased"]) < strtotime("last month")) && $points > 0;

        if ($isExpired) {
            $this->setExpiredPoints($id, $points);
            $points = 0;
        }

        if ($allFields)
            return $p;

        return $points;

    }

    public function computePoints($amount)
    {

        return floor($amount / CraftRewards::$amountPerPoint);
    }



    public function setExpiredPoints(int $id, int $points)
    {

        Craft::$app->getDb()->createCommand()->update("{{%craftrewards_points}}", ['points' => 0, 'points_expired' => $points], ['uid' => $id])->execute();


    }





    public function redeemPoints(int $id)
    {

        $points = 0;
        $user = $this->getUserPoints($id, true);

        $hasPoints = isset($user['uid']);
        if (!$user)
            return;

        $points = $user['points'] + $user['points_redeemed'];


        if ($hasPoints)
            Craft::$app->getDb()->createCommand()->update("{{%craftrewards_points}}", ['points' => 0, 'points_redeemed' => $points, "dateLastPurchased" => date("Y-m-d H:i:s")], ['uid' => $id])->execute();


    }

    public function addUserPoints(int $id, int $points)
    {


        $user = (new Query())
            ->select(['uid', 'points'])
            ->from("{{%craftrewards_points}}")->where(['uid' => $id])
            ->one();
        $hasPoints = isset($user['uid']);
        if ($user && $user['points'])
            $points += $user['points'];
        $points = $points < 0 ?: $points;

        if ($hasPoints)
            Craft::$app->getDb()->createCommand()->update("{{%craftrewards_points}}", ['points' => $points], ['uid' => $id])->execute();
        else
            Craft::$app->getDb()->createCommand()->insert("{{%craftrewards_points}}", ["dateLastPurchased" => date("Y-m-d H:i:s"), "uid" => (int)$id, "points" => (int)$points])->execute();


    }



}


