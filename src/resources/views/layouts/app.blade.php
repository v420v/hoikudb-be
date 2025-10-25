<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', '保育DB 管理画面')</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
  <div class="flex h-screen">
    <!-- サイドバー -->
    <div class="w-64 bg-gray-800 text-white flex flex-col">
      <!-- ヘッダー -->
      <div class="p-6 border-b border-gray-700">
        <h1 class="text-xl font-bold">保育DB 管理画面</h1>
      </div>

      <!-- ナビゲーション -->
      <nav class="flex-1 p-4">
        <ul class="space-y-2">
          @auth
            <li>
              <a href="{{ route('preschool.index') }}"
                class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('preschool.index') ? 'bg-blue-600' : '' }}">
                保育園管理
              </a>
            </li>
            <li>
              <a href="{{ route('preschool.import') }}"
                class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('preschool.import') ? 'bg-blue-600' : '' }}">
                インポート
              </a>
            </li>
            <li>
              <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit"
                  class="flex items-center w-full px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors text-left">
                  ログアウト
                </button>
              </form>
            </li>
          @else
            <li>
              <a href="{{ route('login') }}"
                class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('login') ? 'bg-blue-600' : '' }}">
                ログイン
              </a>
            </li>
          @endauth
        </ul>
      </nav>

      <!-- フッター -->
      <div class="p-4 border-t border-gray-700">
        <p class="text-sm text-gray-400 text-center">© 2025 保育DB</p>
      </div>
    </div>

    <!-- メインコンテンツ -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- コンテンツエリア -->
      <main class="flex-1 overflow-y-auto bg-gray-50">
        <div class="p-6">
          @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
              {{ session('success') }}
            </div>
          @endif

          @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
              {{ session('error') }}
            </div>
          @endif

          @yield('content')
        </div>
      </main>
    </div>
  </div>
</body>

</html>
