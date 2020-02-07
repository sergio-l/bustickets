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
use App\Model\Order;

/**
 * Class Buses
 *
 * @property \App\Model\Bus $model
 *
 */
class Statistics extends Section implements Initializable
{

    //protected $checkAccess = true;

    public function getTitle()
    {
        return 'Статистика';
    }


    public function initialize()
    {
        $this->addToNavigation()->setIcon('fa fa-eye');
    }

    public function onDisplay()
    {
        $display = AdminDisplay::table()->setColumns([
            AdminColumn::text('id')->setLabel('ID'),

        ]);
        $display->paginate(15);
        return $display;
    }


}