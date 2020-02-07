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
use App\Model\Role;
use SleepingOwl\Admin\Contracts\Initializable;
use Auth;
/**
 * Class Roles
 *
 * @property \App\Model\Role $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Roles extends Section implements Initializable
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

    public function initialize()
    {
        //$this->addToNavigation()->setIcon('fa fa-globe');
    }

    public function getTitle()
    {
        return 'Роли';
    }

    public function getCreateTitle()
    {
        return 'Добавление роли';
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        $display = AdminDisplay::table()->setColumns([
            AdminColumn::text('id')->setLabel('ID'),
            AdminColumn::text('display_name')->setLabel('Название роли'),
            AdminColumn::text('name')->setLabel('Alias'),
            AdminColumn::text('description')->setLabel('Описание'),
            AdminColumn::datetime('created_at','Добавлено')->setFormat('d.m.Y H:i'),
        ]);

        $display->setNewEntryButtonText('Добавить');
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
            AdminFormElement::text('display_name', 'Название роли')->required(),
            AdminFormElement::text('name', 'Alias')->unique()->required(),
            AdminFormElement::textarea('description', 'Описание'),
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
