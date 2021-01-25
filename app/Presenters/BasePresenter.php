<?php

namespace App\Presenters;

use App\Model\DI;
use App\Model\Front\Parsers\HTMLParser;
use Contributte;
use Nette;
use App\Model\Utils;
use Nette\Application\Helpers;

/**
 * Class BasePresenter
 * @package App\Presenters
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    private DI\GoogleAnalytics $googleAnalytics;

    /** @var DI\Seo @inject */
    public DI\Seo $seo;

    /** @var Nette\Localization\Translator @inject */
    public Nette\Localization\Translator $translator;

    /** @var Contributte\Translation\LocalesResolvers\Session @inject */
    public Contributte\Translation\LocalesResolvers\Session $translatorSessionResolver;

    /**
     * BasePresenter constructor.
     * @param DI\GoogleAnalytics $googleAnalytics
     */
    public function __construct(DI\GoogleAnalytics $googleAnalytics)
    {
        parent::__construct();

        $this->googleAnalytics = $googleAnalytics;
    }

    /**
     * @return array
     */
    public function formatTemplateFiles(): array
    {
        [$module, $presenter] = Helpers::splitName($this->getName());
        $dir = dirname(static::getReflection()->getFileName());
        $dir = is_dir("$dir/templates") ? $dir : dirname($dir);
        return [
            "$dir/templates/$module/$presenter/$this->view.latte",
            "$dir/templates/$module/$presenter.$this->view.latte",
        ];
    }

    public function startup()
    {
        parent::startup();

        $this->template->addFilter('substrWithoutHTML', fn($value, $limit) => trim(Utils::substrWithoutHTML($value, $limit)));
        $this->template->addFilter('allowAsteriskReplace', fn($input) => HTMLParser::replaceAsterisk($input));
        $this->template->sklonovani = fn($pocet, $slova) => Utils::sklonovani($pocet, $slova);
        $this->template->httpRequest = $this->getHttpRequest();
        $this->template->googleAnalytics = $this->googleAnalytics;
        $this->template->seo = $this->seo;
    }

    /**
     * @return Nette\Localization\Translator
     */
    public function getTranslator(): Nette\Localization\Translator
    {
        return $this->translator;
    }

    /**
     * @return Contributte\Translation\LocalesResolvers\Session
     */
    public function getTranslatorSessionResolver(): Contributte\Translation\LocalesResolvers\Session
    {
        return $this->translatorSessionResolver;
    }
}