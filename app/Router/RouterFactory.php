<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;


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

            ->addRoute('/admin/minecraft/luckperms/[<page>]', 'Minecraft:luckPerms')
            ->addRoute('/admin/minecraft/luckperms/zobrazit/<player>', 'Minecraft:filterLuckPerms')

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

            // TODO: Check why IPS & players parameter is null, after ipBans completed
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

            ->addRoute('/admin/hlasovani', 'Vote:list')
            ->addRoute('/admin/hlasovani/vytvorit', 'Vote:create')
            ->addRoute('/admin/hlasovani/edit/<id>', 'Vote:edit')
            ->addRoute('/admin/hlasovani/odstranit/<id>', 'Vote:delete')

            ->addRoute('/admin/social', 'Social:list')
            ->addRoute('/admin/social/vytvorit', 'Social:create')
            ->addRoute('/admin/social/edit/<id>', 'Social:edit')
            ->addRoute('/admin/social/odstranit/<id>', 'Social:delete')

            ->addRoute('/admin/upload', "Main:upload")
            ->addRoute('/admin/upload/odstranit/<file>', 'Main:removeUpload')

            ->addRoute('/admin/konfigurace', 'Configuration:overview')
            ->addRoute('/admin/konfigurace/aktualizovat', 'Configuration:update')

            ->addRoute('/admin/nastaveni-webu', 'Main:settings');

        $router->withModule('Panel')
            ->addRoute('/panel', 'Main:home')
            ->addRoute('/panel/zmena-hesla', 'Main:changePass')
            ->addRoute('/panel/login', 'Login:main')
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

        $router->withModule('Front')
            ->addRoute('/', 'Page:home')
            ->addRoute('/login', 'Login:main')
            ->addRoute('/admin/odhlasit-se', 'Login:logout')

            ->addRoute('/clanek[/<articleUrl=1>]', 'Page:article')
            ->addRoute('/archiv[/<page=1>]', 'Page:archiv') // list článků
            ->addRoute('/<page>', 'Page:page');

        $router->addRoute('/cli/savingplaytime/<authentication>', 'Cron:savingPlaytime');

		return $router;
	}
}
