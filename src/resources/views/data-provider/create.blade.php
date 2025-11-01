@extends('layouts.app')

@section('title', 'データプロバイダー作成')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
  <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-sm rounded-lg">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">データプロバイダー作成</h3>
      </div>

      <form method="POST" action="{{ route('data-provider.store') }}" class="p-6">
        @csrf

        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700">名称</label>
          <input type="text" name="name" id="name" value="{{ old('name') }}" required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-300 @enderror">
          @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex justify-end space-x-3">
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

