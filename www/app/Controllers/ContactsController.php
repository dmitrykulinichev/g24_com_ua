<?php

namespace App\Controllers;

class ContactsController
{
    protected $blade;

    public function __construct()
    {
        $this->blade = new \Jenssegers\Blade\Blade(__DIR__ . '/../../views', __DIR__ . '/../../storage/cache');
    }

    public function index()
    {
        $meta = [
            'title' => 'Контакти',
            'description' => 'Зв\'яжіться з нами для консультації або технічної підтримки. Ми завжди на зв\'язку.'
        ];

        // Передаємо darkBg = true
        echo $this->blade->make('contacts', ['meta' => $meta, 'darkBg' => true])->render();
    }
}