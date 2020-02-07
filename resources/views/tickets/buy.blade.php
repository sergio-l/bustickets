@extends('layouts.app')
@section('title')Оформления билета@endsection
@section('content')
    @php
        $places = $data->buses->places;
        $num = $places  / 4;
        $sr = round($num);
       //$arrPlaces = [3,4,1,2,5,6,8,9,10,12,11,18,17,13,14,15,20,19,21,22,23,24,25,26,7,16,27,28];
    @endphp
    <section class="background-gray-lightest">
        <div class="container">
            <div id="app" class="content">
                <form method="post" action="/tickets/buy">
                    <div class="row">
                        <h3>Выберите {{$count > 1 ? 'места' : 'место'}}</h3>

                        <div class="col-md-offset-3 col-md-2">
                            <table class="table mtd ">
                                <tr>
                                    <td rowspan="5" >
                                        <i class="fa fa-user"></i>
                                    </td>
                                    <td>
                                <span class="button-checkbox">
                                    <button type="button" class="btn btn-sm"
                                            v-bind:class="isSelected({{$places}})" v-on:click="select({{$places}})"
                                            :disabled="isDisabled({{$places}}, {{ in_array($places, $arrPlaces) ? true : false}})">{{$places}}
                                   </button>
                                    <input type="checkbox" class="hidden" name="places[]" v-model="inputs" value="{{ $places }}" />
                                </span>
                                    </td>
                                    @for($i = 4; $i < $places - 4; $i+= 4)
                                        @if($num < $sr &&  $i == $places - 7)
                                            <td></td>
                                            <td>
                                <span class="button-checkbox">
                                    <button type="button" class="btn btn-sm"
                                            v-bind:class="isSelected({{$i + 3}})" v-on:click="select({{$i + 3}})"
                                            :disabled="isDisabled({{$i + 3}}, {{ in_array($i + 3,$arrPlaces) ? true : false}})">{{$i + 3}}
                                    </button>
                                    <input type="checkbox" class="hidden" name="places[]" v-model="inputs" value="{{$i + 3}}" />
                                </span>
                                            </td>
                                            @break
                                        @endif
                                        <td>
                                <span class="button-checkbox">
                                    <button type="button" class="btn btn-sm" v-bind:class="isSelected({{$i == ($places - 5) ? $i + 1 : $i }})"
                                            v-on:click="select({{$i == ($places - 5) ? $i + 1 : $i }})"
                                            :disabled="isDisabled({{$i == ($places - 5) ? $i + 1 : $i }}, {{ in_array($i,$arrPlaces) ? true : false}})">{{$i == ($places - 5) ? $i + 1 : $i }}
                                    </button>
                                    @if(!in_array($i, $arrPlaces))<input type="checkbox" value="{{$i}}" name="places[]"  v-model="inputs" class="hidden" /> @endif
                                </span>
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td class="col-md-1">
                                        <span class="button-checkbox">
                                            <button type="button" class="btn btn-sm"
                                                    v-bind:class="isSelected({{$places - 1}})" v-on:click="select({{$places - 1}})"
                                                    :disabled="isDisabled({{$places - 1}}, {{ in_array($places - 1, $arrPlaces) ? true : false}})">{{$places - 1}}
                                           </button>
                                            <input type="checkbox" class="hidden" name="places[]" v-model="inputs" value="{{ $places - 1 }}" />
                                        </span>
                                    </td>
                                    @for($i = 3; $i < $places - 4; $i+= 4)
                                        @if($num < $sr &&  $i == $places - 8)
                                            <td></td>
                                        <td>
                                            <span class="button-checkbox">
                                                <button type="button" class="btn btn-sm"
                                                        v-bind:class="isSelected({{$i + 3}})" v-on:click="select({{$i + 3}})"
                                                        :disabled="isDisabled({{$i + 3}}, {{ in_array($i + 3,$arrPlaces) ? true : false}})">{{$i + 3}}
                                                </button>
                                                @if(!in_array($i + 3,$arrPlaces)) <input type="checkbox" class="hidden" v-model="inputs" name="places[]" value="{{$i + 3}}" />@endif
                                            </span>
                                        </td>
                                        @break
                                        @endif
                                        <td>
                                        <span class="button-checkbox">
                                            <button type="button" class="btn btn-sm"
                                                    v-bind:class="isSelected({{$i == ($places - 6) ? $i + 1 : $i }})" v-on:click="select({{$i == ($places - 6) ? $i + 1 : $i }})"
                                                    :disabled="isDisabled({{$i == ($places - 6) ? $i + 1 : $i }}, {{ in_array($i,$arrPlaces) ? true : false}})">{{$i == ($places - 6) ? $i + 1 : $i }}
                                            </button>
                                            @if(!in_array($i,$arrPlaces)) <input type="checkbox" class="hidden" v-model="inputs" name="places[]" value="{{  $i }}" />@endif
                                        </span>
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td colspan="{{  $sr - 1 }}"></td>
                                    <td>
                                <span class="button-checkbox">
                                    <button type="button" class="btn btn-sm"
                                            v-bind:class="isSelected({{$places - 6}})" v-on:click="select({{$places - 6}})"
                                            :disabled="isDisabled({{$places - 6}}, {{ in_array($places - 6,$arrPlaces) ? true : false}})">{{  $places - 6 }}
                                    </button>
                                    <input type="checkbox" class="hidden" name="places[]" v-model="inputs" value="{{$places - 6 }}" />
                                </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="button-checkbox">
                                                <button type="button" class="btn btn-sm"
                                                        v-bind:class="isSelected({{$places - 2}})" v-on:click="select({{$places - 2}})"
                                                        :disabled="isDisabled({{$places - 2}}, {{ in_array($places - 2,$arrPlaces) ? true : false}})">{{ $places - 2 }}
                                              </button>
                                                <input type="checkbox" class="hidden" name="places[]" v-model="inputs" value="{{$places - 2 }}" />
                                        </span>
                                    </td>
                                    @for($i = 2; $i < $places - 4; $i+= 4)
                                        @if($num < $sr &&  $i == $places - 5)
                                            <td>
                                <span class="button-checkbox">
                                    <button type="button" class="btn btn-sm"
                                            v-bind:class="isSelected({{$i - 2}})" v-on:click="select({{$i - 2}})"
                                            :disabled="isDisabled({{$i - 2}}, {{ in_array($i-2, $arrPlaces) ? true : false}})">{{ $i - 2 }}
                                    </button>
                                    <input type="checkbox" class="hidden" name="places[]" v-model="inputs"  value="{{ $i - 2 }}" /></span>
                                            </td>
                                            @break
                                        @endif
                                        <td>
                                <span class="button-checkbox">
                                    <button type="button" class="btn btn-sm"
                                            v-bind:class="isSelected({{$i}})" v-on:click="select({{$i}})"
                                            :disabled="isDisabled({{$i}}, {{ in_array($i,$arrPlaces) ? true : false}})">{{ $i }}
                                    </button>
                                    <input type="checkbox" class="hidden" name="places[]" v-model="inputs"  value="{{ $i }}" />
                                </span>
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>
                                        <span class="button-checkbox">
                                            <button type="button" class="btn btn-sm"
                                                    v-bind:class="isSelected({{$places - 3}})" v-on:click="select({{$places - 3}})"
                                                    :disabled="isDisabled({{$places - 3}}, {{ in_array($places - 3, $arrPlaces) ? true : false}})">{{$places - 3}}
                                            </button>
                                            <input type="checkbox" class="hidden" v-model="inputs" name="places[]" value="{{$places - 3 }}" />
                                        </span>
                                    </td>
                                    @for($i = 1; $i < $places - 4; $i+=4)
                                    @if($num < $sr &&  $i == $places - 6)
                                    <td>
                                    <span class="button-checkbox">
                                        <button type="button" class="btn btn-sm"
                                                v-bind:class="isSelected({{$i - 2}})" v-on:click="select({{$i - 2}})"
                                                :disabled="isDisabled({{$i - 2}}, {{ in_array($i - 2, $arrPlaces) ? true : false}})">{{ $i - 2 }}
                                        </button>
                                        <input type="checkbox" class="hidden" v-model="inputs"  name="places[]" value="{{ $i - 2 }}" />
                                    </span>
                                    </td>
                                    @break;
                                    @endif
                                    <td>
                                    <span class="button-checkbox">
                                        <button type="button" class="btn btn-sm"
                                                v-bind:class="isSelected({{$i}})" v-on:click="select({{$i}})"
                                                :disabled="isDisabled({{$i}}, {{ in_array($i,$arrPlaces) ? true : false}})">{{ $i }}
                                        </button>
                                        <input type="checkbox" class="hidden" v-model="inputs"  name="places[]" value="{{$i}}" />
                                    </span>
                                    </td>
                                    @endfor
                                </tr>
                            </table>
                        </div>
                    </div>
                    <h3>Данные пассажиров</h3>
                    <div class="row">
                        <div class="col-md-8">
                            @for($i = 0; $i < $count; $i++)
                                <fieldset>
                                    <legend>Пассажир #{{$i+1}}:</legend>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-elements">
                                                <div class="form-group form-element-text {!! $errors->has("last_name.".$i) ? ' has-error' : '' !!}">
                                                    <label class="control-label" for="last_name">Фамилия <span class="form-element-required">*</span></label>
                                                    <input class="form-control" type="text" id="last_name" name="last_name[{{$i}}]" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group {!! $errors->has("name.".$i) ? ' has-error' : '' !!}">
                                                <label class="control-label" for="name">Имя <span class="form-element-required">*</span></label>
                                                <input class="form-control" type="text" id="name" name="name[{{$i}}]" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group {!! $errors->has("middle_name.".$i) ? ' has-error' : '' !!}">
                                                <label class="control-label" for="middle_name">Отчество <span class="form-element-required">*</span></label>
                                                <input class="form-control" type="text" id="middle_name" name="middle_name[{{$i}}]" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group {!! $errors->has("passport.".$i) ? ' has-error' : '' !!}">
                                                <label class="control-label" for="passport">№ Паспорта <span class="form-element-required">*</span></label>
                                                <input class="form-control" type="text" id="passport" name="passport[{{$i}}]" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <label><input class="children" type="checkbox" name="children[{{$i}}]" v-model="childrens[{{$i}}]" value="1">
                                                    <strong>Детский (%50)</strong></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="control-label">Есть багаж ? <span class="form-element-required">*</span></label>
                                            <select class="form-control mselect" name="baggage[{{$i}}][person]">
                                                <option selected disabled value="0">Выбрать</option>
                                                <option value="yes">Да</option>
                                                <option value="no">Нет</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label">Количество мест (сумок) <span class="form-element-required">*</span></label>
                                            <select class="form-control mselect2" name="baggage[{{$i}}][place]"
                                                    data-price="{{$data->price->first()->price}}"
                                                    data-sum="{{$data->price->first()->price * $count }}"
                                                    v-model="baggage_place[{{$i}}]"
                                                    v-on:change="bselect"
                                                    disabled>
                                                <option value="0" disabled selected>Выбрать</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="help-block">Одно место бесплатно, последующие 20% от стоимости проезда. багаж у водителя можно получить только при наличии квитанции (электронного билета), в котором сказано  количество багажа</p>
                                        </div>
                                    </div>

                                </fieldset>
                            @endfor
                            <legend>Покупатель</legend>
                            <div class="row">
                                <div class="form-group {!! $errors->has("phone") ? ' has-error' : '' !!}">
                                    <div class="col-md-3">
                                        <label class="control-label" for="phone">Телефон <span class="form-element-required">*</span></label>
                                        <input class="form-control" type="text" id="phone" name="phone" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p class="help-block">Нужен для связи с вами в случае переноса или отмены рейса, проблем с оплатой и прочих вопросов.</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group {!! $errors->has("email") ? ' has-error' : '' !!}">
                                    <div class="col-md-3">
                                        <label class="control-label" for="email">Email <span class="form-element-required">*</span></label>
                                        <input class="form-control" type="text" id="email" name="email" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p class="help-block">На данный адрес будет выслан Вам электронный билет</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group {!! $errors->has("payment_type") ? ' has-error' : '' !!}">
                                    <div class="col-md-4">
                                        <label class="control-label" for="payment_type">Способ оплаты <span class="form-element-required">*</span></label>
                                        <select class="form-control" name="payment_type" id="payment_type">
                                            <option value="0">Выбрать</option>
                                            @if($settings['type_paid'] == 'all' || $settings['type_paid'] == 'prepay')
                                            <option value="prepay">Оплата картой онлайн</option>
                                            @endif
                                            @if($settings['type_paid'] == 'all' || $settings['type_paid'] == 'cash')
                                            <option value="cash">Оплата наличными в кассе</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-element-checkbox">
                                <div class="checkbox">
                                    <label><input id="published" name="published" v-model="published"  value="1" type="checkbox">
                                        С <a href="{{url('/rules')}}">условиями</a> предоставления услуг согласен
                                    </label>
                                </div>
                            </div>
                            <div class="form-group form-element-checkbox">
                                <div class="checkbox">
                                    <label><input id="personal" name="personal" v-model="personal" value="1" type="checkbox">
                                        Я принимаю <a href="{{url('/public_offer')}}" target="_blank">условия публичной оферты</a> и <a href="{{url('/personal')}}" target="_blank">политики конфиденциальности.</a>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success" :disabled="!btn">Оформить</button>
                            </div>
                            <input type="hidden" name="departure" value="{{date('H:i',strtotime($data->stations->first()->pivot->departure))}}">
                            <input type="hidden" name="arrival" value="{{date('H:i',strtotime($data->stations->last()->pivot->arrival))}}">
                            {{csrf_field()}}

                        </div>
                        <div class="col-md-4">
                            <span class="detali_title">Детали заказа</span>
                            <div class="well">
                                <div class="point"><strong>Отправление:</strong>
                                    <p>{{$data->stations->first()->title}}</p>
                                    <i class="fa fa-clock-o"></i> {{date('H:i',strtotime($data->stations->first()->pivot->departure))}}&nbsp;&nbsp;
                                    <i class="fa fa-calendar"></i> {{date('d.m.Y', strtotime($date))}}
                                </div>
                                <div class="line"></div>
                                <div class="point"><strong>Прибытие:</strong>
                                    <p>{{$data->stations->last()->title}}</p>
                                    <i class="fa fa-clock-o"></i> {{date('H:i',strtotime($data->stations->last()->pivot->arrival))}}&nbsp;&nbsp;
                                    <i class="fa fa-calendar"></i> {{date('d.m.Y', strtotime($date))}}
                                </div>
                                <div class="line"></div>
                                <div class="fs22">Стоимость билетов:
                                    <input type="text" v-model="priceTickets" disabled>
                                </div>
                                <div class="fs22">Стоимость перевозки багажа:
                                    <input type="text" v-model="bsuma" disabled>
                                </div>
                                <div class="fs22"><strong>Общая стоимость:</strong>
                                    <input type="text" v-model="total" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>



@endsection

@section('scripts')

    <script src="{{asset('/js/vue2.js')}}"></script>
    <script>

        var k = 0;

        new Vue({
            el:'#app',
            data:{
                inputs:[],
                published:false,
                personal:false,
                places: {{$count}},
                ticketPrice: {{$data->price->first()->price }},
                baggage_place:[],
                bsuma:0,
                childrens:[]
            },

            computed: {
                btn: function () {
                    return this.published && this.personal
                },
                priceTickets: function(){
                    return (this.places * this.ticketPrice) - this.countChildrens() + ' RUB';
                },

                total:function(){
                    return (this.places * this.ticketPrice) - this.countChildrens() + this.bsuma + ' RUB';
                }
            },

            methods:{
                countChildrens:function(){
                    let k = 0;
                    for (var i = 0; i < this.childrens.length; i++){
                        if(this.childrens[i]){
                            k++;
                        }
                    }

                    if(k > 0 ){
                        return this.ticketPrice * 0.50 * k;
                    }else{
                        return 0;
                    }
                },

                bselect:function(){
                    let suma = 0;
                    for(var i= 0; i < this.baggage_place.length; i++){
                        if(this.baggage_place[i] > 1){
                            suma  += (this.ticketPrice * 0.20) * (this.baggage_place[i] - 1);
                        }
                    }

                    this.bsuma = suma;

                },

                select:function(index){
                    if(this.getIndex(index) == 0){
                        this.inputs.push(index);
                    }else{
                        this.getIndex(index);
                    }
                },

                isSelected:function(index){
                    return this.inputs.indexOf(index) != -1 ? 'btn-primary' : '';
                },

                isDisabled:function(index, flag){
                    return (this.inputs.length == this.places && this.inputs.indexOf(index) == -1) || flag == 1;
                },

                getIndex:function(value){
                    for(var i in this.inputs){
                        if(this.inputs[i] == value){
                            return this.inputs.splice(i, 1);
                        }
                    }
                    return 0;
                }
            }

        });

        $(document).on('change', '.mselect' ,function(){
            if($(this).val() == 'yes') {
                $(this).parent().next().find('select').removeAttr('disabled');
                var selected = $(this).parent().next().find('select');
                //updatePrice(selected.data('price'), selected.val(), selected.data('sum'));
            }else{
                $(this).parent().next().find('select').attr('disabled', 'disabled');
                $(this).parent().next().find('select').val('0').prop('selected', true);
                //$('.price').text(currentPrice + ' RUB');
            }

        });

        /*
        $(document).on('change', '.mselect2' ,function(){
            updatePrice($(this).data('price'), $(this).val(), $(this).data('sum'));
        });

        function updatePrice(price, places, sum){
            var s_suma = parseFloat($('.price').text());
            var price = (price * 0.20) * places;

            if(places > 1){
                k = s_suma + price;
                //$('.price').text(k + ' RUB');
            }else{
                //$('.price').text(suma - price + ' RUB');
            }

        }*/
    </script>
@endsection