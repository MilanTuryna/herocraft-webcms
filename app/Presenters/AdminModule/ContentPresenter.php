<?php


namespace App\Presenters\AdminModule;


use App\Model\Admin\Roles\Permissions;
use App\Model\Security\Auth\Authenticator;
use App\Presenters\AdminBasePresenter;
use Nette\SmartObject;

/**
 * Class ContentPresenter
 * @package App\Presenters\AdminModule
 */
class ContentPresenter extends AdminBasePresenter
{
    use SmartObject;

    /**
     * ContentPresenter constructor.
     * @param Authenticator $authenticator
     * @param string $permissionNode
     */
    public function __construct(Authenticator $authenticator, string $permissionNode = Permissions::ADMIN_CONTENT_MANAGER)
    {
        parent::__construct($authenticator, $permissionNode);
    }

    public function renderOverview() {/* TODO */}
}