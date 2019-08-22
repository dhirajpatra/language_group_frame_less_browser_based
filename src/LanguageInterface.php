<?php
declare(strict_types=1);

namespace LanguageApp;

/**
 * this is base interface for Language module
 */
interface LanguageInterface {
    public function getLanguageDetails( $countryName ): string;

    public function getSimilarity( $countryNameFirst, $countryNameSecond ): string;
}