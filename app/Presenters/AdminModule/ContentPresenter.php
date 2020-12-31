<?php


namespace App\Presenters\AdminModule;


use App\Forms\Admin\Content\Homepage\ChangeHeaderSectionForm;
use App\Front\Section\SectionManager;
use App\Model\Admin\Roles\Permissions;
use App\Model\Security\Auth\Authenticator;
use App\Model\SettingsRepository;
use App\Presenters\AdminBasePresenter;
use Nette\Application\UI\Form;
use Nette\SmartObject;

/**
 * Class ContentPresenter
 * @package App\Presenters\AdminModule
 */
class ContentPresenter extends AdminBasePresenter
{
    use SmartObject;

    private SettingsRepository $settingsRepository;
    private SectionManager $sectionManager;

    /**
     * ContentPresenter constructor.
     * @param Authenticator $authenticator
     * @param SettingsRepository $settingsRepository
     * @param SectionManager $sectionManager
     * @param string $permissionNode
     */
    public function __construct(Authenticator $authenticator,
                                SettingsRepository $settingsRepository,
                                SectionManager $sectionManager,
                                string $permissionNode = Permissions::ADMIN_CONTENT_MANAGER)
    {
        parent::__construct($authenticator, $permissionNode);

        $this->settingsRepository = $settingsRepository;
    }

    /**
     * @return Form
     */
    public function createComponentChangeHeaderSectionForm(): Form {
        return (new ChangeHeaderSectionForm($this, $this->settingsRepository, $this->sectionManager))->create();
    }
}