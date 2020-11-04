<?php


namespace App\Model\Admin\Roles;

/**
 * A static class for manipulating with roles permissions.
 *
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

    /**
     * Returning a associative array with constant permissions nodes and user-friendly description of this permission (will be used for select/radios inputs)
     * @return array
     */
    public static function getSelectBox() {
        return [
            self::ADMIN_ARTICLES => "Správa článků",
            self::ADMIN_PAGES => "Správa stránek",
            self::ADMIN_CATEGORIES => "Správa kategorií",
            self::ADMIN_USERS => "Správa uživatelů/administrátorů",

            self::ADMIN_MC_CHATLOG => "Minecraft > Chatlog",
            self::ADMIN_MC_BANLIST => "Minecraft > Banlist",
            self::ADMIN_MC_IPBANLIST => "Minecraft > IP banlist",

            self::ADMIN_MC_GAMES => "Minecraft > Games",
            self::ADMIN_MC_SENIOR => "Minecraft > Senior",
            self::ADMIN_MC_CLASSIC => "Minecraft > Classic",

            self::ADMIN_GLOBAL_SETTINGS => "Nastavení webu",
            self::ADMIN_UPLOAD => "Upload obrázků",
        ];
    }

    /**
     * Returning a array with all permissions nodes, will be used Fulladmin group permissions
     * @return array
     */
    public static function getAllPermissions() {
        return array_keys(self::getSelectBox());
    }
}