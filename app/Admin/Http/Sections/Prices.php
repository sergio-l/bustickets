<?php

namespace App\Admin\Http\Sections;

use AdminColumn;
use AdminDisplay;
use AdminDisplayFilter;
use AdminForm;
use AdminFormElement;
use AdminColumnFilter;

use App\Model\Flight;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Section;

/**
 * Class Prices
 *
 * @property \App\Model\FlightPrice $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Prices extends Section
{
    /**
     * @see http://sleepingowladmin.ru/docs/model_configuration#ограничение-прав-доступа
     *
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $alias;

    

    public function getTitle()
    {
        return 'Стоимость проезда по маршрутам';
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        $display = AdminDisplay::datatables();
        $display->setColumns([
            AdminColumn::text('number')->setLabel('№'),
            AdminColumn::custom('Отправления', function($instance) {
                return $instance->stations->first()->pivot->departure;
            }),
            AdminColumn::custom('Маршрут', function($instance) {
                return $instance->stations->first()->title .' - '. $instance->stations->last()->title;
            }),

        ]);
        ;
        $display->paginate(15);
        return $display;
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {
        // remove if unused
    }

    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        return $this->onEdit(null);
    }

    /**
     * @return void
     */
    public function onDelete($id)
    {
        // remove if unused
    }

    /**
     * @return void
     */
    public function onRestore($id)
    {
        // remove if unused
    }
}
