@extends('layout.app')

@section('content')
    @if($count == 0)
        <div class="container mx-auto">
            <div class="bg-white">
                <div class="mx-auto max-w-4xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
                    <div class="mx-auto max-w-xl overflow-hidden bg-white shadow-md outline outline-1 outline-gray-100 sm:rounded-lg">
                        <div class="px-4 sm:p-6 text-center">
                            <h2 class="text-xl font-bold tracking-tight text-gray-900">{{trans('Ձեր զամբյուղը դատարկ է')}}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-gray-100" x-data="{
            state: {{!empty($form['state']) ? $form['state'] : 11}},
            city: {{!empty($form['city']) ? $form['city'] : 0}},
            km: {},
            totals: totals(),
            payment: 1
        }" x-init="totals.calculateShipping(city, state)">
            <div class="container mx-auto">
                <div class="mx-auto max-w-2xl px-4 pb-24 pt-16 sm:px-6 lg:max-w-7xl lg:px-8">
                    <h2 class="sr-only">{{trans('Վճարում')}}</h2>

                    <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
                        <form action="{{route('confirm-checkout')}}" method="post" id="checkout">
                            @csrf
                            <input type="hidden" name="payment" :value="payment" />
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">{{trans('Կոնտակտային տվյալներ')}}</h2>

                                <div class="rounded-lg border border-gray-200 bg-white shadow-sm p-6 mt-4">
                                    <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                        <div>
                                            <label for="first_name" class="block text-sm font-medium text-gray-700">{{trans('Name')}}</label>
                                            <div class="mt-1">
                                                <input type="text" value="{{$form['first_name'] ?? ''}}" id="first_name" name="first_name" autocomplete="given-name" class="block w-full rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm {{$errors->has('first_name') ? 'border-red-500' : 'border-gray-300'}}">
                                                @if($errors->has('first_name'))
                                                    <div class="text-sm text-red-500">{{ $errors->first('first_name') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div>
                                            <label for="last_name" class="block text-sm font-medium text-gray-700">{{trans('Surname')}}</label>
                                            <div class="mt-1">
                                                <input type="text" value="{{$form['last_name'] ?? ''}}" id="last_name" name="last_name" autocomplete="family-name" class="block w-full rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm {{$errors->has('last_name') ? 'border-red-500' : 'border-gray-300'}}">
                                                @if($errors->has('last_name'))
                                                    <div class="text-sm text-red-500">{{ $errors->first('last_name') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div>
                                            <label for="email" class="block text-sm font-medium text-gray-700">{{trans('Էլ․ Փոստ')}}</label>
                                            <div class="mt-1">
                                                <input type="text" value="{{$form['email'] ?? ''}}" name="email" id="email" autocomplete="tel" class="block w-full rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm {{$errors->has('email') ? 'border-red-500' : 'border-gray-300'}}">
                                                @if($errors->has('email'))
                                                    <div class="text-sm text-red-500">{{ $errors->first('email') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div>
                                            <label for="phone" class="block text-sm font-medium text-gray-700">{{trans('Հեռախոսահամար')}}</label>
                                            <div class="mt-1">
                                                <input type="text" value="{{$form['phone'] ?? ''}}" name="phone" id="phone" autocomplete="tel" class="block w-full rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm {{$errors->has('phone') ? 'border-red-500' : 'border-gray-300'}}">
                                                @if($errors->has('phone'))
                                                    <div class="text-sm text-red-500">{{ $errors->first('phone') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <label for="phone1" class="block text-sm font-medium text-gray-700">{{trans('Լրացուցիչ հեռախոսահամար')}}</label>
                                            <div class="mt-1">
                                                <input type="text" value="{{$form['phone1'] ?? ''}}" name="phone1" id="phone1" autocomplete="tel" class="block w-full rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm {{$errors->has('phone1') ? 'border-red-500' : 'border-gray-300'}}">
                                                @if($errors->has('phone1'))
                                                    <div class="text-sm text-red-500">{{ $errors->first('phone1') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h2 class="text-lg font-medium text-gray-900 mt-4">{{trans('Առաքման հասցե')}}</h2>
                                <div class="rounded-lg border border-gray-200 bg-white shadow-sm p-6 mt-4">
                                    <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                        <div>
                                            <label for="state" class="block text-sm font-medium text-gray-700">{{trans('Մարզ')}}</label>
                                            <div class="mt-1">
                                                <select name="state" x-on:change="city=0;totals.calculateShipping(city, state);" x-model="state" id="state" class="block w-full rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm {{$errors->has('state') ? 'border-red-500' : 'border-gray-300'}}">
                                                    @foreach($states as $state)
                                                        <option value="{{$state->id}}">{{$state->title}}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('state'))
                                                    <div class="text-sm text-red-500">{{ $errors->first('state') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <label for="city" class="block text-sm font-medium text-gray-700">{{trans('Քաղաք / Գյուղ')}}</label>
                                            <div class="mt-1">
                                                <select name="city" id="city" x-on:change="totals.calculateShipping(city, state);" x-model="city" class="block w-full rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm {{$errors->has('city') ? 'border-red-500' : 'border-gray-300'}}">
                                                    <option value="0" disabled>{{trans('Ընտրել')}}</option>
                                                    @foreach($cities as $city)
                                                        <option value="{{$city->id}}" x-init="km[{{$city->id}}]={{$city->km}}" x-show="state=={{$city->state_id}}">{{$city->title}}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('city'))
                                                    <div class="text-sm text-red-500">{{ $errors->first('city') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">

                                            <label for="street" class="block text-sm font-medium text-gray-700">{{trans('Փողոց')}}</label>
                                            <div class="mt-1">
                                                <input type="text" value="{{$form['street'] ?? ''}}" name="street" id="street" autocomplete="street-address" class="block w-full rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm {{$errors->has('street') ? 'border-red-500' : 'border-gray-300'}}">
                                                @if($errors->has('street'))
                                                    <div class="text-sm text-red-500">{{ $errors->first('street') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label for="home" class="block text-sm font-medium text-gray-700">{{trans('Տուն / Բնակարան')}}</label>
                                            <div class="mt-1">
                                                <input type="text" value="{{$form['home'] ?? ''}}" name="home" id="home" class="block w-full rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm {{$errors->has('home') ? 'border-red-500' : 'border-gray-300'}}">
                                                @if($errors->has('home'))
                                                    <div class="text-sm text-red-500">{{ $errors->first('home') }}</div>
                                                @endif
                                            </div>
                                        </div>




                                    </div>
                                </div>

                                <h2 class="text-lg font-medium text-gray-900 mt-4">{{trans('Նշումներ')}}</h2>
                                <div class="rounded-lg border border-gray-200 bg-white shadow-sm p-6 mt-4">
                                    <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                        <div class="sm:col-span-2">
                                            <label for="notes" class="block text-sm font-medium text-gray-700">{{trans('Այլ տեղեկություններ')}}</label>
                                            <div class="mt-1">
                                                <textarea name="notes" id="notes" autocomplete="notes" class="max-h-[90px] min-h-[90px] resize-none block w-full rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm {{$errors->has('notes') ? 'border-red-500' : 'border-gray-300'}}">{{$form['notes'] ?? ''}}</textarea>
                                                @if($errors->has('notes'))
                                                    <div class="text-sm text-red-500">{{ $errors->first('notes') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>

                        <!-- Order summary -->
                        <div class="mt-10 lg:mt-0">
                            <h2 class="text-lg font-medium text-gray-900">{{trans('Պատվերի տվյալներ')}}</h2>

                            <div class="mt-4 rounded-lg border border-gray-200 bg-white shadow-sm">
                                <h3 class="sr-only">Items in your cart</h3>
                                <ul role="list" class="divide-y divide-gray-200">
                                    @foreach($items as $item)
                                        <li class="flex px-4 py-6 sm:px-6">
                                        <div class="flex-shrink-0">
                                            <img src="{{$item->getOptions()['image']}}" alt="Front of men&#039;s Basic Tee in black." class="w-20 rounded-md">
                                        </div>

                                        <div class="ml-6 flex flex-1 flex-col">
                                            <div class="flex">
                                                <div class="min-w-0 flex-1">
                                                    <h4 class="text-sm">
                                                        <a href="#" class="font-medium text-gray-700 hover:text-gray-800">{{$item->getTitle()}}</a>
                                                    </h4>
                                                </div>

                                                <div class="ml-4 flow-root flex-shrink-0">
                                                    <form action="{{route('remove-cart-item')}}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{$item->getHash()}}">
                                                        <button class="text-gray-400 hover:text-gray-500">
                                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="flex flex-1 items-end justify-between pt-2">
                                                <div class="mt-1 text-sm">
                                                    <label for="quantity">{{trans('Քանակ՝')}} {{$item->getQuantity()}}</label>
                                                </div>
                                                <div class="mt-1 text-sm">{{trans('Արժեք՝')}} {{$item->getOptions()['amount']}}</div>

                                            </div>
                                        </div>
                                    </li>
                                    @endforeach

                                    <!-- More products... -->
                                </ul>
                                <dl class="space-y-6 border-t border-gray-200 px-4 py-6 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <dt class="text-sm">{{trans('Արժեք՝')}}</dt>
                                        <dd class="text-sm font-medium text-gray-900" x-text="totals.subtotal"></dd>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <dt class="text-sm">{{trans('Առաքման արժեք')}}</dt>
                                        <dd class="text-sm font-medium text-gray-900" x-text="totals.shipping"></dd>
                                    </div>
                                    <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                                        <dt class="text-base font-medium">{{trans('Ընդհանուր')}}</dt>
                                        <dd class="text-base font-medium text-gray-900" x-text="totals.total"></dd>
                                    </div>
                                </dl>


                                <div class=" border-t border-gray-200 p-6">
                                    <fieldset>
                                        <legend class="text-lg font-medium text-gray-900">{{trans('Վճարման եղանակ')}}</legend>

                                        <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-1 sm:gap-x-4">
                                            <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none" x-on:click="payment=1">
                                                <input type="radio" name="payment" checked value="1" class="sr-only" aria-labelledby="delivery-method-0-label" aria-describedby="delivery-method-0-description-0 delivery-method-0-description-1">
                                                <span class="flex flex-1">
                                                  <span>
                                                    <span id="delivery-method-0-label" class="block text-sm font-medium text-gray-900">{{trans('Վճարել քարտով')}}</span>
                                                      <img src="{{asset('images/logo_inecobank.png')}}" alt="" class="h-6 w-auto my-2">
                                                    <span id="delivery-method-0-description-0" class="mt-1 flex items-center text-sm text-gray-500">{{trans('card_description')}}</span>
                                                  </span>
                                                </span>
                                                <!-- Not Checked: "hidden" -->
                                                <svg class="h-5 w-5 text-yellow-400 hidden" :class="payment===1 ? '!block' : ''" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                                </svg>
                                                <!--
                                                  Active: "border", Not Active: "border-2"
                                                  Checked: "border-yellow-500", Not Checked: "border-transparent"
                                                -->
                                                <span class="pointer-events-none absolute -inset-px rounded-lg border-2 " :class="payment===1 ? 'border-yellow-400' : ''" aria-hidden="true"></span>
                                            </label>

{{--                                            <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none" x-on:click="payment=2">--}}
{{--                                                <input type="radio" name="payment" value="2" class="sr-only" aria-labelledby="delivery-method-0-label" aria-describedby="delivery-method-0-description-0 delivery-method-0-description-1">--}}
{{--                                                <span class="flex flex-1">--}}
{{--                                                  <span>--}}
{{--                                                    <span id="delivery-method-0-label" class="block text-sm font-medium text-gray-900">{{trans('Վճարել Իդրամ համակարգով')}}</span>--}}
{{--                                                      <img src="{{asset('images/idram.png')}}" alt="" class="h-6 w-auto my-2">--}}
{{--                                                    <span id="delivery-method-0-description-0" class="mt-1 flex items-center text-sm text-gray-500">{{trans('idram_description')}}</span>--}}
{{--                                                  </span>--}}
{{--                                                </span>--}}
                                                <!-- Not Checked: "hidden" -->
                                                <svg class="h-5 w-5 text-yellow-400 hidden" :class="payment===2 ? '!block' : ''" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                                </svg>
                                                <!--
                                                  Active: "border", Not Active: "border-2"
                                                  Checked: "border-yellow-500", Not Checked: "border-transparent"
                                                -->
                                                <span class="pointer-events-none absolute -inset-px rounded-lg border-2" :class="payment===2 ? 'border-yellow-400' : ''" aria-hidden="true"></span>
                                            </label>
                                        </div>
                                    </fieldset>
                                </div>


                                <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                                    <x-primary-button class="w-full" onclick="document.getElementById('checkout').submit()">
                                        {{trans('Հաստատել պատվերը')}}
                                    </x-primary-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <script>
            function totals(){
                return {
                    subtotal: 0,
                    shipping: 0,
                    total: 0,
                    calculateShipping(city, state) {
                        axios.post( '{{route('get-totals')}}', {
                            city: city,
                            state: state
                        })
                            .then( (r)=>{
                                console.log(r.data);
                                this.subtotal = r.data.subtotal
                                this.shipping = r.data.shipping
                                this.total = r.data.total
                            }).catch( (e)=>{
                            console.log(e);
                        })
                    }
                }
            }
            function dataD(){
                return {
                    dato1: '',
                    axiosResponse: '',
                    ok: false,

                    inviaValoriCheckBox(val, servizio){

                    },
                    mounted(){
                        console.log('mounted');
                    }
                }
            }

        </script>

    @endif
@endsection
