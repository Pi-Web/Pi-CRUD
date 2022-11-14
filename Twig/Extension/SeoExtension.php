<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Twig\Extension;

use Symfony\Component\String\AbstractString;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use function Symfony\Component\String\u;

final class SeoExtension extends AbstractExtension
{
    public const MIN_TITLE_LENGTH = 30;
    public const MAX_TITLE_LENGTH = 65;

    public const MIN_DESCRIPTION_LENGTH = 120;
    public const MAX_DESCRIPTION_LENGTH = 155;

    private const BRANDING = ' | Quimper Orientation';

    public function getFilters(): array
    {
        return [
            new TwigFilter('seo_title', [$this, 'processTitle']),
            new TwigFilter('seo_description', [$this, 'processDescription']),
        ];
    }

    private function prepareStr(string $str): AbstractString
    {
        return u(strip_tags($str))->trim();
    }

    public function processTitle(string $title): string
    {
        $str = $this->prepareStr($title);
        $brandingStr = u(self::BRANDING);
        $length = $str->length();

        // Nominal case
        if ($length >= self::MIN_TITLE_LENGTH && $length <= self::MAX_TITLE_LENGTH) {
            // Is there enough place for the branding?
            if (($length + $brandingStr->length()) <= self::MAX_TITLE_LENGTH) {
                $str = $str->ensureEnd($brandingStr->toString());
            }

            return $str->toString();
        }

        // Title too short, we add the branding
        if ($length < self::MIN_TITLE_LENGTH) {
            $str = $str->ensureEnd($brandingStr->toString());
        }

        // Title too long, we cup
        if ($length > self::MAX_TITLE_LENGTH) {
            $str = $str->truncate(self::MAX_TITLE_LENGTH);
        }

        return $str->toString();
    }

    public function processDescription(string $description): string
    {
        $str = $this->prepareStr($description);
        $length = $str->length();

        if ($length >= self::MIN_DESCRIPTION_LENGTH && $length <= self::MAX_DESCRIPTION_LENGTH) {
            return $str->toString();
        }

        // Description too long, we cut
        if ($length > self::MAX_DESCRIPTION_LENGTH) {
            $str = $str->truncate(self::MAX_DESCRIPTION_LENGTH);
        }

        return $str->toString();
    }
}