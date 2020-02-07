<?php

namespace App\Admin\Http\Sections;

use AdminColumn;
use AdminDisplay;
use AdminDisplayFilter;
use AdminForm;
use AdminFormElement;
use AdminColumnFilter;
use App\Model\Ticket;
use App\Model\Order;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Section;
use Auth;
use App\Model\Flight;

/**
 * Class Ticket
 *
 * @property \App\Model\Ticket $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Orders extends Section
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
        return 'Заказы';
    }

    public function getCreateTitle()
    {
        return 'Оформление билета';
    }
    

    public function initialize()
    {
        //$this->addToNavigation()->setIcon('fa fa-bus');
    }

    public function onDisplay()
    {
        $display = AdminDisplay::datatablesAsync()
            ->setDisplaySearch(true);
        $display->setOrder([[0, 'desc']]);
        $display->setNewEntryButtonText('Оформить билет');

        $display->setColumns([
            AdminColumn::text('id')->setLabel('№'),

            AdminColumn::text('phone')->setLabel('Телефон'),
            AdminColumn::text('suma')->setLabel('Сумма'),
           
            AdminColumn::custom('Способ оплаты', function ($instance){
                switch ($instance->payment_method){
                    case 'cash': return '<span style="color:blue">Оплата при получении';
                    case 'paid': return '<span style="color: green">Оплата онлайн</span>';
                    case 'not-paid': return '<span style="color: red;">Оплата онлайн (не оплачено)</span>';
                }
            }),
            AdminColumn::custom('Статус', function ($instance){
                if( $instance->status == 'success'){
                    return '<span style="color: green">Выполнен</span>';
                }elseif($instance->payment_method == 'cash' && $instance->status == 'new'){
                    return '<span style="color: red">Не оплачено</span>';
                }elseif($instance->status == 'processing'){
                    return '<span style="color: red">Отмена</span>';
                }
            }),
            AdminColumn::custom('Билетов', function($instance){
                return isset($instance->countTicket[0]['tcount']) ? $instance->countTicket[0]['tcount'] : null;
                }),
            AdminColumn::datetime('created_at','Создан')->setFormat('d.m.Y H:i:s'),
            AdminColumn::custom('', function($instance) {
                return '<a href="/admin/orders/'.$instance->id.'/PDFtickets" target="_blank" class="btn btn-xs btn-default" title="Билеты"><i class="fa fa-print"></i></a>';
            }),

            AdminColumn::custom('', function($instance) {
                //if($instance->tickets[0]->date)
                return '<a href="/admin/order/'.$instance->id.'/return" target="_blank" class="btn btn-xs btn-warning" title="Возврат"><i class="fa fa-handshake-o"></i></a>';
            }),

           
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
        $tickets = Ticket::where(['order_id' => $id])->get();
        $orders = Order::find($id);

        return AdminFormElement::html(view('admin.tickets.edit',
            ['id' => $id, 'tickets' => $tickets, 'orders' => $orders])
        );
    }


    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        $stations = \App\Model\Station::all();
        $form = AdminForm::panel();
        $form->addBody([
            AdminFormElement::custom()->setDisplay(view('admin.tickets.search_form', ['stations' => $stations])),
        ]);
        return $form;
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
