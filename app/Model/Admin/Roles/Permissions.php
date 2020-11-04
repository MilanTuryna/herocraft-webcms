<?php


namespace App\Model\Admin\Roles;

/**
 * Class Permissions
 * @package App\Model\Admin\Roles
 */
class Permissions
{
    const ADMIN_ARTICLES = "admin.articles";
    const ADMIN_PAGES = "admin.pages";
    const ADMIN_CATEGORIES = "admin.categories";
    const ADMIN_USERS = "admin.users";

    const ADMIN_MC_CHATLOG = "admin.minecraft_chatlog";
    const ADMIN_MC_BANLIST = "admin.minecraft_banlist";
    const ADMIN_MC_IPBANLIST = "admin.minecraft_ipbanlist";

    const ADMIN_MC_GAMES = "admin.minecraft_games";
    const ADMIN_MC_SENIOR = "admin.minecraft_senior";
    const ADMIN_MC_CLASSIC = "admin.minecraft_classic";

    const ADMIN_GLOBAL_SETTINGS = "admin.global_settings";
    const ADMIN_UPLOAD = "admin.upload";
}