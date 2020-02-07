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

/**
 * Class Users
 *
 * @property \App\Model\User $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Users extends Section
{
    /**
     * @see http://sleepingowladmin.ru/docs/model_configuration#ограничение-прав-доступа
     *
     * @var bool
     */
    protected $checkAccess = true;

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
        return 'Сотрудники';
    }

    public function getCreateTitle()
    {
        return 'Добавление сотрудника';
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        $display = AdminDisplay::datatables()->setColumns([
            AdminColumn::text('id')->setLabel('ID'),
            AdminColumn::custom('fio')->setCallback(function ($instance){
              return $instance->last_name .' '. $instance->name .' '. $instance->middle_name;
            })->setLabel('ФИО'),
            AdminColumn::text('email')->setLabel('Email'),
            AdminColumn::lists('roles.display_name','Роль'),
            AdminColumn::datetime('created_at','Зарегистрирован')->setFormat('d.m.Y H:i'),
        ]);
        $display->paginate(15);
        $display->setNewEntryButtonText('Добавить');
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

            AdminFormElement::columns()->addColumn([
                AdminFormElement::text('last_name', 'Фамилия')->required(),
            ],3)->addColumn([
                AdminFormElement::text('name', 'Имя')->required(),
            ],3)->addColumn([
                AdminFormElement::text('middle_name', 'Отчество'),
            ],3),

            AdminFormElement::columns()->addColumn([
                AdminFormElement::multiselect('roles', 'Должность')
                    ->setModelForOptions(\App\Model\Role::class)
                    ->setDisplay('display_name')
                    ->setSortable(false)
                    ->required()
            ],3),

            AdminFormElement::columns()->addColumn([
                AdminFormElement::text('email', 'Email')->unique()->required(),
            ],3),
            AdminFormElement::columns()->addColumn([
                AdminFormElement::password('password', 'Пароль')->required()->hashWithBcrypt(),
            ],3),

            AdminFormElement::columns()->addColumn([
                AdminFormElement::select('station_id', 'Станция (только для кассира)')
                    ->setModelForOptions(\App\Model\Station::class)
            ],3),

        ]);
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
