<?php


namespace App\Presenters\AdminModule;


use App\Forms\Admin\Content\Homepage\ChangeHeaderSectionForm;
use App\Forms\Content\Sections\CreateSectionForm;
use App\Front\SectionRepository;
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
final class ContentPresenter extends AdminBasePresenter
{
    use SmartObject;

    private SettingsRepository $settingsRepository;
    private SectionRepository $sectionRepository;

    /**
     * ContentPresenter constructor.
     * @param Authenticator $authenticator
     * @param SettingsRepository $settingsRepository
     * @param SectionRepository $sectionRepository
     * @param string $permissionNode
     */
    public function __construct(Authenticator $authenticator,
                                SettingsRepository $settingsRepository,
                                SectionRepository $sectionRepository,
                                string $permissionNode = Permissions::ADMIN_CONTENT_MANAGER)
    {
        parent::__construct($authenticator, $permissionNode);

        $this->settingsRepository = $settingsRepository;
        $this->sectionRepository = $sectionRepository;
    }

    /**
     * @return Form
     */
    public function createComponentChangeHeaderSectionForm(): Form {
        return (new ChangeHeaderSectionForm($this, $this->settingsRepository))->create();
    }

    /**
     * @return Form
     */
    public function createComponentCreateSectionForm(): Form {
        return (new CreateSectionForm($this, $this->sectionRepository, $this->admin->getName()))->create();
    }
}