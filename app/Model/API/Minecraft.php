<?php


namespace App\Model\API;

/**
 * Class Minecraft
 * @package App\Model\API
 */
class Minecraft
{
    const API = "https://api.mojang.com/";
    const SKIN_API = "https://visage.surgeplay.com/full/512/";


    /**
     * @param $uuid
     * @return bool|string|string[]
     */
    public static function minifyUUID($uuid) {
        if (is_string($uuid)) {
            $minified = str_replace('-', '', $uuid);
            if (strlen($minified) === 32) {
                return $minified;
            }
        }
        return false;

    }

    /**
     * @param $user
     * @return bool
     */
    public static function isValidUsername($user): bool {
        return is_string($user) and strlen($user) >= 2 and strlen($user) <= 16 and ctype_alnum(str_replace('_', '', $user));
    }

    /**
     * @param $uuid
     * @return bool|string
     */
    public static function formatUUID($uuid) {
        return is_string($uuid) ? substr($uuid, 0, 8) . '-' .
            substr($uuid, 8, 4) . '-' . substr($uuid, 12, 4) . '-' .
            substr($uuid, 16, 4) . '-' . substr($uuid, 20, 12) : false;
    }

    /**
     * @param $username
     * @return bool|mixed
     */
    public static function getProfile($username) {
        if (self::isValidUsername($username)) {
            $json = file_get_contents(Minecraft::API . 'users/profiles/minecraft/' . $username);
            if (!empty($json)) {
                $data = json_decode($json, true);
                if (is_array($data) and !empty($data)) {
                    return $data;
                }
            }
        }

        return false;
    }

    /**
     * @param $username
     * @param mixed $profile
     * @return bool|mixed
     */
    public static function getUUID($username, $profile = null) {
        $profile = is_null($profile) ? static::getProfile($username) : $profile;
        if (is_array($profile) and isset($profile['id'])) {
            return $profile['id'];
        }

        return false;
    }

    /**
     * @param $uuid
     * @return bool|mixed
     */
    public static function getUsername($uuid) {
        $uuid = self::minifyUUID($uuid);
        if (is_string($uuid)) {
            $json = file_get_contents(Minecraft::API . 'user/profiles/' . $uuid . '/names');
            if (!empty($json)) {
                $data = json_decode($json, true);
                if (!empty($data) and is_array($data)) {
                    $last = array_pop($data);
                    if (is_array($last) and isset($last['name'])) {
                        return $last['name'];
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param $uuid
     * @return string
     */
    public static function getSkinURL($uuid) {
        return Minecraft::SKIN_API . $uuid;
    }
}