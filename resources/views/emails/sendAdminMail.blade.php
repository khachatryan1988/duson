<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
  <p>{{ __('order_success') }}</p>
  <div class="bg-yellow-100 p-6 rounded-lg shadow-lg text-center relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-r from-yellow-300 to-yellow-500 opacity-20 rounded-lg"></div>


      <h1><a href="{{asset('Հետվերադարձի պայմաններ.pdf')}}">Հետվերադարձի պայմաններ</a></h1>

{{--      <h1 class="relative z-10 mt-4 mb-6 text-4xl font-extrabold tracking-tight text-yellow-800">--}}
{{--          🎉 <strong>ՇՆՈՐՀԱՎՈՐՈՒՄ ԵՆՔ</strong> 🎉--}}
{{--      </h1>--}}

{{--      <p class="relative z-10 mt-4 mb-4 text-xl font-semibold text-gray-900">--}}
{{--          Դուք մասնակցում եք <span class="text-yellow-600">DUSON-ի</span> խաղարկությանը։--}}
{{--      </p>--}}

{{--      <p class="relative z-10 mt-4 mb-4 text-lg text-gray-800">--}}
{{--          Մանրամասներին կարող եք ծանոթանալ DUSON-ի պաշտոնական էջերում՝--}}
{{--      </p>--}}

{{--      <p class="relative z-10 mt-4 mb-2 text-lg font-bold text-gray-900">--}}
{{--          📸 Instagram (duson_armenia) ---}}
{{--          <a href="https://www.instagram.com/duson_armenia/" target="_blank" class="text-blue-600 underline">https://www.instagram.com/duson_armenia/</a>--}}
{{--      </p>--}}

{{--      <p class="relative z-10 mb-2 text-lg font-bold text-gray-900">--}}
{{--          👍 Facebook (duson) ---}}
{{--          <a href="https://www.facebook.com/duson.mattresses" target="_blank" class="text-blue-600 underline">https://www.facebook.com/duson.mattresses</a>--}}
{{--      </p>--}}

{{--      <p class="relative z-10 mb-2 text-lg font-bold text-gray-900">--}}
{{--          📲 Telegram (duson) ---}}
{{--          <a href="https://t.me/dusonmattress" target="_blank" class="text-blue-600 underline">https://t.me/dusonmattress</a>--}}
{{--      </p>--}}
{{--      <div class="absolute -top-4 -right-4 z-0 opacity-75 animate-confetti">--}}
{{--          <div class="w-2 h-2 bg-pink-500 rounded-full"></div>--}}
{{--          <div class="w-3 h-3 bg-green-400 rounded-full"></div>--}}
{{--          <div class="w-2 h-2 bg-purple-500 rounded-full"></div>--}}
{{--          <div class="w-3 h-3 bg-blue-400 rounded-full"></div>--}}
{{--          <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>--}}
{{--      </div>--}}
{{--  </div>--}}
{{--  <style>--}}
{{--      .animate-confetti > div {--}}
{{--          position: absolute;--}}
{{--          animation: fall 3s infinite ease-in-out;--}}
{{--      }--}}
{{--      @keyframes fall {--}}
{{--          0% { transform: translateY(0) rotate(0); opacity: 1; }--}}
{{--          100% { transform: translateY(300px) rotate(360deg); opacity: 0; }--}}
{{--      }--}}
{{--  </style>--}}
<a href="{{route('profile.order',['order' => $invoiceNo])}}" class="flex items-center justify-center rounded-md border border-gray-300 bg-white px-2.5 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
    <span>{{trans('Դիտել պատվերը')}}</span>
</a>
</body>
</html>
