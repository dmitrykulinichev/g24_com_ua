<?php

require __DIR__ . '/vendor/autoload.php';

use Jenssegers\Blade\Blade;

// Ініціалізація Leaf
$app = new Leaf\App;

// Налаштування Blade
// Шляхи до views та кешу
$views = __DIR__ . '/views';
$cache = __DIR__ . '/storage/cache';

$blade = new Blade($views, $cache);

// Передаємо $blade у маршрути через use() або глобально, 
// але краще зробити простий хелпер або використовувати use ($blade)
// Для простоти зараз будемо використовувати use ($blade) в routes.php

// Маршрути
// Оскільки routes.php у нас просто підключається, ми можемо передати $blade туди
// Але краще трохи переписати routes.php, щоб він був функцією або просто використовував глобальну змінну (для початку)
// Або просто підключимо його тут, і він матиме доступ до $blade, бо це той самий scope.

require __DIR__ . '/app/routes.php';

$app->run();