<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;

/**
 * Class RouterFactory
 * @package App\Router
 */
final class RouterFactory
{
	use Nette\StaticClass;

    /**
     * @return RouteList
     */
	public static function createRouter(): RouteList
	{
		$router = new RouteList;

        $router->withModule('Admin')
            ->addRoute('/admin', 'Main:home')

            ->addRoute('/admin/clanky', 'Article:list')
            ->addRoute('/admin/clanky/vytvorit', 'Article:create')
            ->addRoute('/admin/clanky/edit/<url>', 'Article:edit')
            ->addRoute('/admin/clanky/odstranit/<url>', 'Article:delete')

            ->addRoute('/admin/minecraft', 'Minecraft:overview')
            ->addRoute('/admin/minecraft/online-hraci', 'Minecraft:onlinePlayers')
            ->addRoute('/admin/minecraft/helpers', 'Minecraft:helpers')
            ->addRoute('/admin/minecraft/helpers/<helper>', 'Minecraft:helperView')

            ->addRoute('/admin/minecraft/luckperms/hrac/<player>', 'Minecraft:filterLuckPerms')
            ->addRoute('/admin/minecraft/luckperms/odstranit?uuid=<uuid>&perm=<permission>&returnPlayer=<returnPlayer>', 'Minecraft:deleteSpecificPermission')
            ->addRoute('/admin/minecraft/luckperms/[<page>]', 'Minecraft:luckPerms')

            ->addRoute('/admin/minecraft/chat/filter/?timeStart=<timeStart>&timeEnd=<timeEnd>&players[]=<players=null>',  'Minecraft:filterChat')
            ->addRoute('/admin/minecraft/chat[/<page>]', 'Minecraft:chat')

            ->addRoute('/admin/minecraft/games/eventy', 'MinecraftGames:eventList')
            ->addRoute('/admin/minecraft/games/eventy/zaznam/odstranit/<recordId>?returnEventID=<eventId=1>', 'MinecraftGames:deleteEventRecord')
            ->addRoute('/admin/minecraft/games/eventy/zaznam/<recordId>', 'MinecraftGames:editEventRecord')
            ->addRoute('/admin/minecraft/games/eventy/<eventId>', 'MinecraftGames:event')

            ->addRoute('/admin/minecraft/games/hideandseek/zaznam/<playerId>', 'MinecraftGames:editHASrecord')
            ->addRoute('/admin/minecraft/games/hideandseek[/<page>]', 'MinecraftGames:hideAndSeekStats')

            ->addRoute('/admin/minecraft/games/spleef/zaznam/<playerUUID>', 'MinecraftGames:editSpleefRecord')
            ->addRoute('/admin/minecraft/games/spleef[/<page>]', 'MinecraftGames:spleefStats')

            ->addRoute('/admin/minecraft/bany/filter/?timeStart=<timeStart>&timeEnd=<timeEnd>&players[]=<players=null>',  'Minecraft:filterBan')
            ->addRoute('/admin/minecraft/bany/zaznam/<nick>', 'Minecraft:editBan')
            ->addRoute('/admin/minecraft/bany[/<page>]', 'Minecraft:banList')

            ->addRoute('/admin/minecraft/ipbany/filter/?timeStart=<timeStart>&timeEnd=<timeEnd>&ips[]=<ips=null>',  'Minecraft:filterIpBan')
            ->addRoute('/admin/minecraft/ipbany/zaznam/<ip>', 'Minecraft:editIpBan')
            ->addRoute('/admin/minecraft/ipbany[/<page>]', 'Minecraft:ipBanList')

            ->addRoute('/admin/minecraft/senior/ekonomika/zaznam/<recordId>', "MinecraftSenior:editEconomyRecord")
            ->addRoute('/admin/minecraft/senior/ekonomika/[<page>]', 'MinecraftSenior:economy')

            ->addRoute('/admin/minecraft/classic/ekonomika/zaznam/<recordId>', "MinecraftClassic:editEconomyRecord")
            ->addRoute('/admin/minecraft/classic/ekonomika/[<page>]', 'MinecraftClassic:economy')

            ->addRoute('/admin/stranky', 'Page:list')
            ->addRoute('/admin/stranky/vytvorit', 'Page:create')
            ->addRoute('/admin/stranky/edit/<url>', 'Page:edit')
            ->addRoute('/admin/stranky/odstranit/<url>', 'Page:delete')

            ->addRoute('/admin/kategorie', 'Category:list')
            ->addRoute('/admin/kategorie/vytvorit', 'Category:create')
            ->addRoute('/admin/kategorie/edit/<id>', 'Category:edit')
            ->addRoute('/admin/kategorie/odstranit/<id>', 'Category:delete')

            ->addRoute('/admin/uzivatele', 'User:list')
            ->addRoute('/admin/uzivatele/vytvorit', 'User:create')
            ->addRoute('/admin/uzivatele/edit/<id>', 'User:edit')
            ->addRoute('/admin/uzivatele/odstranit/<id>', 'User:delete')

            ->addRoute('/admin/content', 'Content:overview')
            ->addRoute('/admin/content-manager/widget/vytvorit', 'Content:createWidget')
            ->addRoute('/admin/content-manager/widget/edit/<id>', 'Content:editWidget')
            ->addRoute('/admin/content-manager/widget/pdstranit/<id>?nazevWidgetu=<widgetName>', 'Content:deleteWidget')
            ->addRoute('/admin/content-manager/sekce/vytvorit', 'Content:createSection')
            ->addRoute('/admin/content-manager/sekce/edit/<id>', 'Content:editSection')
            ->addRoute('/admin/content-manager/sekce/odstranit/<id>?nazevSekce=<sectionName>', 'Content:deleteSection')
            ->addRoute('/admin/content-manager/predvolby-stylu/tlacitko/edit/<id>', 'Content:editButtonStyle')
            ->addRoute('/admin/content-manager/predvolby-stylu/tlacitko/odstranit/<id>?styleName=<buttonStyleName>', 'Content:deleteButtonStyle')
            ->addRoute('/admin/content-manager/predvolby-stylu/tlacitko', 'Content:buttonStylesList')

            ->addRoute('/admin/upload', "Main:upload")
            ->addRoute('/admin/upload/odstranit/<file>', 'Main:removeUpload')

            ->addRoute('/admin/konfigurace', 'Configuration:overview')
            ->addRoute('/admin/konfigurace/aktualizovat', 'Configuration:update')

            ->addRoute('/admin/nastaveni-webu', 'Main:settings');

        $router->withModule('Panel')
            ->addRoute('/panel', 'Main:home')
            ->addRoute('/panel/zmena-hesla', 'Main:changePass')
            ->addRoute('/panel/login', 'Login:main')
            ->addRoute('/panel/tickety/nahled',  'Login:ticketLogin')
            ->addRoute('/panel/odhlasit-se', 'Login:logout')

            ->addRoute('/panel/tickety/pridat', 'Ticket:add')
            ->addRoute('/panel/tickety/zobrazit/<id>', 'Ticket:view')
            ->addRoute('/panel/tickety[/<page>]', 'Ticket:list');

        $router->withModule('HelpDesk')
            ->addRoute('/help/login', 'Login:main')
            ->addRoute('/help/tickety/odstranit/<ticketId>', 'Main:deleteTicket')
            ->addRoute('/help/tickety/<id>', 'Main:ticket')
            ->addRoute('/help/odhlasit-se', 'Main:logout')
            ->addRoute('/help[/<page>]', 'Main:home');
        
        $router->withModule('Stats')
            ->addRoute('/statistiky/', 'Main:app')
            ->addRoute('/statistiky/eventy/<eventName>[/<page>]', 'Main:viewEvent')

            ->addRoute('/statistiky/api', 'API:serverView')
            ->addRoute('/statistiky/api/<name>', 'API:view');

        $router->withModule('WebLoader')
            ->addRoute('/web-loader/front/css', 'Front:css')
            ->addRoute('/web-loader/front/js', 'Front:js')

            ->addRoute('/web-loader/admin/css', 'Admin:css')
            ->addRoute('/web-loader/admin/js', 'Admin:js')

            ->addRoute('/web-loader/panel/css', 'Panel:css')
            ->addRoute('/web-loader/panel/js', 'Panel:js')

            ->addRoute('/web-loader/helpdesk/css', 'HelpDesk:css')
            ->addRoute('/web-loader/helpdesk/js', 'HelpDesk:js')

            ->addRoute('/web-loader/stats/css', 'Stats:css')
            ->addRoute('/web-loader/stats/js', 'Stats:js');

        $router->withModule('Front')
            ->addRoute('/login', 'Login:main')

            ->addRoute('/', 'Main:landingPage')
            ->addRoute('/archiv[/<pagination=1>]', 'Main:archive')
            ->addRoute('/<pageUrl>', 'Page:view')

            ->addRoute('/admin/odhlasit-se', 'Login:logout')

            ->addRoute('/clanek[/<articleUrl=1>]', 'Article:view')
            ->addRoute('/export-clanku/<articleUrl>?dl=<download>', 'Article:export');

        $router->withModule('Dynamic')
            ->addRoute('/dynamic/css/buttons', 'CSS:buttons');

        $router->addRoute('/cli/savingPlaytime/<authentication>', 'Cron:savingPlaytime');

		return $router;
	}
}
