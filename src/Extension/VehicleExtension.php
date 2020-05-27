<?php

namespace App\Extension;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\MessageCatalogueInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class VehicleExtension
 * @package App\Extension
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class VehicleExtension extends AbstractExtension
{

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * VehicleExtension constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return array|\Twig\TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('trans_array', [$this, 'getTranslationFromArray']),
        ];
    }

    public function getTranslationFromArray(string $id, string $domain = 'messages')
    {

        /**
         * @var MessageCatalogueInterface
         */
        $messages = $this->translator->getCatalogue();

        $translations = [];

        foreach($messages->all($domain) as $key => $message) {
            if (substr($key, 0, strlen($id)) === $id) {
                $translations[$key] = $message;
            }
        }
        return $translations;
    }
}