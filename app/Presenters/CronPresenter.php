<?php


namespace App\Presenters;


use App\Model\API\Plugin\LuckPerms;
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

    /**
     * CronPresenter constructor.
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct();

        $this->context = $context;
    }

    /**
     * @throws AbortException
     */
    public function actionSavingPlaytime() {
        if($_SERVER['SERVER_ADDR'] === $_SERVER['REMOTE_ADDR']) {
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