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
use SleepingOwl\Admin\Section;
use App\Model\Station;

/**
 * Class Buses
 *
 * @property \App\Model\Bus $model
 *
 */
class Stations extends Section
{
    protected $checkAccess = true;

    public function getTitle()
    {
        return 'Станции';
    }

    public function getCreateTitle()
    {
        return 'Добавление станции';
    }

    public function onDisplay()
    {
        $display = AdminDisplay::datatables()->setColumns([
            AdminColumn::text('id')->setLabel('ID'),
            AdminColumn::link('title')->setLabel('Название'),
            AdminColumn::custom('Статус', function ($instance) {
                return $instance->published ? '<i class="fa fa-check"></i>' : '<i class="fa fa-minus"></i>';
            })->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::datetime('created_at','Добавлено')->setFormat('d.m.Y'),
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
            AdminFormElement::text('title', 'Название')->required(),
            AdminFormElement::checkbox('published', 'Опубликовано')
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