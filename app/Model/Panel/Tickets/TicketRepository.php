<?php

namespace App\Model\Panel\Tickets;

use App\Model\DI\Tickets\Settings;
use App\Model\Utils;
use Nette\Database\Explorer;
use Nette\Database\Row;
use Nette\Database\Table\ActiveRow;
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

    private Explorer $explorer;
    private Settings $settings;

    /**
     * TicketRepository constructor.
     * @param Explorer $explorer
     * database.default
     * @param Settings $settings
     */
    public function __construct(Explorer $explorer, Settings $settings)
    {
        $this->explorer = $explorer;
        $this->settings = $settings;
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return array|Row[]
     */
    public function getTickets(?int $limit = null, ?int $offset = null): array {
        $sql = "SELECT t.*, tr.author AS lastResponseAuthor, tr.type AS lastResponseType, tr.time AS lastResponseTime, last_responses.time as d FROM tickets AS t 
LEFT JOIN (SELECT ticketId, max(time) as time, author, type FROM ticket_responses GROUP BY ticketId) 
as last_responses ON t.id = last_responses.ticketId 
LEFT JOIN ticket_responses AS tr ON t.id = tr.ticketId AND last_responses.time = tr.time ORDER BY t.locked ASC, t.time DESC, tr.time DESC";
        if($limit) $sql.=" LIMIT ".$limit;
        if($offset) $sql.=" OFFSET ".$offset;
        return $this->explorer->query($sql)->fetchAll();
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
        return $this->explorer->table(self::TABLE)->count('*');
    }

    /**
     * @param $author
     * @return Selection
     */
    public function getTicketsByAuthor($author) {
        return $this->explorer->table(self::TABLE)->where('author = ?', $author);
    }

    /**
     * @param $id
     * @return Row|ActiveRow|null
     */
    public function getTicketById($id) {
        return $this->explorer->table(self::TABLE)->wherePrimary($id)->fetch();
    }

    /**
     * @param $id
     * @return Row|ActiveRow|null
     */
    public function getResponseById($id)  {
        return $this->explorer->table(self::RESPONSE_TABLE)->wherePrimary($id)->fetch();
    }

    /**
     * @param $ticketId
     * @param string $lockedBy
     * @return int
     */
    public function lockTicket($ticketId, string $lockedBy = '') {
        return $this->explorer->table(self::TABLE)->wherePrimary($ticketId)->update([
            'locked' => 1,
            'lockedBy' => $lockedBy
        ]);
    }

    /**
     * @param $ticketId
     * @return int
     */
    public function unlockTicket($ticketId) {
        return $this->explorer->table(self::TABLE)->wherePrimary($ticketId)->update([
            'locked' => 0,
            'lockedBy' => '',
        ]);
    }

    /**
     * @param $ticketId
     * @return array|Row[]
     */
    public function getTicketResponses($ticketId) {
        return $this->explorer->table(self::RESPONSE_TABLE)->where('ticketId = ?', $ticketId)->order('time ASC')->fetchAll();
    }

    /**
     * @param int $ticketId
     * @param array $response
     * @return bool|int|ActiveRow
     */
    public function addResponse(int $ticketId, array $response) {
        return $this->explorer->table(self::RESPONSE_TABLE)->insert([
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
        $ticket['time'] = Utils::sqlNow();
        return $this->explorer->table(self::TABLE)->insert($ticket);
    }

    /**
     * @param $ticketId
     * @return int
     */
    public function removeTicket($ticketId)
    {
        return $this->explorer->table(self::TABLE)->wherePrimary($ticketId)->delete();
    }

    /**
     * @param $responseId
     * @return int
     */
    public function removeResponse($responseId) {
        return $this->explorer->table(self::RESPONSE_TABLE)->wherePrimary($responseId)->delete();
    }

    /**
     * @param $responseId
     * @param $message
     * @return int
     */
    public function editResponse($responseId, $message) {
        return $this->explorer->table(self::RESPONSE_TABLE)->wherePrimary($responseId)->update([
           'content' => $message
        ]);
    }

    /**
     * @return int
     */
    public function getAllTicketsCount(): int {
        return $this->explorer->table(self::TABLE)->count("*");
    }

    /**
     * @return Explorer
     */
    public function getDatabaseExplorer(): Explorer {
        return $this->explorer;
    }

    /**
     * @return Settings
     */
    public function getTicketSettings(): Settings {
        return $this->settings;
    }

}