<?php

namespace App\Controllers;

use App\Docs\DocsStore;

/**
 * Посібник водія — окремий розділ сайту (/driver, /driver/<slug>, вміст — content/driver).
 * Код і шаблони — документації (Docs2Controller, views/docs2): інші лише тека, меню, адреса, пошук і тексти.
 */
class DriverGuideController extends Docs2Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->store = DocsStore::driver();
        $this->contentPath = $this->store->root();
    }

    protected function section(): array
    {
        return [
            'name'         => 'driver',
            'base'         => $this->store->basePath(),
            'search'       => '/api/driver/search',
            'title'        => t('driver.index.title'),
            'subtitle'     => t('driver.index.subtitle'),
            'nav'          => t('driver.page.nav'),
            'breadcrumb'   => t('driver.page.breadcrumb'),
            'title_suffix' => t('driver.meta.title'),
        ];
    }

    protected function indexMeta(): array
    {
        return [
            'title' => t('driver.meta.title'),
            'description' => t('driver.meta.description'),
        ];
    }

    /** У посібнику старих адрес немає. */
    protected function movedTo(string $slug): ?string
    {
        return null;
    }
}
