<?php

namespace App\Admin\Http\Sections;

use AdminColumn;
use AdminDisplay;
use AdminDisplayFilter;
use AdminForm;
use AdminFormElement;
use AdminColumnFilter;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Section;
use App\Model\UserHistory;

/**
 * Class Buses
 *
 * @property \App\Model\Bus $model
 *
 */
class UsersHistory extends Section implements Initializable
{

    protected $checkAccess = true;

    public function getTitle()
    {
        return 'История изменения';
    }


    public function initialize()
    {
        $this->addToNavigation()->setIcon('fa fa-history');
    }

    public function onDisplay()
    {
        $display = AdminDisplay::datatablesAsync()
            ->setDisplaySearch(true);

        $display->setColumns([
            AdminColumn::relatedLink('order.id','№ заказа'),
            AdminColumn::link('page', 'URL')->setLinkAttributes(['target' => '_blank']),
            AdminColumn::custom('Сотрудник', function($instance){
                return $instance->user->last_name .' '. $instance->user->name;
            }),

            AdminColumn::custom('Изменения статуса', function($instance){
                if($instance->status == 'new'){
                    return '<span style="color: blue">Новый</span>';
                }elseif($instance->status == 'success'){
                    return '<span style="color: green">Выполнен</span>';
                }elseif($instance->status == 'processing') {
                    return '<span>В обработке</span>';
                }
            }),
            AdminColumn::datetime('created_at','Дата')->setFormat('d.m.Y'),
        ]);
        $display->paginate(15);
        return $display;
    }

    public function isDeletable(\Illuminate\Database\Eloquent\Model $model)
    {
        return false;
    }



}