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
use App\Model\Bus;

/**
 * Class Buses
 *
 * @property \App\Model\Bus $model
 *
 */
class Buses extends Section implements Initializable
{

    protected $checkAccess = true;

    public function getTitle()
    {
        return 'Автобусы';
    }

    public function getCreateTitle()
    {
        return 'Добавление автобуса';
    }

    public function initialize()
    {
        $this->addToNavigation()->setIcon('fa fa-bus');
    }

    public function onDisplay()
    {
        $display = AdminDisplay::table()->setColumns([
            AdminColumn::text('id')->setLabel('ID'),
            AdminColumn::link('title')->setLabel('Марка автобуса'),
            AdminColumn::text('number')->setLabel('№'),
            AdminColumn::text('places')->setLabel('Посадочные места'),
            AdminColumn::image('image', 'Изображения'),
            AdminColumn::custom('Статус', function ($instance) {
                return $instance->published ? '<i class="fa fa-check"></i>' : '<i class="fa fa-minus"></i>';
            })->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
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
            AdminFormElement::text('title', 'Марка автобуса')->required(),
            AdminFormElement::columns()->addColumn([
                AdminFormElement::text('number', '№ знак')->required(),
            ],3),
            AdminFormElement::columns()->addColumn([
                AdminFormElement::number('places', 'Посадочные места')
                    ->setMin(1)
                    ->setStep(1)
                    ->required(),
                ],2),
            AdminFormElement::ckeditor('content', 'Информация'),
            AdminFormElement::image('image', 'Главное изображения')->setUploadPath(function(\Illuminate\Http\UploadedFile $file) {
                return 'uploads/buses';
            }),
            AdminFormElement::images('images', 'Дополнительные изображения')->setUploadPath(function(\Illuminate\Http\UploadedFile $file) {
                return 'uploads/buses';
            }),
            AdminFormElement::checkbox('published', 'Активен')
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