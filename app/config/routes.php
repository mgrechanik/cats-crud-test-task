<?php

use App\controllers\CatsController;

return [
    'main' => [CatsController::class, 'main'],
    'list' => [CatsController::class, 'list'],
    'create' => [CatsController::class, 'create'],
    'edit' => [CatsController::class, 'edit'],
    'delete' => [CatsController::class, 'delete'],
];
