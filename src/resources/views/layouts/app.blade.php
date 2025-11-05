<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', '管理画面')</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
  <div class="flex h-screen">
    <!-- サイドバー -->
    <div class="w-64 bg-gray-800 text-white flex flex-col">
      <!-- ヘッダー -->
      <div class="p-6 border-b border-gray-700">
        <h1 class="text-xl font-bold relative inline-block">管理画面
          <span class="absolute -top-1 -right-10 bg-blue-500 text-white text-xs font-semibold px-1.5 py-0.5 rounded">beta</span>
        </h1>
      </div>

      <!-- ナビゲーション -->
      <nav class="flex-1 p-4">
        <ul class="space-y-2">
          @auth
            <li>
              <a href="{{ route('preschool.index') }}"
                class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('preschool.index') ? 'bg-blue-600' : 'hover:bg-gray-700 transition-colors' }}">
                保育園
              </a>
            </li>
            <li>
              <a href="{{ route('preschool.import') }}"
                class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('preschool.import') ? 'bg-blue-600' : 'hover:bg-gray-700 transition-colors' }}">
                インポート
              </a>
            </li>
            <li>
              <a href="{{ route('data-provider.index') }}"
                class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('data-provider.*') ? 'bg-blue-600' : 'hover:bg-gray-700 transition-colors' }}">
                データプロバイダー
              </a>
            </li>
          @else
            <li>
              <a href="{{ route('login') }}"
                class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('login') ? 'bg-blue-600' : 'hover:bg-gray-700 transition-colors' }}">
                ログイン
              </a>
            </li>
          @endauth
        </ul>
      </nav>

      <!-- フッター -->
      <div class="p-4 border-t border-gray-700">
        @auth
          <form method="POST" action="{{ route('logout') }}" id="logout-form" class="w-full">
            @csrf
            <button type="submit" id="logout-button"
              class="w-full px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg transition-colors text-center">
              ログアウト
            </button>
          </form>
        @endauth
      </div>
    </div>

    <!-- メインコンテンツ -->
    <div class="flex-1 flex flex-col overflow-hidden relative">
      <!-- フラッシュメッセージ通知エリア -->
      <div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2">
        @if (session('success'))
          <div id="success-notification" class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 min-w-[300px] max-w-[500px] animate-slide-in">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-white hover:text-gray-200">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
              </svg>
            </button>
          </div>
        @endif

        @if (session('error'))
          <div id="error-notification" class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 min-w-[300px] max-w-[500px] animate-slide-in">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-white hover:text-gray-200">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
              </svg>
            </button>
          </div>
        @endif
      </div>

      <!-- コンテンツエリア -->
      <main class="flex-1 overflow-y-auto bg-gray-50">
        <div class="p-6">
          @yield('content')
        </div>
      </main>
    </div>
  </div>

  <style>
    @keyframes slide-in {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes slide-out {
      from {
        transform: translateX(0);
        opacity: 1;
      }
      to {
        transform: translateX(100%);
        opacity: 0;
      }
    }

    .animate-slide-in {
      animation: slide-in 0.3s ease-out;
    }

    .animate-slide-out {
      animation: slide-out 0.3s ease-out;
    }
  </style>

  <script>
    // フラッシュメッセージの自動削除
    document.addEventListener('DOMContentLoaded', function() {
      const successNotification = document.getElementById('success-notification');
      const errorNotification = document.getElementById('error-notification');

      function removeNotification(notification) {
        if (notification) {
          notification.classList.add('animate-slide-out');
          setTimeout(() => {
            notification.remove();
          }, 300);
        }
      }

      // 成功メッセージを5秒後に自動削除
      if (successNotification) {
        setTimeout(() => {
          removeNotification(successNotification);
        }, 5000);
      }

      // エラーメッセージを7秒後に自動削除（エラーは少し長めに表示）
      if (errorNotification) {
        setTimeout(() => {
          removeNotification(errorNotification);
        }, 7000);
      }
    });

    // ログアウト確認ダイアログ
    document.addEventListener('DOMContentLoaded', function() {
      const logoutForm = document.getElementById('logout-form');
      const logoutButton = document.getElementById('logout-button');

      if (logoutButton && logoutForm) {
        logoutButton.addEventListener('click', function(e) {
          e.preventDefault();
          if (confirm('{{ config("messages.confirm.logout") }}')) {
            logoutForm.submit();
          }
        });
      }
    });
  </script>
</body>

</html>
