<?php


namespace App\Presenters;


use App\Model\API\Plugin\LuckPerms;
use App\Model\DI\Cron;
use Nette\Application\AbortException;
use Nette\Application\UI\Presenter;
use Nette\Database\Context;

/**
 * Class CronPresenter
 * @package App\Presenters
 */
class CronPresenter extends Presenter
{
    private Context $context;
    private Cron $cron;

    /**
     * CronPresenter constructor.
     * @param Context $context
     * @param Cron $cron
     */
    public function __construct(Context $context, Cron $cron)
    {
        parent::__construct();

        $this->context = $context;
        $this->cron = $cron;
    }

    /**
     * @param $authentication
     * @throws AbortException
     */
    public function actionSavingPlaytime($authentication) {
        if($authentication === $this->cron->getAuthenticationPassword()) {
            $arr = [];
            $helpers = $this->context->query("SELECT t2.username, t1.uuid, t1.permission, t1.server, t3.playtime FROM luckperms_user_permissions AS t1 LEFT JOIN luckperms_players AS t2 ON t1.uuid = t2.uuid INNER JOIN playtime AS t3 ON t2.username = t3.username WHERE t1.permission = ? OR t1.permission = ? OR t1.permission = ? OR t1.permission=? OR t1.permission=?",
                LuckPerms::GROUPS['helper'],
                LuckPerms::GROUPS['helpdesk'],
                LuckPerms::GROUPS['implement-web'],
                LuckPerms::GROUPS['admin'],
                LuckPerms::GROUPS['owner'])->fetchAll();
            foreach ($helpers as $helper) array_push($arr, [$helper->username, $helper->playtime]);
            $this->context->table("playtime_week")->insert($arr);
        } {
            $this->flashMessage("Přístup zamítnut.", "danger");
        }
        $this->redirect(":Front:Page:home");
    }

}