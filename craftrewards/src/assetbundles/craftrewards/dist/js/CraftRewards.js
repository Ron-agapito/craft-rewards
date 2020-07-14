/**
 * Craft Rewards plugin for Craft CMS
 *
 * Craft Rewards JS
 *
 * @author    ron.agapito
 * @copyright Copyright (c) 2020 ron.agapito
 * @link      ron.agapito@icloud.com
 * @package   CraftRewards
 * @since     1
 */

var $modal = new Garnish.Modal(); //create a new modal
$modal.setContainer("#pointsModal");
$(".btnPoints").click(function()
{

$modal.show();

    var p = $(this).data("points");
    var id = $(this).data("id");
    var action = $(this).data("action");
    $(".pointsOnModal").html(p);
    $("#type").val( action);
    $("#customerId").val(id);
    $("#points").val(0)
});

$(".close").click(function()
{

   $modal.hide()
});