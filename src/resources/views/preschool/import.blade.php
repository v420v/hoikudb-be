@extends('layouts.app')

@section('title', 'インポート')

@section('content')
  @use('App\Models\CsvImportHistory')

  <div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white shadow-sm rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-medium text-gray-900">インポート</h3>
        </div>
        <div class="p-6">
          <form action="{{ route('preschool.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <!--- 種別選択 -->
            <div class="mb-6">
              <label for="kind" class="block text-sm font-medium text-gray-700 mb-2">
                種別
              </label>
              <select id="kind" name="kind" class="w-full p-2 border border-gray-300 rounded-md bg-gray-50">
                <option value="{{ CsvImportHistory::KIND_WAITING }}">{{ CsvImportHistory::KIND_JA[CsvImportHistory::KIND_WAITING] }}</option>
                <option value="{{ CsvImportHistory::KIND_ACCEPTANCE }}">{{ CsvImportHistory::KIND_JA[CsvImportHistory::KIND_ACCEPTANCE] }}</option>
                <option value="{{ CsvImportHistory::KIND_CHILDREN }}">{{ CsvImportHistory::KIND_JA[CsvImportHistory::KIND_CHILDREN] }}</option>
              </select>
            </div>
            <div class="mb-6">
              <label for="csv-file" class="block text-sm font-medium text-gray-700 mb-2">
                CSVファイル
              </label>
              <div class="flex items-center">
                <label
                  class="flex flex-col items-center w-full px-4 py-6 bg-white text-blue-600 rounded-lg shadow-sm border border-blue-300 border-dashed cursor-pointer hover:bg-blue-50 hover:border-blue-400 transition">
                  <svg class="w-8 h-8 mb-2 text-blue-400" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M7 16V4a2 2 0 012-2h6a2 2 0 012 2v12m-5-4v6m0 0l-2-2m2 2l2-2" />
                  </svg>
                  <span class="text-sm" id="file-label">ファイルを選択またはここにドラッグ＆ドロップ</span>
                  <input id="csv-file" name="csv" type="file" accept=".csv" class="hidden" required>
                </label>
              </div>
              <div id="selected-file" class="mt-2 text-sm text-gray-600 hidden">
                <div class="flex items-center justify-between bg-gray-50 rounded-md px-3 py-2">
                  <div class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">選択されたファイル: </span>
                    <span id="file-name" class="ml-1"></span>
                  </div>
                  <button type="button" id="remove-file" class="text-red-500 hover:text-red-700 focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
            <button type="submit"
              class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
              インポート
            </button>
          </form>
          <script>
            // ドラッグ&ドロップ用のUX改善（オプショナル）
            document.addEventListener('DOMContentLoaded', function() {
              const fileInput = document.getElementById('csv-file');
              const label = fileInput.parentElement;
              const fileLabel = document.getElementById('file-label');
              const selectedFileDiv = document.getElementById('selected-file');
              const fileNameSpan = document.getElementById('file-name');
              const removeFileBtn = document.getElementById('remove-file');

              // ファイル選択時の処理
              fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                  const fileName = this.files[0].name;
                  fileNameSpan.textContent = fileName;
                  selectedFileDiv.classList.remove('hidden');
                  fileLabel.textContent = 'ファイルが選択されました';
                } else {
                  selectedFileDiv.classList.add('hidden');
                  fileLabel.textContent = 'ファイルを選択またはここにドラッグ＆ドロップ';
                }
              });

              // ファイル削除ボタンの処理
              removeFileBtn.addEventListener('click', function() {
                fileInput.value = '';
                selectedFileDiv.classList.add('hidden');
                fileLabel.textContent = 'ファイルを選択またはここにドラッグ＆ドロップ';
              });

              label.addEventListener('dragover', e => {
                e.preventDefault();
                label.classList.add('bg-blue-100', 'border-blue-500');
              });
              label.addEventListener('dragleave', e => {
                e.preventDefault();
                label.classList.remove('bg-blue-100', 'border-blue-500');
              });
              label.addEventListener('drop', e => {
                e.preventDefault();
                label.classList.remove('bg-blue-100', 'border-blue-500');
                fileInput.files = e.dataTransfer.files;
                
                // ドロップ時もファイル名を表示
                if (fileInput.files && fileInput.files[0]) {
                  const fileName = fileInput.files[0].name;
                  fileNameSpan.textContent = fileName;
                  selectedFileDiv.classList.remove('hidden');
                  fileLabel.textContent = 'ファイルが選択されました';
                }
              });

              label.addEventListener('click', () => {
                fileInput.click();
              });
            });
          </script>
        </div>
      </div>
      
      <!-- インポート履歴セクション -->
      <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
          <div class="flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">インポート履歴</h3>
            <span class="text-sm text-gray-500">全 {{ number_format($total_count) }} 件</span>
          </div>
        </div>
        <div class="p-6">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ファイル名</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">種別</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">作成日時</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach ($csvImportHistories as $csvImportHistory)
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <a class="text-blue-600 hover:text-blue-800" href="{{ route('preschool.import.history', $csvImportHistory->id) }}">{{ $csvImportHistory->file_name }}</a>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $csvImportHistory->kind_ja }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $csvImportHistory->created_at->format('Y-m-d H:i:s') }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="mt-4">
            {{ $csvImportHistories->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
