<?php

namespace App\Admin\Http\Sections;

use AdminColumn;
use AdminDisplay;
use AdminDisplayFilter;
use AdminForm;
use AdminFormElement;
use AdminColumnFilter;
use Auth;

use App\Admin\Http\Controllers\FlightController;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Section;
use App\Model\Flight;
use App\Model\Bus;
use App\Model\Type;
use App\Model\Station;
use App\Model\User;
use App\Model\Driver;

/**
 * Class Buses
 *
 * @property \App\Model\Flight $model
 *
 */
class Flights extends Section
{

    protected $checkAccess = true;

    public function getTitle()
    {
        return 'Рейсы';
    }

    public function initialize()
    {
        // Добавление пункта меню и счетчика кол-ва записей в разделе
        //$this->addToNavigation()->setIcon('fa fa-globe');
    }

    public function getCreateTitle()
    {
        return 'Добавление рейса';
    }

    public function getEditTitle()
    {
        return 'Редактирования рейса';
    }

    public function onDisplay()
    {
        $display = AdminDisplay::datatables();
        $display->setApply(function ($query) {
            $query->where(['parent_id' => null]);
        });

        $display->setApply(function ($query) {
            $query->orderBy('number', 'asc');
        });

        $display->setColumns([

            AdminColumn::text('number')->setLabel('№'),
            AdminColumn::custom('Отправления', function($instance) {
                return isset($instance->stations->first()->pivot->departure) ? date("H:i",strtotime($instance->stations->first()->pivot->departure)) : null;
            }),
            AdminColumn::custom('Маршрут', function($instance) {
                if(isset($instance->stations->first()->title) && isset($instance->stations->last()->title)) {
                    return $instance->stations->first()->title . ' - ' . $instance->stations->last()->title;
                }else{
                    return '-';
                }
            }),
            AdminColumn::custom('Прибытие', function($instance) {
                return isset($instance->stations->last()->pivot->arrival) ? date("H:i",strtotime($instance->stations->last()->pivot->arrival)) : null;
            }),
            AdminColumn::text('buses.number')->setLabel('№ автобуса'),
            AdminColumn::text('driver.full_name')->setLabel('Водитель'),
            AdminColumn::lists('types.type')->setLabel('Дни рейса'),
            AdminColumn::text('status')->setLabel('Статус'),
            AdminColumn::datetime('created_at','Добавлено')->setFormat('d.m.Y'),
           
            AdminColumn::custom('', function($instance) {
                if (Auth::user()->isSuperAdmin() || Auth::user()->isManager()) {
                    return '<a href="/admin/flights/' . $instance->id . '/price" class="btn btn-xs btn-default" title="Стоимость проезда"><i class="fa fa-money"></i></a>';
                }
            }),
        ]);

        $display->setNewEntryButtonText('Добавить рейс');
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
        $flight      = Flight::find($id);
        $stations    = Station::all();
        $buses       = Bus::all();
        $drivers     = Driver::all();
        $flight_type = Type::all();


        return AdminForm::panel()->addBody([
            AdminFormElement::multiselect('types', 'Дни рейса')
                ->setModelForOptions(\App\Model\Type::class)
                ->setDisplay('type')
                ->required(),
            AdminFormElement::view('admin.flight.edit_form', ['stations' => $stations,
                'flight' => $flight,
                'busess' => $buses,
                'drivers'=> $drivers,
                'types'  => $flight_type,
            ])

        ]);

    }


    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        $stations = Station::all();
        $buses = Bus::all();
        $flight_type = Type::all();
        $drivers = Driver::all();

        $elements['start_date'] = AdminFormElement::date('start_date', 'Начальная дата дейсвия рейса')->required();
        $elements['end_date'] = AdminFormElement::date('end_date', 'Конечная дата дейсвия рейса')->required();
        $elements['types'] = AdminFormElement::multiselect('types', 'Дни рейса')
            ->setModelForOptions(\App\Model\Type::class)
            ->setDisplay('type')
            ->setSortable(false)
            ->required();

        return AdminFormElement::html(view('admin.flight.create_form',
            ['stations' => $stations,
                'busess' => $buses,
                'drivers'=> $drivers,
                'types'  => $flight_type,
                'elements' =>  $elements
            ]));
    }

    public function delete()
    {
        return 1;
    }

}