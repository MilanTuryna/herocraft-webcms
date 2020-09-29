<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

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
            ->addRoute('/admin/minecraft/chat[/<page>]', 'Minecraft:chat')
            ->addRoute('/admin/minecraft/eventy', 'Minecraft:eventList')
            ->addRoute('/admin/minecraft/eventy/zaznam/<recordId>', 'Minecraft:editEventRecord')
            ->addRoute('/admin/minecraft/eventy/<eventId>', 'Minecraft:event')

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

            ->addRoute('/admin/nastaveni-webu', 'Main:settings');

        $router->withModule('Panel')
            ->addRoute('/panel', 'Main:home')
            ->addRoute('/panel/zmena-hesla', 'Main:changePass')
            ->addRoute('/panel/login', 'Login:main')
            ->addRoute('/panel/odhlasit-se', 'Login:logout')

            ->addRoute('/panel/tickety/pridat', 'Ticket:add')
            ->addRoute('/panel/tickety/zobrazit/<id>', 'Ticket:view')
            ->addRoute('/panel/tickety[/<page>]', 'Ticket:list')

            ->addRoute('/panel/pratele', 'Friends:list')
            ->addRoute('/panel/pratele/<friend>', 'Friends:info');

        $router->withModule('HelpDesk')
            ->addRoute('/help/login', 'Login:main')
            ->addRoute('/help/tickety/<id>', 'Main:ticket')
            ->addRoute('/help/odhlasit-se', 'Main:logout')
            ->addRoute('/help[/<page>]', 'Main:home');
        
        $router->withModule('Stats')
            ->addRoute('/statistiky/', 'Main:app')
            ->addRoute('/statistiky/api/<name>', 'API:view');

        $router->withModule('Front')
            ->addRoute('/', 'Page:home')
            ->addRoute('/login', 'Login:main')
            ->addRoute('/admin/odhlasit-se', 'Login:logout')

            ->addRoute('/clanek[/<articleUrl=1>]', 'Page:article')
            ->addRoute('/archiv[/<page=1>]', 'Page:archiv') // list článků
            ->addRoute('/<page>', 'Page:page');

		return $router;
	}
}
