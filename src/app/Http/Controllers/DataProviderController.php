<?php

namespace App\Http\Controllers;

use App\Models\DataProvider;
use App\Models\DataProviderFileConfig;
use Illuminate\Http\Request;

class DataProviderController
{
    public function index()
    {
        $dataProviders = DataProvider::with('fileConfigs')->orderBy('name')->get();

        return view('data-provider.index', [
            'dataProviders' => $dataProviders,
        ]);
    }

    public function create()
    {
        return view('data-provider.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        DataProvider::create([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('data-provider.index')
            ->with('success', config('messages.data_provider.created'));
    }

    public function edit($id)
    {
        $dataProvider = DataProvider::findOrFail($id);

        return view('data-provider.edit', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $dataProvider = DataProvider::findOrFail($id);
        $dataProvider->update([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('data-provider.index')
            ->with('success', config('messages.data_provider.updated'));
    }

    public function destroy($id)
    {
        $dataProvider = DataProvider::findOrFail($id);
        $dataProvider->delete();

        return redirect()->route('data-provider.index')
            ->with('success', config('messages.data_provider.deleted'));
    }

    public function createFileConfig($dataProviderId)
    {
        $dataProvider = DataProvider::findOrFail($dataProviderId);

        return view('data-provider.file-config.create', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function storeFileConfig(Request $request, $dataProviderId)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'file_type' => 'required|in:' . implode(',', [DataProviderFileConfig::FILE_TYPE_CSV, DataProviderFileConfig::FILE_TYPE_PDF]),
            'new_line' => 'required|in:' . implode(',', [DataProviderFileConfig::NEW_LINE_LF, DataProviderFileConfig::NEW_LINE_CRLF]),
            'encoding' => 'required|in:' . implode(',', [DataProviderFileConfig::ENCODING_SJIS, DataProviderFileConfig::ENCODING_UTF_8]),
            'delimiter' => 'required|string',
            'enclosure' => 'required|string',
        ]);

        DataProviderFileConfig::create([
            'data_provider_id' => $dataProviderId,
            'display_name' => $request->input('display_name'),
            'file_type' => $request->input('file_type'),
            'new_line' => $request->input('new_line'),
            'encoding' => $request->input('encoding'),
            'delimiter' => $request->input('delimiter'),
            'enclosure' => $request->input('enclosure'),
        ]);

        return redirect()->route('data-provider.index')
            ->with('success', config('messages.file_config.created'));
    }

    public function editFileConfig($dataProviderId, $fileConfigId)
    {
        $dataProvider = DataProvider::findOrFail($dataProviderId);
        $fileConfig = DataProviderFileConfig::findOrFail($fileConfigId);

        return view('data-provider.file-config.edit', [
            'dataProvider' => $dataProvider,
            'fileConfig' => $fileConfig,
        ]);
    }

    public function updateFileConfig(Request $request, $dataProviderId, $fileConfigId)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'file_type' => 'required|in:' . implode(',', [DataProviderFileConfig::FILE_TYPE_CSV, DataProviderFileConfig::FILE_TYPE_PDF]),
            'new_line' => 'required|in:' . implode(',', [DataProviderFileConfig::NEW_LINE_LF, DataProviderFileConfig::NEW_LINE_CRLF]),
            'encoding' => 'required|in:' . implode(',', [DataProviderFileConfig::ENCODING_SJIS, DataProviderFileConfig::ENCODING_UTF_8]),
            'delimiter' => 'required|string',
            'enclosure' => 'required|string',
        ]);

        $fileConfig = DataProviderFileConfig::findOrFail($fileConfigId);
        $fileConfig->update([
            'display_name' => $request->input('display_name'),
            'file_type' => $request->input('file_type'),
            'new_line' => $request->input('new_line'),
            'encoding' => $request->input('encoding'),
            'delimiter' => $request->input('delimiter'),
            'enclosure' => $request->input('enclosure'),
        ]);

        return redirect()->route('data-provider.index')
            ->with('success', config('messages.file_config.updated'));
    }

    public function destroyFileConfig($dataProviderId, $fileConfigId)
    {
        DataProviderFileConfig::findOrFail($fileConfigId)->delete();

        return redirect()->route('data-provider.index')
            ->with('success', config('messages.file_config.deleted'));
    }
}

