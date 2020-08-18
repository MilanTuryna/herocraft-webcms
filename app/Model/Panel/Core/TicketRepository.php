<?php

namespace App\Model\Panel\Core;

use App\Model\Utils;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\IRow;

/**
 * Class TicketRepository
 * @package App\Model\Panel\Core
 */
class TicketRepository
{
    const TABLE = 'tickets';
    const RESPONSE_TABLE = 'ticket_responses';
    const TYPES = [
        'admin' => 'Admin',
        'support' => 'Support',
        'player' => 'Hráč'
    ];

    private Context $context;

    /**
     * TicketRepository constructor.
     * @param Context $context
     *
     * database.default
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @return array|IRow[]
     */
    public function getAllTickets() {
        return $this->context->table(self::TABLE)->fetchAll();
    }

    /**
     * @param $author
     * @return array|IRow[]
     */
    public function getTicketsByAuthor($author) {
        return $this->context->table(self::TABLE)->where('author = ?', $author)->fetchAll();
    }

    public function getTicketById($id) {
        return $this->context->table(self::TABLE)->wherePrimary($id)->fetch();
    }

    public function getResponseById($id)  {
        return $this->context->table(self::RESPONSE_TABLE)->wherePrimary($id)->fetch();
    }

    /**
     * @param $ticketId
     * @return int
     */
    public function lockTicket($ticketId) {
        return $this->context->table(self::TABLE)->wherePrimary($ticketId)->update([
            'locked' => 1
        ]);
    }

    /**
     * @param $ticketId
     * @return array|IRow[]
     */
    public function getTicketResponses($ticketId) {
        return $this->context->table(self::RESPONSE_TABLE)->where('ticketId = ?', $ticketId)->order('time ASC')->fetchAll();
    }

    /**
     * @param int $ticketId
     * @param array $response
     * @return bool|int|ActiveRow
     */
    public function addResponse(int $ticketId, array $response) {
        return $this->context->table(self::RESPONSE_TABLE)->insert([
            'author' => $response['author'],
            'type' => $response['type'],
            'content' => $response['content'],
            'ticketId' => $ticketId,
        ]);
    }

    /**
     * @param array $ticket
     * @return bool|int|ActiveRow
     */
    public function addTicket(array $ticket) {
        return $this->context->table(self::TABLE)->insert([
            'name' => $ticket['name'],
            'author' => $ticket['author'],
            'time' => Utils::sqlNow()
        ]);
    }

    /**
     * @param $ticketId
     * @return int
     */
    public function removeTicket($ticketId)
    {
        return $this->context->table(self::TABLE)->wherePrimary($ticketId)->delete();
    }

    /**
     * @param $responseId
     * @return int
     */
    public function removeResponse($responseId) {
        return $this->context->table(self::RESPONSE_TABLE)->wherePrimary($responseId)->delete();
    }

    public function editResponse($responseId, $message) {
        return $this->context->table(self::RESPONSE_TABLE)->wherePrimary($responseId)->update([
           'content' => $message
        ]);
    }

    /**
     * @return Context
     */
    public function getDatabaseContext() {
        return $this->context; // database.default -> config
    }
}