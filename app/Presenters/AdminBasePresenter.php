<?php


namespace App\Presenters;


use App\Model\Admin\Roles\Permissions as Permissions;
use App\Model\Admin\Object\Administrator;
use App\Model\DI\GoogleAnalytics;
use App\Model\Security\Auth\Authenticator;

use Nette\Application\AbortException;

/**
 * Class AdminBasePresenter
 * @package App\Presenters
 */
abstract class AdminBasePresenter extends BasePresenter
{
    private Authenticator $authenticator;
    private string $permissionNode;

    protected Administrator $admin;

    /**
     * AdminBasePresenter constructor.
     * @param Authenticator $authenticator
     * @param string $permissionNode
     */
    public function __construct(Authenticator $authenticator, string $permissionNode = Permissions::SPECIAL_WITHOUT_PERMISSION)
    {
        parent::__construct(GoogleAnalytics::disabled()); // disable google analytics in administration section

        $this->authenticator = $authenticator;
        $this->permissionNode = $permissionNode;
    }

    /**
     * @throws AbortException
     */
    public function startup()
    {
        parent::startup();
        $user = $this->authenticator->getUser();
        if(!(bool)$user) {
            $this->flashMessage('Pro manipulaci s administrací, proveďte autorizaci.', 'danger');
            $this->redirect(':Front:Login:main');
        } else {
            $permissions = Permissions::listToArray($user->permissions);
            $permissionsSelectBox = Permissions::getSelectBox();
            if(Permissions::checkPermission($permissions, $this->permissionNode)) {
                $this->admin = new Administrator($user->name, $user->email, $user->id, $permissions);
                $this->template->admin = $this->admin;
                $this->template->permissionsSelectBox = $permissionsSelectBox;
                $this->template->isFullAdmin = Permissions::checkPermission($this->admin->getPermissions(), Permissions::ADMIN_FULL);
                $adminPermissions = $this->admin->getPermissions();
                $this->template->havePermission = [
                    "settings" => Permissions::checkPermission($adminPermissions, Permissions::ADMIN_GLOBAL_SETTINGS),
                    "articles" => Permissions::checkPermission($adminPermissions, Permissions::ADMIN_ARTICLES),
                    "pages" => Permissions::checkPermission($adminPermissions, Permissions::ADMIN_PAGES),
                    "categories" => Permissions::checkPermission($adminPermissions, Permissions::ADMIN_CATEGORIES),
                    "minecraft_chatlog" => Permissions::checkPermission($adminPermissions, Permissions::ADMIN_MC_CHATLOG),
                    "minecraft_banlist" => Permissions::checkPermission($adminPermissions, Permissions::ADMIN_MC_BANLIST),
                    "minecraft_ipbanlist" => Permissions::checkPermission($adminPermissions, Permissions::ADMIN_MC_IPBANLIST),
                    "minecraft_games" => Permissions::checkPermission($adminPermissions, Permissions::ADMIN_MC_GAMES),
                    "minecraft_senior" => Permissions::checkPermission($adminPermissions, Permissions::ADMIN_MC_SENIOR),
                    "minecraft_classic" => Permissions::checkPermission($adminPermissions, Permissions::ADMIN_MC_CLASSIC),
                    "minecraft_helpers" => Permissions::checkPermission($adminPermissions, Permissions::ADMIN_MC_HELPERS),
                    "minecraft_onlineplayers" => Permissions::checkPermission($adminPermissions, Permissions::ADMIN_MC_ONLINEPLAYERS),
                    "upload" => Permissions::checkPermission($adminPermissions, Permissions::ADMIN_UPLOAD),
                    "content" => Permissions::checkPermission($adminPermissions, Permissions::ADMIN_CONTENT_MANAGER),
                ];
            } else {
                $this->flashMessage(Permissions::getNoPermMessage($this->permissionNode) , 'danger');
                $this->redirect("Main:home");
            }
        }
    }
}