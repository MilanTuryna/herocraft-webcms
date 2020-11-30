<?php


namespace App\Model\Panel\Object;

use Nette\SmartObject;

/**
 * Class Ticket
 * @package App\Model\Panel\Object
 */
class Ticket
{
    use SmartObject;

    private string $author;
    private string $name;
    private string $subject;
    private string $content;
    private ?int $id;

    /**
     * Ticket constructor.
     * @param string $author
     * @param string $name
     * @param string $subject
     * @param string $content
     * @param int $id
     */
    public function __construct(string $author, string $name, string $subject, string $content, ?int $id = null)
    {
        $this->author = $author;
        $this->name = $name;
        $this->subject = $subject;
        $this->content = $content;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}