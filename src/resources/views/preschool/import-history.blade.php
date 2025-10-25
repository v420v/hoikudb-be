@extends('layouts.app')

@section('title', 'インポート履歴詳細')

@section('content')
  @use('App\Models\Preschool')
  <div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white shadow-sm rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-1 rounded-md bg-blue-100 text-blue-700 text-sm font-medium">
                  {{ $csvImportHistory->kind_ja }}
                </span>
                <span class="truncate" title="{{ $csvImportHistory->file_name }}">
                  {{ $csvImportHistory->file_name }}
                </span>
              </h3>
              <div class="text-sm text-gray-500 mt-1">
                インポート日時: {{ $csvImportHistory->created_at->format('Y-m-d H:i:s') }}
              </div>
            </div>
            <span class="text-sm text-gray-500">全 {{ number_format($total_count) }} 件</span>
          </div>
        </div>
        <div class="p-6">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">保育園名</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">0歳児</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">1歳児</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">2歳児</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">3歳児</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">4歳児</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">5歳児</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach ($preschoolMonthlyStats as $preschoolMonthlyStat)
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $preschoolMonthlyStat->preschool->name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $preschoolMonthlyStat->zero_year_old }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $preschoolMonthlyStat->one_year_old }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $preschoolMonthlyStat->two_year_old }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $preschoolMonthlyStat->three_year_old }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $preschoolMonthlyStat->four_year_old }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $preschoolMonthlyStat->five_year_old }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="mt-6">
            {{ $preschoolMonthlyStats->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
