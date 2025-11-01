@extends('layouts.app')

@section('title', '保育園')

@section('content')
  @use('App\Models\Preschool')
  <div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- 検索フィルターエリア -->
      <div class="bg-white shadow-sm rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-medium text-gray-900">検索条件</h3>
        </div>
        <div class="p-6">
          <form method="GET" action="{{ route('preschool.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
              <!-- ステータス -->
              <div>
                <label class="block text-sm font-medium text-gray-700">ステータス</label>
                <select name="status"
                  class="mt-1 block w-full px-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-50">
                  <option value="">すべて</option>
                  <option value="{{ Preschool::STATUS_ACTIVE }}"
                    {{ request()->status === Preschool::STATUS_ACTIVE ? 'selected' : '' }}>アクティブ</option>
                  <option value="{{ Preschool::STATUS_INACTIVE }}"
                    {{ request()->status === Preschool::STATUS_INACTIVE ? 'selected' : '' }}>非アクティブ</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">保育園名</label>
                <input type="text" name="name" value="{{ request()->name }}"
                  class="mt-1 block w-full px-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-50"
                  placeholder="保育園名を入力">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">建物コード</label>
                <input type="text" name="building_code" value="{{ request()->building_code }}"
                  class="mt-1 block w-full px-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-50"
                  placeholder="建物コードを入力">
              </div>
            </div>

            <!-- ボタンエリア -->
            <div class="flex justify-end space-x-3">
              <button type="submit"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                検索
              </button>
              <a href="{{ route('preschool.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                クリア
              </a>
            </div>
          </form>
        </div>
      </div>

      <div class="bg-white shadow-sm rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
          <div class="flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">保育園一覧</h3>
            <span class="text-sm text-gray-500">全 {{ number_format($total_count) }} 件</span>
          </div>
        </div>
        <div class="pt-6 pl-6 pr-6">
          {{ $preschools->links() }}
        </div>
        <div class="p-6">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ステータス</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">園名</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">建物コード</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">登録日時</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($preschools as $preschool)
                  <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span
                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-{{ $preschool->status === Preschool::STATUS_ACTIVE ? 'green' : 'red' }}-100 text-{{ $preschool->status === Preschool::STATUS_ACTIVE ? 'green' : 'red' }}-800">
                        {{ $preschool->status === Preschool::STATUS_ACTIVE ? 'アクティブ' : '非アクティブ' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $preschool->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $preschool->building_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ $preschool->created_at->format('Y/m/d H:i') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ページネーション -->
      <div class="mt-6">
        {{ $preschools->links() }}
      </div>
    </div>
  </div>
@endsection
