<?php

namespace App\Admin\Http\Sections;

use AdminColumn;
use AdminDisplay;
use AdminDisplayFilter;
use AdminForm;
use AdminFormElement;
use AdminColumnFilter;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Section;
use App\Model\Driver;

/**
 * Class Buses
 *
 * @property \App\Model\Bus $model
 *
 */
class Drivers extends Section
{
    protected $checkAccess = true;


    public function getTitle()
    {
        return 'Водители';
    }

    public function getCreateTitle()
    {
        return 'Добавление водителя';
    }



    public function onDisplay()
    {
        $display = AdminDisplay::table()->setColumns([
            AdminColumn::text('id')->setLabel('ID'),
            AdminColumn::link('last_name')->setLabel('ФИО'),
            AdminColumn::datetime('created_at','Добавлен')->setFormat('d.m.Y'),
        ]);
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
        return AdminForm::panel()->addBody([
            AdminFormElement::text('last_name', 'Фамилия')->required(),
            AdminFormElement::text('name', 'Имя')->required(),
            AdminFormElement::text('middle_name', 'Отчество')->required(),

        ]);
    }

    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        return $this->onEdit(null);
    }


}