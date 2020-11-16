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
    const ADMIN_FULL = "*";

    const ADMIN_ARTICLES = "admin.articles";
    const ADMIN_PAGES = "admin.pages";
    const ADMIN_CATEGORIES = "admin.categories";
    //const ADMIN_USERS = "admin.users"; // permission to manage users is same as ADMIN_FULL;

    const ADMIN_MC_HELPERS = "admin.minecraft_helpers";
    const ADMIN_MC_CHATLOG = "admin.minecraft_chatlog";
    const ADMIN_MC_BANLIST = "admin.minecraft_banlist";
    const ADMIN_MC_IPBANLIST = "admin.minecraft_ipbanlist";
    const ADMIN_MC_ONLINEPLAYERS = "admin.minecraft_onlineplayers";

    const ADMIN_MC_GAMES = "admin.minecraft_games";
    const ADMIN_MC_SENIOR = "admin.minecraft_senior";
    const ADMIN_MC_CLASSIC = "admin.minecraft_classic";

    const ADMIN_GLOBAL_SETTINGS = "admin.global_settings";
    const ADMIN_UPLOAD = "admin.upload";

    /**
     * Constant used for checking automatic checking in AdminBasePresenter (if children presenter are without permission)
     */
    const SPECIAL_WITHOUT_PERMISSION = "admin.special_without_permission";

    /**
     * Returning a associative array with constant permissions nodes and user-friendly description of this permission (will be used for select/radios inputs)
     * @return array
     */
    public static function getSelectBox() {
        return [
            self::ADMIN_FULL => "Správce - plná práva: *",

            self::ADMIN_ARTICLES => "Správa článků",
            self::ADMIN_PAGES => "Správa stránek",
            self::ADMIN_CATEGORIES => "Správa kategorií",
            //self::ADMIN_USERS => "Správa uživatelů/administrátorů",

            self::ADMIN_MC_HELPERS => "Minecraft > Helpeři",
            self::ADMIN_MC_CHATLOG => "Minecraft > Chatlog",
            self::ADMIN_MC_BANLIST => "Minecraft > Banlist",
            self::ADMIN_MC_IPBANLIST => "Minecraft > IP banlist",
            self::ADMIN_MC_ONLINEPLAYERS => "Minecraft > Online hráči",

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
    public static function getAllPermissions(): array {
        return array_keys(self::getSelectBox());
    }

    /**
     * Checking if permission node is includes in permissions
     * @param array $permArray
     * @param string $node
     * @return bool
     */
    public static function checkPermission(array $permArray, string $node): bool {
        return in_array($node, $permArray) || in_array(self::ADMIN_FULL, $permArray) || $node === Permissions::SPECIAL_WITHOUT_PERMISSION;
    }

    /**
     * Method used to generate message if user don't have permission.
     * @param string $node
     * @return string
     */
    public static function getNoPermMessage(string $node): string {
        return 'K této sekci nemáš přístup.';
    }

    /**
     * Method used to parse array (in string, from MySQL) to classic array.
     * @param string $unparsedList
     * @return array
     */
    public static function listToArray(string $unparsedList): array {
        return array_filter(explode(",", preg_replace('/\s+/', '', $unparsedList)));
    }

    /**
     * @param $parsedList
     * @return string
     */
    public static function arrayToUnparsedList($parsedList): string {
        return implode(",", $parsedList);
    }
}