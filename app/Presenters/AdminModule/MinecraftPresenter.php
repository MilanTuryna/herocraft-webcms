<?php


namespace App\Presenters\AdminModule;


use App\Model\API\Plugin\ChatLog;
use App\Model\Security\Auth\Authenticator;
use App\Presenters\AdminBasePresenter;
use Nette\Application\AbortException;

class MinecraftPresenter extends AdminBasePresenter
{
    private ChatLog $chatLog;

    public function __construct(Authenticator $authenticator, ChatLog $chatLog)
    {
        parent::__construct($authenticator);

        $this->chatLog = $chatLog;
    }

    /**
     * @param int $page
     * @throws AbortException
     */
    public function renderChat(int $page = 1) {
        $messages = $this->chatLog->findAllRows();

        $lastPage = 0;
        $paginatorData = $messages->page($page, 20, $lastPage);
        $this->template->messages = $paginatorData;

        $this->template->page = $page;
        $this->template->lastPage = $lastPage;

        if($page > $lastPage+1) {
            $this->redirect("Minecraft:chat");
        }
    }
}