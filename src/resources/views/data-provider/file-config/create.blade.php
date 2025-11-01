@extends('layouts.app')

@section('title', 'ファイル設定作成')

@section('content')
@use('App\Models\DataProviderFileConfig')
<div class="min-h-screen bg-gray-50 py-6">
  <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-sm rounded-lg">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">ファイル設定作成</h3>
        <p class="text-sm text-gray-500 mt-1">データプロバイダー: {{ $dataProvider->name }}</p>
      </div>

      <form method="POST" action="{{ route('data-provider.file-config.store', $dataProvider->id) }}" class="p-6">
        @csrf

        <div class="space-y-4">
          <div>
            <label for="display_name" class="block text-sm font-medium text-gray-700">表示名</label>
            <input type="text" name="display_name" id="display_name" value="{{ old('display_name') }}" required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('display_name') border-red-300 @enderror">
            @error('display_name')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="file_type" class="block text-sm font-medium text-gray-700">ファイルタイプ</label>
            <select name="file_type" id="file_type" required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('file_type') border-red-300 @enderror">
              <option value="">選択してください</option>
              <option value="{{ DataProviderFileConfig::FILE_TYPE_CSV }}" {{ old('file_type') === DataProviderFileConfig::FILE_TYPE_CSV ? 'selected' : '' }}>CSV</option>
              <option value="{{ DataProviderFileConfig::FILE_TYPE_PDF }}" {{ old('file_type') === DataProviderFileConfig::FILE_TYPE_PDF ? 'selected' : '' }}>PDF</option>
            </select>
            @error('file_type')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="new_line" class="block text-sm font-medium text-gray-700">改行コード</label>
            <select name="new_line" id="new_line" required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('new_line') border-red-300 @enderror">
              <option value="">選択してください</option>
              <option value="{{ DataProviderFileConfig::NEW_LINE_LF }}" {{ old('new_line') === DataProviderFileConfig::NEW_LINE_LF ? 'selected' : '' }}>LF (\n)</option>
              <option value="{{ DataProviderFileConfig::NEW_LINE_CRLF }}" {{ old('new_line') === DataProviderFileConfig::NEW_LINE_CRLF ? 'selected' : '' }}>CRLF (\r\n)</option>
            </select>
            @error('new_line')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="encoding" class="block text-sm font-medium text-gray-700">エンコーディング</label>
            <select name="encoding" id="encoding" required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('encoding') border-red-300 @enderror">
              <option value="">選択してください</option>
              <option value="{{ DataProviderFileConfig::ENCODING_SJIS }}" {{ old('encoding') === DataProviderFileConfig::ENCODING_SJIS ? 'selected' : '' }}>Shift-JIS</option>
              <option value="{{ DataProviderFileConfig::ENCODING_UTF_8 }}" {{ old('encoding') === DataProviderFileConfig::ENCODING_UTF_8 ? 'selected' : '' }}>UTF-8</option>
            </select>
            @error('encoding')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="delimiter" class="block text-sm font-medium text-gray-700">区切り文字</label>
            <select name="delimiter" id="delimiter" required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('delimiter') border-red-300 @enderror">
              <option value="">選択してください</option>
              <option value="{{ DataProviderFileConfig::DELIMITER_COMMA }}" {{ old('delimiter') === DataProviderFileConfig::DELIMITER_COMMA ? 'selected' : '' }}>カンマ (,)</option>
              <option value="{{ DataProviderFileConfig::DELIMITER_TAB }}" {{ old('delimiter') === DataProviderFileConfig::DELIMITER_TAB ? 'selected' : '' }}>タブ (\t)</option>
              <option value="{{ DataProviderFileConfig::DELIMITER_SEMICOLON }}" {{ old('delimiter') === DataProviderFileConfig::DELIMITER_SEMICOLON ? 'selected' : '' }}>セミコロン (;)</option>
              <option value="{{ DataProviderFileConfig::DELIMITER_COLON }}" {{ old('delimiter') === DataProviderFileConfig::DELIMITER_COLON ? 'selected' : '' }}>コロン (:)</option>
            </select>
            @error('delimiter')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="enclosure" class="block text-sm font-medium text-gray-700">囲み文字</label>
            <select name="enclosure" id="enclosure" required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('enclosure') border-red-300 @enderror">
              <option value="">選択してください</option>
              <option value="{{ DataProviderFileConfig::ENCLOSURE_DOUBLE_QUOTE }}" {{ old('enclosure') === DataProviderFileConfig::ENCLOSURE_DOUBLE_QUOTE ? 'selected' : '' }}>ダブルクォート (")</option>
              <option value="{{ DataProviderFileConfig::ENCLOSURE_SINGLE_QUOTE }}" {{ old('enclosure') === DataProviderFileConfig::ENCLOSURE_SINGLE_QUOTE ? 'selected' : '' }}>シングルクォート (')</option>
              <option value="{{ DataProviderFileConfig::ENCLOSURE_BACKTICK }}" {{ old('enclosure') === DataProviderFileConfig::ENCLOSURE_BACKTICK ? 'selected' : '' }}>バッククォート (`)</option>
              <option value="{{ DataProviderFileConfig::ENCLOSURE_PIPE }}" {{ old('enclosure') === DataProviderFileConfig::ENCLOSURE_PIPE ? 'selected' : '' }}>パイプ (|)</option>
            </select>
            @error('enclosure')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6">
          <a href="{{ route('data-provider.index') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            キャンセル
          </a>
          <button type="submit"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            作成
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

