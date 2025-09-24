<?php

namespace App\Entities;

use Core\Database\Entity;

class Course extends Entity {

    const THEME_INOVACAO = "inovação";
    const THEME_TECNOLOGIA = "tecnologia";
    const THEME_MARKETING = "marketing";
    const THEME_EMPREENDEDORISMO = "empreendedorismo";
    const THEME_AGRO = "agro";

    const THEMES = [
        self::THEME_INOVACAO,
        self::THEME_TECNOLOGIA,
        self::THEME_MARKETING,
        self::THEME_EMPREENDEDORISMO,
        self::THEME_AGRO
    ];

    public static string $table = "courses";

    public string $title;
    public string $description;
    public string $theme;
    public ?string $url_image;
}
