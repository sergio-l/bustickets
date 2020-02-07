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
 * Class Pages
 *
 * @property \App\Model\Page $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Pages extends Section
{
    /**
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
        return 'Страници';
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        $display = AdminDisplay::table()->setColumns([
            AdminColumn::text('id')->setLabel('ID'),
            AdminColumn::link('title')->setLabel('Название'),
            AdminColumn::text('alias')->setLabel('Alias/URL'),
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
            AdminFormElement::text('title', 'Заголовок страници')->required(),
            AdminFormElement::ckeditor('content', 'Содержимое')
                ->required(),
            AdminFormElement::textarea('description', 'Описания (SEO description)')
                ->setRows(3),
            AdminFormElement::textarea('keywords', 'Ключивые слова (SEO keywords)')
                ->setRows(2)
        ]);
    }

    /**
     * @return FormInterface
     */

    public function isDeletable(\Illuminate\Database\Eloquent\Model $model)
    {
        return false;
    }

}
