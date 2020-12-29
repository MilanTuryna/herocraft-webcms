<?php

namespace App\Model\Panel\Core\Tickets;

use App\Model\DI\Tickets\Settings;
use App\Model\Utils;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\IRow;
use Nette\Database\Table\Selection;

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
    private Settings $settings;

    /**
     * TicketRepository constructor.
     * @param Context $context
     * database.default
     * @param Settings $settings
     */
    public function __construct(Context $context, Settings $settings)
    {
        $this->context = $context;
        $this->settings = $settings;
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return array|\Nette\Database\IRow[]
     */
    public function getTickets(?int $limit = null, ?int $offset = null): array {
        $sql = "SELECT t.name, t.author, t.email, t.subject, t.gameVerified, t.time, t.locked, t.id, tr.author as lastResponseAuthor, 
tr.time as lastResponseTime, tr.type as lastResponseType FROM tickets as t LEFT JOIN ticket_responses as tr 
ON t.id = tr.ticketId ORDER BY t.time DESC, tr.time DESC";
        if($limit) $sql.=" LIMIT ".$limit;
        if($offset) $sql.=" OFFSET ".$offset;
        return $this->context->query($sql)->fetchAll();
    }

    /**
     * Method for getting ticket subjects in associative array format (for Nette Forms), exam. ["Jiné" => "Jiné"]
     *
     * @return array
     */
    public function getSelectBox() {
        return array_map(fn (array $subject) => array_combine(array_keys($subject), array_keys($subject)), $this->settings->getSubjects());
    }

    /**
     * @param $author
     * @return int
     */
    public function getTicketsCount($author) {
        return $this->context->table(self::TABLE)->count('*');
    }

    /**
     * @param $author
     * @return Selection
     */
    public function getTicketsByAuthor($author) {
        return $this->context->table(self::TABLE)->where('author = ?', $author);
    }

    /**
     * @param $id
     * @return \Nette\Database\IRow|ActiveRow|null
     */
    public function getTicketById($id) {
        return $this->context->table(self::TABLE)->wherePrimary($id)->fetch();
    }

    /**
     * @param $id
     * @return \Nette\Database\IRow|ActiveRow|null
     */
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
     * @return int
     */
    public function unlockTicket($ticketId) {
        return $this->context->table(self::TABLE)->wherePrimary($ticketId)->update([
            'locked' => 0
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
            'subject' => $ticket['subject'],
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

    /**
     * @param $responseId
     * @param $message
     * @return int
     */
    public function editResponse($responseId, $message) {
        return $this->context->table(self::RESPONSE_TABLE)->wherePrimary($responseId)->update([
           'content' => $message
        ]);
    }

    /**
     * @return int
     */
    public function getAllTicketsCount(): int {
        return $this->context->table(self::TABLE)->count("*");
    }

    /**
     * @return Context
     */
    public function getDatabaseContext() {
        return $this->context; // database.default -> config
    }

    /**
     * @return Settings
     */
    public function getTicketSettings(): Settings {
        return $this->settings;
    }

}