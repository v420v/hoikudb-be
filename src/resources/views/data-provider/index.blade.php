@extends('layouts.app')

@section('title', 'データプロバイダー')

@section('content')
@use('App\Models\DataProviderFileConfig')
<div class="min-h-screen bg-gray-50 py-6">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- ヘッダー -->
    <div class="bg-white shadow-sm rounded-lg mb-6">
      <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900">データプロバイダー</h3>
        <a href="{{ route('data-provider.create') }}"
          class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          新規作成
        </a>
      </div>
    </div>

    <!-- データプロバイダー -->
    <div class="space-y-6">
      @foreach ($dataProviders as $dataProvider)
        <div class="bg-white shadow-sm rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
              <div>
                <h4 class="text-lg font-medium text-gray-900">{{ $dataProvider->name }}</h4>
                <p class="text-sm text-gray-500 mt-1">
                  登録日時: {{ $dataProvider->created_at->format('Y/m/d H:i') }}
                </p>
              </div>
              <div class="flex space-x-2">
                <a href="{{ route('data-provider.edit', $dataProvider->id) }}"
                  class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                  編集
                </a>
                <form method="POST" action="{{ route('data-provider.destroy', $dataProvider->id) }}" class="inline"
                  onsubmit="return confirm('{{ config('messages.confirm.delete') }}');">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                    class="inline-flex items-center px-3 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                    削除
                  </button>
                </form>
              </div>
            </div>
          </div>

          <!-- ファイル設定一覧 -->
          <div class="px-6 py-4">
            <div class="flex justify-between items-center mb-4">
              <h5 class="text-md font-medium text-gray-700">ファイル設定</h5>
              <a href="{{ route('data-provider.file-config.create', $dataProvider->id) }}"
                class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                ファイル設定を追加
              </a>
            </div>

            @if ($dataProvider->fileConfigs->count() > 0)
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">表示名</th>
                      <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ファイルタイプ</th>
                      <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">エンコーディング</th>
                      <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">区切り文字</th>
                      <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">囲み文字</th>
                      <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($dataProvider->fileConfigs as $fileConfig)
                      <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $fileConfig->display_name }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                          <span
                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $fileConfig->file_type === DataProviderFileConfig::FILE_TYPE_CSV ? 'CSV' : 'PDF' }}
                          </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ strtoupper($fileConfig->encoding) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 font-mono">{{ $fileConfig->delimiter }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 font-mono">{{ $fileConfig->enclosure }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                          <div class="flex space-x-2">
                            <a href="{{ route('data-provider.file-config.edit', [$dataProvider->id, $fileConfig->id]) }}"
                              class="text-blue-600 hover:text-blue-900">編集</a>
                            <form method="POST"
                              action="{{ route('data-provider.file-config.destroy', [$dataProvider->id, $fileConfig->id]) }}"
                              class="inline" onsubmit="return confirm('{{ config("messages.confirm.delete") }}');">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="text-red-600 hover:text-red-900">削除</button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <p class="text-sm text-gray-500">ファイル設定が登録されていません。</p>
            @endif
          </div>
        </div>
      @endforeach

      @if ($dataProviders->count() === 0)
        <div class="bg-white shadow-sm rounded-lg p-6 text-center">
          <p class="text-gray-500">データプロバイダーが登録されていません。</p>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

