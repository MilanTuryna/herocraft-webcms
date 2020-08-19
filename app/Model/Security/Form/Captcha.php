<?php


namespace App\Model\Security\Form;

use App\Constants;

/**
 * Class Captcha
 * @package App\Model\Security
 */
class Captcha
{
    const freqMethods = [
      'No' => ['Neni', 'Ne', 'Nie'],
      'Yes' => ['Ano', 'Je', 'Jo']
    ];

    const methods = [
        'Kolik dní je 48 hodin?' => ['2', '2 dny', 'Dva', 'Dva dny'],
        'Je slovo "pes" sloveso?' => self::freqMethods['No'],
        'Jaké je krajské město pardubického kraje?' => ['Pardubice'],
        'Jaké je hlavní město České Republiky?' => ['Praha', 'Prague'],
        'Je Frankfurt v česku?' => self::freqMethods['No'],
        'Je černá tmavá barva?' => self::freqMethods['Yes'],
        'Je slunce hvězda nebo planeta?' => ['Hvezda'],
        'Žijeme v 19. století nebo 21. století?' => ['21.', '21'],
        'Je Praha na slovensku?' => self::freqMethods['No'],
        'Je Praha v česku?' => self::freqMethods['Yes'],
        'Je Minecraft hra?' => self::freqMethods['Yes'],
        'Potřebujete na jízdu automobilem řidičský průkaz?' => self::freqMethods['Yes'],
        'Potřebujete na cyklistické kolo řidičský průkaz?' => self::freqMethods['No'],
        'Patří facebook mezi sociální sítě?' => self::freqMethods['Yes'],
        'Vytváří zapalovač oheň?' => self::freqMethods['Yes'],
        'Je New York v německu?' => self::freqMethods['No'],
        'Je Youtube fotobanka?' => self::freqMethods['No'],
        'Patří instagram mezi sociální sítě?' => self::freqMethods['Yes'],
        'Patří twitter mezi sociální sítě?' => self::freqMethods['Yes'],
        'Lední medvěd je hnědý?' => self::freqMethods['No'],
        'Patří slimák mezi rychlé zvířata?' => self::freqMethods['No'],
        'Patří mravenec mezi velké zvířata?' => self::freqMethods['No'],
        'Je Beatles zpěvecká skupina?' => self::freqMethods['Yes'],
    ];

    private $method;
    private $methodAnswers;

    public function __construct($method)
    {
        $this->method = $method;
        $this->methodAnswers = array_map(fn($data) => strtr(strtolower($data), Constants::VALID_URL), self::methods[$this->method]);
    }

    public static function getRandomMethod() {
        return array_rand(self::methods);
    }

    public static function getMethodOrder($method) {
        return array_search($method, array_keys(Captcha::methods));
    }

    public function getMethod() {
        return $this->method;
    }

    public function verify($input) {
        return in_array(strtr(mb_strtolower($input), Constants::VALID_URL), $this->methodAnswers);
    }
}