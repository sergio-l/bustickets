<?php

namespace App\Admin\Http\Sections;

use AdminForm;
use AdminFormElement;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Section;

/**
 * Class Settings
 *
 * @property \App\Model\Setting $model
 */
class Settings extends Section
{
    /**
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
        return 'Настройки';
    }

    public function getEditTitle()
    {
        return 'Настройки';
    }


    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {
        return AdminForm::panel()->addBody([
            AdminFormElement::text('book_time', 'Время выкупа билета')->required(),
            AdminFormElement::select('site_work','Бронирования билетов на сайте')
                ->setOptions(['disabled' => 'Отключено', 'enabled' => 'Включен']),
            AdminFormElement::select('type_paid','Способы оплаты')
                ->setOptions(['all' => 'Оплата картой онлайн / Оплата наличными в кассе',
                    'prepay' => 'Оплата картой онлайн',
                    'cash'   => 'Оплата наличными в кассе'
                ]),

        ]);
    }


    public function isCreatable()
    {
        return false;
    }


    public function isDeletable(\Illuminate\Database\Eloquent\Model $model)
    {
        return false;
    }
}
