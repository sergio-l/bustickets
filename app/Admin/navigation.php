<?php

use SleepingOwl\Admin\Navigation\Page;

// Default check access logic
// AdminNavigation::setAccessLogic(function(Page $page) {
// 	   return auth()->user()->isSuperAdmin();
// });
//
// AdminNavigation::addPage(\App\User::class)->setTitle('test')->setPages(function(Page $page) {
// 	  $page
//		  ->addPage()
//	  	  ->setTitle('Dashboard')
//		  ->setUrl(route('admin.dashboard'))
//		  ->setPriority(100);
//
//	  $page->addPage(\App\User::class);
// });
//
// // or
//
// AdminSection::addMenuPage(\App\User::class)


AdminNavigation::setFromArray([
    [
        'title' => 'Dashboard',
        'icon'  => 'fa fa-dashboard',
        'priority' => 1,
        'url'   => route('admin.dashboard'),
    ],
/*
    [
        'title' => 'Маршруты',
        'id'  => 'routes_block',
        'priority' => 1,
        'icon' => 'fa fa-exchange',
        'pages' => [

        ]
    ],
*/
    (new \SleepingOwl\Admin\Navigation\Page(\App\Model\Flight::class))
        ->setIcon('fa fa-exchange')
        ->setPriority(2)
        ->setTitle('Маршруты'),
    (new \SleepingOwl\Admin\Navigation\Page(\App\Model\Bus::class))
        ->setIcon('fa fa-bus')
        ->setPriority(3)
        ->setTitle('Автобусы'),
    (new \SleepingOwl\Admin\Navigation\Page(\App\Model\Driver::class))
        ->setIcon('fa fa-id-card')
        ->setPriority(4)
        ->setTitle('Водители'),
    (new \SleepingOwl\Admin\Navigation\Page(\App\Model\Station::class))
        ->setIcon('fa fa-map-marker')
        ->setPriority(5)
        ->setTitle('Станции'),
    (new \SleepingOwl\Admin\Navigation\Page(\App\Model\Order::class))
        ->setIcon('fa fa-ticket')
        ->setPriority(6)
        ->setTitle('Заказы'),
    [
        'title' => 'Пользователи',
        'id'  => 'user_block',
        'priority' => 7,
        'icon' => 'fa fa-group',
        'pages' => [
            (new Page(\App\Model\User::class))
                ->setIcon('fa fa-user')
                ->setPriority(0)
                ->setTitle('Сотрудники'),
            (new Page(\App\Model\Role::class))
                ->setIcon('fa fa-group')
                ->setPriority(100)
                ->setTitle('Роли'),
        ]
    ],
    (new \SleepingOwl\Admin\Navigation\Page(\App\Model\Page::class))
        ->setIcon('fa fa-pencil')
        ->setPriority(8)
        ->setTitle('Страницы'),
    (new \SleepingOwl\Admin\Navigation\Page(\App\Model\Setting::class))
        ->setIcon('fa fa-cogs')
        ->setPriority(9)
        ->setUrl('/admin/settings/1/edit')
        ->setTitle('Настройки'),
    [
        'title' => 'Статистика',
        'icon'  => 'fa fa-eye',
        'url'   => '/admin/stat',
    ],

]);