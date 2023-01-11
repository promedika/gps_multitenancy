@extends('layouts.app')

@section('content')
<style>
    .custom-label input:checked + svg {
        display: block !important;
    }
</style>

<main class="flex sm:container sm:mx-auto sm:mt-10">
    <div class="mx-auto w-4/5 sm:px-6">

        @if (session('status'))
            <div class="px-3 py-4 mb-4 text-sm text-green-700 bg-green-100 border border-t-8 border-green-600 rounded" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <section class="flex flex-col break-words bg-white sm:border-1">

            <header class="px-6 py-5 font-semibold text-gray-700 sm:py-6 sm:px-8">
                {{ __('Maintenance Form #'.$maintenance->id) }}
            </header>

            <form class="sm:grid sm:grid-cols-12 sm:p-4 sm:m-2" method="POST"
                action="#">
                <div class="border border-gray-300 col-span-3 text-lg font-semibold flex items-center justify-center">
                    Pekerjaan
                </div>
                <div class="border border-gray-300 col-span-2 text-xs flex items-center justify-center">
                    Preventive Maintenance
                </div>
                <div class="border border-gray-300 col-span-4 flex text-lg font-semibold items-center justify-center">
                    {{ $device->standard_name }}
                </div>
                <div class="border border-gray-300 col-span-3 flex flex-col items-center">
                    <div class="text-xs border-gray-300 border-b w-full flex px-2">Date : <span class="ml-auto">{{ date('d-m-Y', strtotime($maintenance->created_at)) }}</span></div>
                    <div class="flex items-center p-2">
                        <div>
                        <img width="100" height="50" src="{{ asset('gps_logo.png') }}">
                        </div>
                        @if (explode('.', $_SERVER['HTTP_HOST'])[0] == 'rsudkoja')  
                        <div style="padding-left: 5px">
                            <img width="140" height="50" src="{{ asset('logo Koja.png') }}">
                        </div>
                        @elseif ((explode('.', $_SERVER['HTTP_HOST'])[0] == 'rsudkramatjati'))
                        <div>
                            <img width="150" height="50" src="{{ asset('logo Kramat Jati.png') }}">
                        </div>
                        @endif
                    </div>
                </div>

                <div class="border border-gray-300 col-span-6 flex flex-col text-xs px-2">
                    <div class="my-1 grid grid-cols-2">
                        <p>Nama Rumah Sakit / Klinik</p>
                        <p>: {{ app('currentTenant')->name }}</p>
                    </div>
                    <div class="my-1 grid grid-cols-2">
                        <p>Lokasi</p>
                        <p>: {{ $maintenance->inventory->room->room_name }}</p>
                    </div>
                    <div class="my-1 grid grid-cols-2">
                        <p>Merk</p>
                        <p>: {{ $maintenance->inventory->identity->brand->brand }}</p>
                    </div>
                    <div class="my-1 grid grid-cols-2">
                        <p>Model / Tipe</p>
                        <p>: {{ $maintenance->inventory->identity->model }}</p>
                    </div>
                </div>

                <div class="border border-gray-300 col-span-6 flex flex-col text-xs px-2">
                    <div class="my-1 grid grid-cols-2">
                        <p>Nomor Inventory</p>
                        <p>: {{ $maintenance->inventory->barcode }}</p>
                    </div>
                    <div class="my-1 grid grid-cols-2">
                        <p>S / N</p>
                        <p>: 
                            @if ($maintenance->inventory->identity->serial)
                                {{ $maintenance->inventory->identity->serial }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="my-1 grid grid-cols-2">
                        <p>Nomor Kalibrasi</p>
                        <p>: 
                            @if ($maintenance->inventory->latest_record->label)
                                {{ $maintenance->inventory->latest_record->label }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="my-1 grid grid-cols-2">
                        <p>Tanggal Kalibrasi</p>
                        <p>: 
                            @if ($maintenance->inventory->latest_record->cal_date)
                                {{ date('d-m-Y', strtotime($maintenance->inventory->latest_record->cal_date)) }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>

                <div class="border border-gray-300 col-span-6 flex flex-col text-xs">
                    <div class="text-sm border-gray-300 bg-gray-300 font-semibold border-b w-full px-2">Kondisi Lingkungan</div>
                    <div class="my-1 grid grid-cols-2 px-2 py-1">
                        <p class="flex items-center">Suhu Ruangan</p>
                        <p>:
                            <input disabled value="{{ isset($raw->temperature) ? $raw->temperature : '' }}" type="number" name="temperature" class="w-16 text-sm rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600"> 
                            &#8451; <span class="ml-auto">( 21 - 25 )</span></p>
                    </div>
                    <div class="my-1 grid grid-cols-2 px-2 py-1">
                        <p class="flex items-center">Kelembaban</p>
                        <p>:
                            <input disabled value="{{ isset($raw->humidity) ? $raw->humidity : '' }}" type="number" name="humidity" class="w-16 text-sm rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600"> 
                            % <span>( 50 - 60 )</span></p>
                    </div>
                </div>

                <div class="border border-gray-300 col-span-6 flex flex-col text-xs">
                    <div class="text-sm border-gray-300 bg-gray-300 font-semibold border-b w-full px-2">Kondisi Kelistrikan</div>
                    <div class="my-1 grid grid-cols-2 px-2 py-1">
                        <p class="flex items-center">Tegangan Jala - Jala :
                            <input disabled value="{{ isset($raw->voltage) ? $raw->voltage : '' }}" type="number" name="voltage" class="w-16 text-sm rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600"> 
                            V
                        </p>
                        @if (isset($raw->is_ups) && $raw->is_ups)
                            <span class="flex items-center form-check">
                                <input disabled checked id="ups" name="is_ups" class="form-check-input appearance-none h-5 w-5 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label inline-block text-gray-800" for="flexCheckDefault">
                                    UPS &nbsp;
                                </label>
                                <p id="upsNode" class="items-center ml-auto"> : 
                                    <input disabled value="{{ isset($raw->ups) ? $raw->ups : '' }}" type="number" name="ups" class="w-16 text-sm rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600"> 
                                    V
                                </p>
                            </span>
                        @else
                            <span class="flex items-center form-check">
                                <input disabled id="ups" name="is_ups" class="form-check-input appearance-none h-5 w-5 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label inline-block text-gray-800" for="flexCheckDefault">
                                    UPS &nbsp;
                                </label>
                                <p id="upsNode" class="hidden items-center ml-auto"> : 
                                    <input disabled type="number" name="ups" class="w-16 text-sm rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600"> 
                                    V
                                </p>
                            </span>
                        @endif
                    </div>
                    <div class="my-1 grid grid-cols-2 px-2 py-1">
                        @if (isset($raw->is_stabilizer) && $raw->is_stabilizer)
                            <span class="flex form-check items-center col-start-2">
                                <input checked disabled id="stabilizer" name="is_stabilizer" class="form-check-input appearance-none h-5 w-5 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label inline-block text-gray-800" for="flexCheckDefault">
                                    Stabilizer &nbsp;
                                </label>
                                <p id="stabilizerNode" class="items-center ml-auto"> : 
                                    <input disabled value="{{ isset($raw->stabilizer) ? $raw->stabilizer : '' }}" type="number" name="stabilizer" class="w-16 text-sm rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600"> 
                                    V
                                </p>
                            </span>
                        @else
                            <span class="flex form-check items-center col-start-2">
                                <input disabled id="stabilizer" name="is_stabilizer" class="form-check-input appearance-none h-5 w-5 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label inline-block text-gray-800" for="flexCheckDefault">
                                    Stabilizer &nbsp;
                                </label>
                                <p id="stabilizerNode" class="hidden items-center ml-auto"> : 
                                    <input disabled type="number" name="stabilizer" class="w-16 text-sm rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600"> 
                                    V
                                </p>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="border border-gray-300 col-span-6 row-span-2 flex flex-col text-xs">
                    <div class="text-sm border-gray-300 bg-gray-300 font-semibold border-b w-full px-2">Alat Kerja yang Digunakan</div>
                    <div class="w-full justify-evenly flex items-center py-1">
                        <table class="align-middle">
                            <tbody>
                                <?php 
                                    if (isset($raw->is_tools)) {
                                        $raw->is_tools = (array) $raw->is_tools;
                                    }


                                    if (isset($raw->tools)) {
                                        $raw->tools = (array) $raw->tools;
                                    }
                                ?>
                                <tr>
                                    <td class="w-4">1. </td>
                                    <td class="w-32">ESA</td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled 
                                                    @if (isset($raw->is_tools[0]) && $raw->is_tools[0] == "on")
                                                        checked
                                                    @endif
                                                    type="checkbox" name="is_tools[0]" class="hidden">
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td class="w-4">6.<td>
                                    <td class="w-32">
                                        <input disabled value="{{ isset($raw->tools[5]) ? $raw->tools[5] : '' }}" type="text" name="tools[]" class="w-full text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600"> 
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled 
                                                @if (isset($raw->is_tools[5]) && $raw->is_tools[5] == "on")
                                                    checked
                                                @endif
                                                type="checkbox" name="is_tools[5]" class="hidden">
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-4">2. </td>
                                    <td class="w-32">Thermohygrometer</td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled 
                                                @if (isset($raw->is_tools[1]) && $raw->is_tools[1] == "on")
                                                    checked
                                                @endif
                                                type="checkbox" name="is_tools[1]" class="hidden">
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td class="w-4">7.<td>
                                    <td class="w-32">
                                        <input disabled value="{{ isset($raw->tools[6]) ? $raw->tools[6] : '' }}" type="text" name="tools[]" class="w-full text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600"> 
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled
                                                @if (isset($raw->is_tools[6]) && $raw->is_tools[6] == "on")
                                                    checked
                                                @endif
                                                type="checkbox" name="is_tools[6]" class="hidden">
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-4">3. </td>
                                    <td class="w-32">Toolset</td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled 
                                                @if (isset($raw->is_tools[2]) && $raw->is_tools[2] == "on")
                                                    checked
                                                @endif
                                                type="checkbox" name="is_tools[2]" class="hidden">
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td class="w-4">8.<td>
                                    <td class="w-32">
                                        <input disabled value="{{ isset($raw->tools[7]) ? $raw->tools[7] : '' }}" type="text" name="tools[]" class="w-full text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600"> 
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled 
                                                @if (isset($raw->is_tools[7]) && $raw->is_tools[7] == "on")
                                                    checked
                                                @endif
                                                type="checkbox" name="is_tools[7]" class="hidden">
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-4">4. </td>
                                    <td class="w-32">Cleaning Kit</td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled 
                                                @if (isset($raw->is_tools[3]) && $raw->is_tools[3] == "on")
                                                    checked
                                                @endif
                                                type="checkbox" name="is_tools[3]" class="hidden">
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td class="w-4">9.<td>
                                    <td class="w-32">
                                        <input disabled value="{{ isset($raw->tools[8]) ? $raw->tools[8] : '' }}" type="text" name="tools[]" class="w-full text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600"> 
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled 
                                                @if (isset($raw->is_tools[8]) && $raw->is_tools[8] == "on")
                                                    checked
                                                @endif
                                                type="checkbox" name="is_tools[8]" class="hidden">
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-4">5. </td>
                                    <td class="w-32">Multitester</td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled 
                                                @if (isset($raw->is_tools[4]) && $raw->is_tools[4] == "on")
                                                    checked
                                                @endif
                                                type="checkbox" name="is_tools[4]" class="hidden">
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td class="w-4">10.<td>
                                    <td class="w-32">
                                        <input disabled value="{{ isset($raw->is_tools[9]) ? $raw->is_tools[9] : '' }}" type="text" name="tools[]" class="w-full text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600"> 
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled 
                                                @if (isset($raw->is_tools[9]) && $raw->is_tools[9] == "on")
                                                    checked
                                                @endif
                                                type="checkbox" name="is_tools[9]" class="hidden">
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="border border-gray-300 col-span-6 flex flex-col text-xs">
                    <div class="text-sm border-gray-300 bg-gray-300 font-semibold border-b w-full px-2">Pemeriksaan Keamanan Lain</div>
                    <div class="my-1 grid grid-cols-5 px-2 py-1">
                        <p class="flex items-center col-span-2">Penempatan Alat : </p>
                        <label class="custom-label flex ml-3">
                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                <input disabled 
                                @if (isset($raw->placement) && $raw->placement == "good")
                                    checked
                                @endif
                                type="radio" name="placement" value="good" class="hidden">
                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                            </div>
                            <span class="select-none">Baik</span>
                        </label>
                        <label class="custom-label flex ml-3">
                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                <input disabled 
                                @if (isset($raw->placement) && $raw->placement == "bad")
                                    checked
                                @endif
                                type="radio" name="placement" value="bad" class="hidden">
                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                            </div>
                            <span class="select-none">Tidak</span>
                        </label>
                        <label class="custom-label flex ml-3">
                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                <input disabled 
                                @if (isset($raw->placement) && $raw->placement == "none")
                                    checked
                                @endif
                                type="radio" name="placement" value="none" class="hidden">
                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                            </div>
                            <span class="select-none">N / A</span>
                        </label>
                    </div>
                    <div class="my-1 grid grid-cols-5 px-2 py-1">
                        <p class="flex items-center col-span-2">Roda / Troli / Bracket : </p>
                        <label class="custom-label flex ml-3">
                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                <input disabled 
                                @if (isset($raw->extra) && $raw->extra == "good")
                                    checked
                                @endif
                                type="radio" name="extra" value="good" class="hidden">
                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                            </div>
                            <span class="select-none">Baik</span>
                        </label>
                        <label class="custom-label flex ml-3">
                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                <input disabled 
                                @if (isset($raw->extra) && $raw->extra == "bad")
                                    checked
                                @endif
                                type="radio" name="extra" value="bad" class="hidden">
                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                            </div>
                            <span class="select-none">Tidak</span>
                        </label>
                        <label class="custom-label flex ml-3">
                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                <input disabled 
                                @if (isset($raw->extra) && $raw->extra == "none")
                                    checked
                                @endif
                                type="radio" name="extra" value="none" class="hidden">
                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                            </div>
                            <span class="select-none">N / A</span>
                        </label>
                    </div>
                </div>

                <div class="border border-gray-300 col-span-6 flex flex-col text-xs pb-2">
                    <div class="grid grid-cols-3 gap-y-2 w-full">
                        <div class="text-sm border-gray-300 bg-gray-300 font-semibold border-b border-x w-full px-2">Pemeriksaan Fisik</div>
                        <div class="flex text-sm border-gray-300 bg-gray-300 font-semibold border-b border-x w-full px-2">
                            <span class="ml-4">B</span>
                            <span class="ml-6">C/RR</span>
                            <span class="ml-6">RB</span>
                        </div>
                        <div class="flex justify-evenly text-sm border-gray-300 bg-gray-300 font-semibold border-b border-x w-full px-2">
                            <span>Bersih</span>
                            <span>Kotor</span>
                        </div>

                        <p class="flex items-center px-4">Main Unit </p>
                        <div class="flex justify-evenly">
                            <label class="custom-label flex ml-3">
                                <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                    <input disabled 
                                    @if (isset($raw->physic_main) && $raw->physic_main == "0")
                                        checked
                                    @endif
                                    type="radio" name="physic_main" value="0" class="hidden">
                                    <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                </div>
                            </label>
                            <label class="custom-label flex ml-3">
                                <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                    <input disabled 
                                    @if (isset($raw->physic_main) && $raw->physic_main == "1")
                                        checked
                                    @endif
                                    type="radio" name="physic_main" value="1" class="hidden">
                                    <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                </div>
                            </label>
                            <label class="custom-label flex ml-3">
                                <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                    <input disabled 
                                    @if (isset($raw->physic_main) && $raw->physic_main == "2")
                                        checked
                                    @endif
                                    type="radio" name="physic_main" value="2" class="hidden">
                                    <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                </div>
                            </label>
                        </div>
                        <div class="flex justify-evenly">
                            <label class="custom-label flex ml-3">
                                <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                    <input disabled 
                                    @if (isset($raw->condition_main) && $raw->condition_main == "clean")
                                        checked
                                    @endif
                                    type="radio" name="condition_main" value="clean" class="hidden">
                                    <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                </div>
                            </label>
                            <label class="custom-label flex ml-3">
                                <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                    <input disabled 
                                    @if (isset($raw->condition_main) && $raw->condition_main == "dirty")
                                        checked
                                    @endif
                                    type="radio" name="condition_main" value="dirty" class="hidden">
                                    <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                </div>
                            </label>
                        </div>

                        <p class="flex items-center px-4">Roda / Troli / Bracket </p>
                        <div class="flex justify-evenly">
                            <label class="custom-label flex ml-3">
                                <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                    <input disabled 
                                    @if (isset($raw->physic_extra) && $raw->physic_extra == "0")
                                        checked
                                    @endif
                                    type="radio" name="physic_extra" value="0" class="hidden">
                                    <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                </div>
                            </label>
                            <label class="custom-label flex ml-3">
                                <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                    <input disabled 
                                    @if (isset($raw->physic_extra) && $raw->physic_extra == "1")
                                        checked
                                    @endif
                                    type="radio" name="physic_extra" value="1" class="hidden">
                                    <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                </div>
                            </label>
                            <label class="custom-label flex ml-3">
                                <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                    <input disabled 
                                    @if (isset($raw->physic_extra) && $raw->physic_extra == "2")
                                        checked
                                    @endif
                                    type="radio" name="physic_extra" value="2" class="hidden">
                                    <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                </div>
                            </label>
                        </div>
                        <div class="flex justify-evenly">
                            <label class="custom-label flex ml-3">
                                <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                    <input disabled 
                                    @if (isset($raw->condition_extra) && $raw->condition_extra == "clean")
                                        checked
                                    @endif
                                    type="radio" name="condition_extra" value="clean" class="hidden">
                                    <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                </div>
                            </label>
                            <label class="custom-label flex ml-3">
                                <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                    <input disabled 
                                    @if (isset($raw->condition_extra) && $raw->condition_extra == "dirty")
                                        checked
                                    @endif
                                    type="radio" name="condition_extra" value="dirty" class="hidden">
                                    <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="border border-gray-300 col-span-6 flex flex-col text-xs">
                    <div class="text-sm border-gray-300 bg-gray-300 font-semibold border-b w-full px-2 flex">
                        Pemeriksaan Keamanan Listrik
                        {{--
                        <a onclick="addRow('electricity')" class="ml-auto text-green-500 hover:text-gray-500 cursor-pointer">
                            <i class="fas fa-plus-circle"></i>
                        </a>
                        --}}
                    </div>
                    <table class="align-middle my-3 mr-1">
                        <tbody id="elBody">
                            @php $raw->is_el = isset($raw->is_el) ? $raw->is_el : []; @endphp
                            <tr>
                                <td class="w-64">
                                    <span class="ml-2 text-xs flex items-center">Tahanan hubungan pertanahan</span>
                                </td>
                                <td class="form-check">
                                    <input disabled onclick="toggleInput(this)" id="el1" name="is_el[]" value="el1" class="form-check-input appearance-none h-5 w-5 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox" id="flexCheckDefault" 
                                    @php echo (in_array('el1', $raw->is_el)) ? 'checked' : ''; @endphp
                                    >
                                </td>
                                <td class="flex justify-start pl-2">
                                    <p id="el1Node" class="@php echo (in_array('el1', $raw->is_el)) ? 'flex' : 'hidden'; @endphp items-center col-span-2">
                                        <input disabled type="number" name="el[]" class="w-16 text-sm rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto"
                                        value="{{ isset($raw->el[0]) && $raw->el[0] }}"> 
                                        &nbsp;&#8804;0,2&#8486;&nbsp;&nbsp;&nbsp;
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-64">
                                    <span class="ml-2 pr-1 text-xs flex items-center break-normal">Arus bocor Casis dengan Pembumian</span>
                                </td>
                                <td>
                                    <input disabled onclick="toggleInput(this)" id="el2" name="is_el[]" value="el2" class="form-check-input appearance-none h-5 w-5 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox" id="flexCheckDefault" 
                                    @php echo (in_array('el2', $raw->is_el)) ? 'checked' : ''; @endphp
                                    >
                                </td>
                                <td class="flex justify-start pl-2">
                                    <p id="el2Node" class="@php echo (in_array('el2', $raw->is_el)) ? 'flex' : 'hidden'; @endphp items-center col-span-2">
                                        <input disabled type="number" name="el[]" class="w-16 text-sm rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto"
                                        value="{{ isset($raw->el[1]) && $raw->el[1] }}"> 
                                        &nbsp;&#8804;100&#xb5;A
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-64">
                                    <span class="ml-2 pr-1 text-xs col-span-3 flex items-center break-normal">Arus bocor Casis tanpa Pembumian</span>
                                </td>
                                <td>
                                    <input disabled onclick="toggleInput(this)" id="el3" name="is_el[]" value="el3" class="form-check-input appearance-none h-5 w-5 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox" id="flexCheckDefault"
                                    @php echo (in_array('el3', $raw->is_el)) ? 'checked' : ''; @endphp
                                    >
                                </td>
                                <td class="flex justify-start pl-2">
                                    <p id="el3Node" class="@php echo (in_array('el3', $raw->is_el)) ? 'flex' : 'hidden'; @endphp items-center col-span-2">
                                        <input disabled type="number" name="el[]" class="w-16 text-sm rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto" value="{{ isset($raw->el[2]) && $raw->el[2] }}"> 
                                        &nbsp;&#8804;500&#xb5;A
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-64">
                                    <span class="ml-2 pr-1 text-xs col-span-3 flex items-center break-normal">Arus bocor Casis Polaritas terbalik dengan Pembumian</span>
                                </td>
                                <td>
                                    <input disabled onclick="toggleInput(this)" id="el4" name="is_el[]" value="el4" class="form-check-input appearance-none h-5 w-5 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox" id="flexCheckDefault"
                                    @php echo (in_array('el4', $raw->is_el)) ? 'checked' : ''; @endphp
                                    >
                                </td>
                                <td class="flex justify-start pl-2">
                                    <p id="el4Node" class="@php echo (in_array('el4', $raw->is_el)) ? 'flex' : 'hidden'; @endphp items-center col-span-2">
                                        <input disabled type="number" name="el[]" class="w-16 text-sm rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto" value="{{ isset($raw->el[3]) && $raw->el[3] }}"> 
                                        &nbsp;&#8804;100&#xb5;A
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-64">
                                    <span class="ml-2 pr-1 text-xs col-span-3 flex items-center break-normal">Arus bocor Casis Polaritas terbalik tanpa Pembumian</span>
                                </td>
                                <td>
                                    <input disabled onclick="toggleInput(this)" id="el5" name="is_el[]" value="el5" class="form-check-input appearance-none h-5 w-5 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox" id="flexCheckDefault" 
                                    @php echo (in_array('el5', $raw->is_el)) ? 'checked' : ''; @endphp
                                    >
                                </td>
                                <td class="flex justify-start pl-2">
                                    <p id="el5Node" class="@php echo (in_array('el5', $raw->is_el)) ? 'flex' : 'hidden'; @endphp items-center col-span-2">
                                        <input disabled type="number" name="el[]" class="w-16 text-sm rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto" value="{{ isset($raw->el[4]) && $raw->el[4] }}"> 
                                        &nbsp;&#8804;500&#xb5;A
                                    </p>
                                </td>
                            </tr>
                            @isset($raw->elParam)
                                @foreach ($raw->elParam as $i => $p)
                                    <tr>
                                        <td class="w-64">
                                            <span class="ml-2 pr-1 text-xs col-span-3 flex items-center break-normal">{{ $p }}</span>
                                        </td>
                                        <td>
                                            <input disabled onclick="toggleInput(this)" id="el{{count($raw->el) + $i}}" name="is_el[]" value="el{{count($raw->el) + $i}}" class="form-check-input appearance-none h-5 w-5 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox" id="flexCheckDefault" 
                                            @php echo (in_array('el'.count($raw->el) + $i, $raw->is_el)) ? 'checked' : ''; @endphp
                                            >
                                        </td>
                                        <td class="flex justify-start pl-2">
                                            <p id="el{{count($raw->el) + $i}}Node" class="@php echo (in_array('el'.count($raw->el) + $i, $raw->is_el)) ? 'flex' : 'hidden'; @endphp items-center col-span-2">
                                                <input disabled type="number" name="el[]" class="w-16 text-sm rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto" value="{{ isset($raw->el[count($raw->el) + $i]) && $raw->el[count($raw->el) + $i] }}"> 
                                                &nbsp;&#8804;500&#xb5;A
                                            </p>
                                        </td>
                                    </tr>
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>

                <div class="border border-gray-300 col-span-6 row-span-2 flex flex-col text-xs">
                    <table class="text-sm border-gray-300 bg-gray-300 font-semibold border-b w-full px-2">
                        <tr>
                            <td class="w-48 pl-2">
                                Pemeriksaan Fungsi Alat
                            </td>
                            <td class="pl-4">
                                N / A
                            </td>
                            <td class="pl-7">
                                Baik
                            </td>
                            <td class="pl-6">
                                Tidak
                            </td>
                            {{--
                            <td>
                                <a onclick="addRow('function')" class="ml-auto text-green-500 hover:text-gray-500 cursor-pointer">
                                    <i class="fas fa-plus-circle"></i>
                                </a>
                            </td>
                            --}}
                        </tr>
                    </table>
                    <div class="px-2 py-1 my-1">
                        <table class="align-middle w-full">
                            <tbody id="funcBody">
                                <?php 
                                    if (isset($raw->func)) {
                                        $raw->func = (array) $raw->func;
                                    }
                                ?>
                                <tr>
                                    <td class="w-48">
                                        <p class="flex items-center col-span-2">Display / Monitor</p>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="func[0]" value="0" class="hidden" 
                                                @if (isset($raw->func[0]) && $raw->func[0] == "0")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="func[0]" value="1" class="hidden" 
                                                @if (isset($raw->func[0]) && $raw->func[0] == "1")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="func[0]" value="2" class="hidden" 
                                                @if (isset($raw->func[0]) && $raw->func[0] == "2")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-48">
                                        <p class="flex items-center col-span-2">Switch On / Off</p>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="func[1]" value="0" class="hidden" 
                                                @if (isset($raw->func[1]) && $raw->func[1] == "0")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="func[1]" value="1" class="hidden" 
                                                @if (isset($raw->func[1]) && $raw->func[1] == "1")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="func[1]" value="2" class="hidden" 
                                                @if (isset($raw->func[1]) && $raw->func[1] == "2")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-48">
                                        <p class="flex items-center col-span-2">Control / Setting</p>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="func[2]" value="0" class="hidden" 
                                                @if (isset($raw->func[2]) && $raw->func[2] == "0")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="func[2]" value="1" class="hidden" 
                                                @if (isset($raw->func[2]) && $raw->func[2] == "1")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="func[2]" value="2" class="hidden" 
                                                @if (isset($raw->func[2]) && $raw->func[2] == "2")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-48">
                                        <p class="flex items-center col-span-2">Keypad</p>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="func[3]" value="0" class="hidden" 
                                                @if (isset($raw->func[3]) && $raw->func[3] == "0")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="func[3]" value="1" class="hidden" 
                                                @if (isset($raw->func[3]) && $raw->func[3] == "1")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="func[3]" value="2" class="hidden" 
                                                @if (isset($raw->func[3]) && $raw->func[3] == "2")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-48">
                                        <p class="flex items-center col-span-2">Timer</p>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="func[4]" value="0" class="hidden" 
                                                @if (isset($raw->func[4]) && $raw->func[4] == "0")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="func[4]" value="1" class="hidden" 
                                                @if (isset($raw->func[4]) && $raw->func[4] == "1")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="func[4]" value="2" class="hidden" 
                                                @if (isset($raw->func[4]) && $raw->func[4] == "2")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                </tr>
                                @isset($raw->funcParam)
                                    @foreach ($raw->funcParam as $i => $p)
                                        <tr>
                                            <td class="w-48">
                                                <p class="flex items-center col-span-2">{{ $p }}</p>
                                            </td>
                                            <td>
                                                <label class="custom-label flex ml-3">
                                                    <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                        <input disabled type="radio" name="func[4 + $i]" value="0" class="hidden" 
                                                        @if (isset($raw->func[4 + $i]) && $raw->func[4 + $i] == "0")
                                                            checked
                                                        @endif>
                                                        <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                                    </div>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="custom-label flex ml-3">
                                                    <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                        <input disabled type="radio" name="func[4 + $i]" value="1" class="hidden" 
                                                        @if (isset($raw->func[4 + $i]) && $raw->func[4 + $i] == "1")
                                                            checked
                                                        @endif>
                                                        <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                                    </div>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="custom-label flex ml-3">
                                                    <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                        <input disabled type="radio" name="func[4 + $i]" value="2" class="hidden" 
                                                        @if (isset($raw->func[4 + $i]) && $raw->func[4 + $i] == "2")
                                                            checked
                                                        @endif>
                                                        <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                                    </div>
                                                </label>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="border border-gray-300 col-span-6 flex flex-col text-xs">
                    <table class="text-sm border-gray-300 bg-gray-300 font-semibold border-b w-full px-2">
                        <tr>
                            <td class="w-48 pl-2">
                                Kelengkapan Alat
                            </td>
                            <td class="pl-4">
                                N / A
                            </td>
                            <td class="pl-7">
                                Baik
                            </td>
                            <td class="pl-6">
                                Tidak
                            </td>
                            {{--
                            <td>
                                <a onclick="addRow('complete')" class="ml-auto text-green-500 hover:text-gray-500 cursor-pointer">
                                    <i class="fas fa-plus-circle"></i>
                                </a>
                            </td>
                            --}}
                        </tr>
                    </table>
                    <div class="px-2 py-1 my-1">
                        <table class="align-middle w-full">
                            <tbody id="completeBody">
                                <tr>
                                    <td class="w-48">
                                        <p class="flex items-center col-span-2">Power Cord / Adaptor</p>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="complete[0]" value="0" class="hidden"
                                                @if (isset($raw->complete[0]) && $raw->complete[0] == "0")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="complete[0]" value="1" class="hidden"
                                                @if (isset($raw->complete[0]) && $raw->complete[0] == "1")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="complete[0]" value="2" class="hidden"
                                                @if (isset($raw->complete[0]) && $raw->complete[0] == "2")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                </tr>
                                @isset($raw->compParam)
                                    @foreach ($raw->compParam as $i => $p)
                                        <tr>
                                            <td class="w-48">
                                                <p class="flex items-center col-span-2">{{ $p }}</p>
                                            </td>
                                            <td>
                                                <label class="custom-label flex ml-3">
                                                    <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                        <input disabled type="radio" name="complete[0]" value="0" class="hidden"
                                                        @if (isset($raw->complete[0 + $i]) && $raw->complete[0 + $i] == "0")
                                                            checked
                                                        @endif>
                                                        <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                                    </div>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="custom-label flex ml-3">
                                                    <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                        <input disabled type="radio" name="complete[0]" value="1" class="hidden"
                                                        @if (isset($raw->complete[0 + $i]) && $raw->complete[0 + $i] == "1")
                                                            checked
                                                        @endif>
                                                        <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                                    </div>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="custom-label flex ml-3">
                                                    <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                        <input disabled type="radio" name="complete[0]" value="2" class="hidden"
                                                        @if (isset($raw->complete[0 + $i]) && $raw->complete[0 + $i] == "2")
                                                            checked
                                                        @endif>
                                                        <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                                    </div>
                                                </label>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="border border-gray-300 col-span-12 flex flex-col text-xs">
                    <div class="text-sm border-gray-300 bg-gray-300 font-semibold border w-full px-2 flex items-center">
                        <span class="w-64">
                            Pemeriksaan Kinerja Alat
                        </span>
                        <span class="w-16 ml-3 text-center">
                            Setting
                        </span>
                        <span class="w-14 ml-10 text-center">
                            Terukur I
                        </span>
                        <span class="w-16 ml-9 text-center">
                            Terukur II
                        </span>
                        <span class="w-20 ml-9 text-center">
                            Nilai Acuan
                        </span>
                        <span class="w-16 ml-5 text-center">
                            Baik
                        </span>
                        <span class="w-16 ml-1 text-center">
                            Tidak
                        </span>
                        {{--
                        <span>
                            <a onclick="addRow('performance')" class="ml-auto text-green-500 cursor-pointer">
                                <i class="fas fa-plus-circle"></i>
                            </a>
                        </span>
                        --}}
                    </div>
                    <div class="px-2 py-1 my-1">
                        <table class="align-middle w-full">
                            <tbody id="performBody">
                                @isset($raw->performanceParam)
                                    @foreach ($raw->performanceParam as $i => $p)
                                    <tr>
                                        <td class="w-64 border-gray-300 border-r">
                                            <span class="flex items-center col-span-2">
                                                <input disabled type="text" name="performanceParam[]" class="w-full text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto"
                                                value="{{ isset($p) ? $p : '' }}">
                                            </span>
                                        </td>
                                        <td class="border-gray-300 text-center border-r">
                                            <input disabled type="text" name="setting[]" class="w-16 text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto"
                                            value="{{ isset($raw->setting[$i]) ? $raw->setting[$i] : '' }}">
                                        </td>
                                        <td class="border-gray-300 text-center border-r">
                                            <input disabled type="number" name="value[0][]" class="w-16 text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto"
                                            value="{{ isset($raw->value[0][$i]) ? $raw->value[0][$i] : '' }}">
                                        </td>
                                        <td class="border-gray-300 text-center border-r">
                                            <input disabled type="number" name="value[1][]" class="w-16 text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto"
                                            value="{{ isset($raw->value[1][$i]) ? $raw->value[1][$i] : '' }}">
                                        </td>
                                        <td class="border-gray-300 text-center border-r">
                                            <input disabled type="text" name="reference[]" class="w-20 text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto"
                                            value="{{ isset($raw->reference[$i]) ? $raw->reference[$i] : '' }}">
                                        </td>
                                        <td>
                                            <label class="custom-label flex ml-3">
                                                <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                    <input disabled type="radio" name="performanceCondition[0]" value="1" class="hidden"
                                                    @if (isset($raw->performanceCondition[$i]) && $raw->performanceCondition[$i] == "1")
                                                        checked
                                                    @endif>
                                                    <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                                </div>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="custom-label flex ml-3">
                                                <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                    <input disabled type="radio" name="performanceCondition[0]" value="0" class="hidden"
                                                    @if (isset($raw->performanceCondition[$i]) && $raw->performanceCondition[$i] == "0")
                                                        checked
                                                    @endif>
                                                    <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                                </div>
                                            </label>
                                        </td>
                                        {{--
                                        <td class="">
                                            <a onclick="deleteRow(this)" class="text-red-500 hover:text-gray-500 cursor-pointer mx-1">
                                                <i class="fas fa-minus-circle"></i>
                                            </a>
                                        </td>
                                        --}}
                                    </tr>
                                    @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                    <div class="text-sm border-gray-300 bg-gray-300 font-semibold border w-full px-2 flex items-center">
                        <span class="w-64">
                        </span>
                        <span class="w-20 ml-7 text-center">
                            Spesifikasi
                        </span>
                        <span class="w-14 ml-7 text-center">
                            Terukur I
                        </span>
                        <span class="w-16 ml-7 text-center">
                            Terukur II
                        </span>
                        <span class="w-20 ml-6 text-center">
                            Nilai Acuan
                        </span>
                        <span class="w-16 ml-1 text-center">
                            Baik
                        </span>
                        <span class="w-16 ml-0 text-center">
                            Tidak
                        </span>
                        <span>
                        </span>
                    </div>
                    <div class="px-2 py-1 my-1">
                        <table class="align-middle w-full">
                            <tbody id="performBody">
                                <tr>
                                    <td class="w-64 border-gray-300 border-r">
                                        <span class="flex items-center col-span-2">
                                            Battery
                                        </span>
                                    </td>
                                    <td class="border-gray-300 text-center border-r">
                                        <input disabled type="text" name="batterySetting[]" class="w-16 text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto" value="{{ isset($raw->batterySetting[0]) ? $raw->batterySetting[0] : '' }}">
                                        VDC
                                    </td>
                                    <td class="border-gray-300 text-center border-r">
                                        <input disabled type="number" name="battery[0][]" class="w-16 text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto" value="{{ isset($raw->battery[0][0]) ? $raw->battery[0][0] : '' }}">
                                    </td>
                                    <td class="border-gray-300 text-center border-r">
                                        <input disabled type="number" name="battery[1][]" class="w-16 text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto" value="{{ isset($raw->battery[1][0]) ? $raw->battery[1][0] : '' }}">
                                    </td>
                                    <td class="border-gray-300 text-center border-r">
                                        <div class="text-center w-20">
                                            &#8804;10%
                                        </div>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="batteryCondition[0]" value="1" class="hidden"
                                                @if (isset($raw->batteryCondition[0]) && $raw->batteryCondition[0] == "0")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="batteryCondition[0]" value="0" class="hidden"
                                                @if (isset($raw->batteryCondition[0]) && $raw->batteryCondition[0] == "1")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td class="w-7">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="border border-gray-300 col-span-12 flex flex-col text-xs">
                    <div class="text-sm border-gray-300 border w-full pr-2 flex items-center">
                        <span class="w-64 bg-gray-300 pl-2 font-semibold">
                            Hasil Pemeriksaan
                        </span>
                        <span class="flex items-center text-center mx-4">
                            <label class="custom-label flex ml-3">
                                <div class="bg-white shadow w-4 h-4 p-1 flex justify-center items-center mr-2">
                                    <input disabled type="radio" name="inspectionResult" value="1" class="hidden"
                                    @if (isset($raw->inspectionResult) && $raw->inspectionResult == "1")
                                        checked
                                    @endif>
                                    <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                </div>
                            </label>
                            <span class="select-none text-xs">Alat Bekerja dengan Baik</span>
                        </span>
                        <span class="flex items-center text-center ml-8">
                            <label class="custom-label flex ml-3">
                                <div class="bg-white shadow w-4 h-4 p-1 flex justify-center items-center mr-2">
                                    <input disabled type="radio" name="inspectionResult" value="0" class="hidden"
                                    @if (isset($raw->inspectionResult) && $raw->inspectionResult == "0")
                                        checked
                                    @endif>
                                    <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                </div>
                            </label>
                            <span class="select-none text-xs">Alat Tidak Bekerja dengan Baik</span>
                        </span>
                    </div>
                </div>

                <div class="border border-gray-300 col-span-6 flex flex-col text-xs">
                    <table class="text-sm border-gray-300 bg-gray-300 font-semibold border-b w-full px-2">
                        <tr>
                            <td class="w-64 pl-2">
                                Pemeliharaan Alat
                            </td>
                            <td class="pl-2">
                                Dilakukan
                            </td>
                            <td class="pr-6">
                                Tidak
                            </td>
                        </tr>
                    </table>
                    <div class="px-2 py-6 my-1">
                        <table class="align-middle w-full">
                            <tbody>
                                <?php 
                                    if (isset($raw->maintenance)) {
                                        $raw->maintenance = (array) $raw->maintenance;
                                    }
                                ?>
                                <tr>
                                    <td class="w-64">
                                        <p class="flex items-center col-span-2">Pembersihan Main Unit</p>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="maintenance[0]" value="1" class="hidden"
                                                @if (isset($raw->maintenance[0]) && $raw->maintenance[0] == "1")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="maintenance[0]" value="0" class="hidden"
                                                @if (isset($raw->maintenance[0]) && $raw->maintenance[0] == "0")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-64">
                                        <p class="flex items-center col-span-2">Pembersihan Aksesoris / Kelengkapan alat</p>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="maintenance[1]" value="1" class="hidden"
                                                @if (isset($raw->maintenance[1]) && $raw->maintenance[1] == "1")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="maintenance[1]" value="0" class="hidden"
                                                @if (isset($raw->maintenance[1]) && $raw->maintenance[1] == "0")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-64">
                                        <p class="flex items-center col-span-2">Pemantauan fungsi alat</p>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="maintenance[2]" value="1" class="hidden"
                                                @if (isset($raw->maintenance[2]) && $raw->maintenance[2] == "1")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="maintenance[2]" value="0" class="hidden"
                                                @if (isset($raw->maintenance[2]) && $raw->maintenance[2] == "0")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-64">
                                        <p class="flex items-center col-span-2">Pemantauan kinerja alat</p>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="maintenance[3]" value="1" class="hidden"
                                                @if (isset($raw->maintenance[3]) && $raw->maintenance[3] == "1")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="maintenance[3]" value="0" class="hidden"
                                                @if (isset($raw->maintenance[3]) && $raw->maintenance[3] == "0")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="flex w-64">
                                        <p class="flex items-center col-span-2">Penggantian Consumable</p>
                                        <input disabled type="text" name="cons" class="text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto"
                                        value="{{ $raw->cons }}">
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="maintenance[4]" value="1" class="hidden"
                                                @if (isset($raw->maintenance[4]) && $raw->maintenance[4] == "1")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="maintenance[4]" value="0" class="hidden"
                                                @if (isset($raw->maintenance[4]) && $raw->maintenance[4] == "0")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-64">
                                        <p class="flex items-center col-span-2">Lubricating &/ Tighting</p>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="maintenance[5]" value="1" class="hidden"
                                                @if (isset($raw->maintenance[5]) && $raw->maintenance[5] == "1")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-label flex ml-3">
                                            <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                <input disabled type="radio" name="maintenance[5]" value="0" class="hidden"
                                                @if (isset($raw->maintenance[5]) && $raw->maintenance[5] == "0")
                                                    checked
                                                @endif>
                                                <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                            </div>
                                        </label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="border border-gray-300 col-span-6 flex flex-col text-xs">
                    <table class="text-sm border-gray-300 bg-gray-300 font-semibold border-b w-full px-2">
                        <tr>
                            <td class="w-14 text-center pl-5">
                                Stok
                            </td>
                            <td class="w-52 text-center pl-6">
                                Konsumabel
                            </td>
                            <td class="pl-3">
                                Ada
                            </td>
                            <td class="pl-1">
                                Tidak
                            </td>
                            <td class="pl-1">
                                Habis
                            </td>
                        </tr>
                    </table>
                    <div class="px-2 py-1 my-1">
                        <table class="align-middle w-full">
                            <tbody id="consBody">
                                @isset($raw->stock)
                                    @for ($i=0; $i < 6; $i++)
                                        <tr>
                                            <td>
                                                <input disabled type="text" name="stock[]" class="w-14 text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto" value="{{ isset($raw->stock[$i]) ? $raw->stock[$i] : ''}}">
                                            </td>
                                            <td class="w-56">
                                                <span class="flex items-center col-span-2">
                                                    <input disabled type="text" name="consumables[]" class="w-full text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto" value="{{ isset($raw->consumables[$i]) ? $raw->consumables[$i] : ''}}">
                                                </span>
                                            </td>
                                            <td>
                                                <label class="custom-label flex ml-3">
                                                    <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                        <input disabled type="radio" name="consCondition[{{$i}}]" value="0" class="hidden"
                                                        @if (isset($raw->consCondition[$i]) && $raw->consCondition[$i] == "0")
                                                            checked
                                                        @endif>
                                                        <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                                    </div>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="custom-label flex ml-3">
                                                    <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                        <input disabled type="radio" name="consCondition[{{$i}}]" value="1" class="hidden"
                                                        @if (isset($raw->consCondition[$i]) && $raw->consCondition[$i] == "1")
                                                            checked
                                                        @endif>
                                                        <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                                    </div>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="custom-label flex ml-3">
                                                    <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                                                        <input disabled type="radio" name="consCondition[{{$i}}]" value="2" class="hidden"
                                                        @if (isset($raw->consCondition[$i]) && $raw->consCondition[$i] == "2")
                                                            checked
                                                        @endif>
                                                        <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                                    </div>
                                                </label>
                                            </td>
                                        </tr>
                                    @endfor
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="border border-gray-300 col-span-12 flex flex-col text-xs">
                    <div class="text-sm border-gray-300 border w-full pr-2 flex items-center">
                        <span class="w-64 bg-gray-300 pl-2 font-semibold">
                            Hasil Maintenance
                        </span>
                        <span class="flex items-center text-center mx-4">
                            <label class="custom-label flex ml-3">
                                <div class="bg-white shadow w-4 h-4 p-1 flex justify-center items-center mr-2">
                                    <input disabled type="radio" name="maintenanceResult" value="1" class="hidden"
                                    @if (isset($raw->maintenanceResult) && $raw->maintenanceResult == "1")
                                        checked
                                    @endif>
                                    <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                </div>
                            </label>
                            <span class="select-none text-xs">Alat Berfungsi dengan Baik</span>
                        </span>
                        <span class="flex items-center text-center ml-8">
                            <label class="custom-label flex ml-3">
                                <div class="bg-white shadow w-4 h-4 p-1 flex justify-center items-center mr-2">
                                    <input disabled type="radio" name="maintenanceResult" value="0" class="hidden"
                                    @if (isset($raw->maintenanceResult) && $raw->maintenanceResult == "0")
                                        checked
                                    @endif>
                                    <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                </div>
                            </label>
                            <span class="select-none text-xs">Alat Tidak Dapat Berfungsi dengan Baik</span>
                        </span>
                    </div>
                </div>

                <div class="border border-gray-300 col-span-12 flex flex-col text-xs">
                    <div class="text-sm border-gray-300 border w-full pr-2 flex items-center">
                        <span class="w-64 h-full flex items-center bg-gray-300 pl-2 font-semibold">
                            Rekomendasi Hasil Maintenance
                        </span>
                        <div class="flex flex-col mx-4">
                            <span class="flex items-center text-center my-1">
                                <label class="custom-label flex ml-3">
                                    <div class="bg-white shadow w-4 h-4 p-1 flex justify-center items-center mr-2">
                                        <input disabled type="radio" name="recommendation" value="0" class="hidden"
                                        @if (isset($raw->recommendation) && $raw->recommendation == "0")
                                            checked
                                        @endif>
                                        <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                    </div>
                                </label>
                                <span class="select-none text-xs">Alat Dapat Digunakan</span>
                            </span>
                            <span class="flex items-center text-center my-1">
                                <label class="custom-label flex ml-3">
                                    <div class="bg-white shadow w-4 h-4 p-1 flex justify-center items-center mr-2">
                                        <input disabled type="radio" name="recommendation" value="1" class="hidden"
                                        @if (isset($raw->recommendation) && $raw->recommendation == "3")
                                            checked
                                        @endif>
                                        <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                    </div>
                                </label>
                                <span class="select-none text-xs">Alat Perlu Dikalibrasi</span>
                            </span>
                        </div>
                        <div class="flex flex-col ml-14">
                            <span class="flex items-center text-center my-1">
                                <label class="custom-label flex ml-3">
                                    <div class="bg-white shadow w-4 h-4 p-1 flex justify-center items-center mr-2">
                                        <input disabled type="radio" name="recommendation" value="2" class="hidden"
                                        @if (isset($raw->recommendation) && $raw->recommendation == "2")
                                            checked
                                        @endif>
                                        <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                    </div>
                                </label>
                                <span class="select-none text-xs">Alat Tidak Dapat Digunakan</span>
                            </span>
                            <span class="flex items-center text-center my-1">
                                <label class="custom-label flex ml-3">
                                    <div class="bg-white shadow w-4 h-4 p-1 flex justify-center items-center mr-2">
                                        <input disabled type="radio" name="recommendation" value="3" class="hidden"
                                        @if (isset($raw->recommendation) && $raw->recommendation == "3")
                                            checked
                                        @endif>
                                        <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                                    </div>
                                </label>
                                <span class="select-none text-xs">Alat Harus Dikalibrasi</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="border border-gray-300 col-span-12 flex flex-col text-xs">
                    <div class="text-sm border-gray-300 border bg-gray-300 w-full pr-2 flex items-center">
                        <span class="w-full flex items-center pl-2 font-semibold">
                            Catatan
                        </span>
                    </div>
                    <div class="w-full flex justify-center">
                        <textarea disabled rows="15" name="notes" class="w-full my-1 text-xs shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600">{{ isset($raw->notes) ? $raw->notes : '' }}</textarea>
                    </div>
                </div>

                <div class="border border-gray-300 col-span-12 flex flex-col text-xs">
                    <div class="text-sm border-gray-300 border bg-gray-300 w-full pr-2 flex justify-evenly items-center">
                        <span class="w-full text-center justify-center flex items-center font-semibold">
                            Teknisi Pelaksana
                        </span>
                        <span class="w-full text-center justify-center flex items-center font-semibold">
                            Penanggung Jawab Alat / Ruangan
                        </span>
                        <span class="w-full text-center justify-center flex items-center font-semibold">
                            IPSRS
                        </span>
                    </div>
                    <div class="w-full flex">
                        <div class="h-36 w-72 border border-gray-300 flex flex-col items-center text-center text-sm">
                            <img src="{{ asset('stamp_submitted.png') }}" alt="">
                            <p class="self-end w-full text-center mb-1 mt-auto font-semibold">
                                {{ Auth::user()->name }}
                            </p>
                        </div>
                        <div class="h-36 w-72 border border-gray-300 flex flex-col items-center text-center text-sm">
                            {{-- <img src="{{ asset('stamp_approved.png') }}" alt="">
                            <p class="self-end w-full text-center mb-1 mt-auto font-semibold">User name</p> --}}
                        </div>
                        <div class="h-36 w-72 border border-gray-300 flex flex-col items-center text-center text-sm">
                            {{-- <img src="{{ asset('stamp_rejected.png') }}" alt="">
                            <p class="self-end w-full text-center mb-1 mt-auto font-semibold">User name</p> --}}
                        </div>
                    </div>
                </div>

                <div class="col-span-12 flex flex-wrap justify-end my-2">
                    {{--
                    <input role="submit" type="submit" value="{{ __('Submit') }}" class="block text-center cursor-pointer text-white bg-green-600 p-3 duration-300 rounded-sm hover:bg-green-800 w-full sm:w-32">
                    --}}
                </div>        
            </form>
        </section>
    </div>
</main>

<script>
    $(document).ready(function(){
        $('input[name="is_el[]"]').each(function(k, v) {
            if ($(this).data('el') == 'checked') $(this).click();
        });
    })
    function toggleInput(el) {
        let key = null

        switch (el.id) {
            case 'ups':
                key = 'upsNode'
                break;

            case 'stabilizer':
                key = 'stabilizerNode'
                break;

            case 'el1':
                key = 'el1Node'
                break;

            case 'el2':
                key = 'el2Node'
                break;

            case 'el3':
                key = 'el3Node'
                break;

            case 'el4':
                key = 'el4Node'
                break;

            case 'el5':
                key = 'el5Node'
                break;
        
            default:
                console.log('key undefined');
                break;
        }

        if (el.parentNode.classList.contains('active')) {
            el.parentNode.classList.remove('active')

            showNode(key, false)
        } else {
            el.parentNode.classList.add('active')

            showNode(key, true)
        }
    }

    function showNode(key, value) {
        let node = document.getElementById(key)

        if (value) {
            node.classList.remove('hidden')
            node.querySelector('input').disabled = true
            node.classList.add('flex')
        } else {
            node.classList.remove('flex')
            node.querySelector('input').disabled = false
            node.classList.add('hidden')
        }
    }

    function addRow(type) {
        let table = null
        let row = null

        switch (type) {
            case 'electricity':
                table = document.getElementById('elBody')
                row = table.insertRow()
                row.innerHTML = '<td class="w-64 py-1">'+
                                    '<span class="ml-2 pr-1 text-xs col-span-3 flex items-center break-normal">'+
                                        '<a onclick="deleteRow(this)" class="text-red-500 hover:text-gray-500 cursor-pointer mx-1">'+
                                            '<i class="fas fa-minus-circle"></i>'+
                                        '</a>'+
                                        '<input disabled type="text" name="elParam[]" class="w-full text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto">'+
                                    '</span>'+
                                '</td>'+
                                '<td>'+
                                '</td>'+
                                '<td colspan="2" class="flex justify-start pl-2 py-1">'+
                                    '<p id="el5Node" class="flex items-center col-span-2">'+
                                        '<input disabled type="number" name="el[]" class="w-16 text-sm rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto">'+ 
                                        '<input disabled type="text" name="elThreshold[]" class="w-16 text-sm rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto">'+
                                    '</p>'+
                                '</td>'
                break;
            case 'function':
                table = document.getElementById('funcBody')
                row = table.insertRow()
                row.innerHTML = '<td class="w-48">'+
                                    '<span class="flex items-center col-span-2">'+
                                        '<a onclick="deleteRow(this)" class="text-red-500 hover:text-gray-500 cursor-pointer mx-1">'+
                                            '<i class="fas fa-minus-circle"></i>'+
                                        '</a>'+
                                        '<input disabled type="text" name="funcParam[]" class="w-full text-sm rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto">'+ 
                                    '</span>'+
                                '</td>'+
                                '<td>'+
                                    '<label class="custom-label flex ml-3">'+
                                        '<div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">'+
                                            '<input type="radio" name="func['+row.rowIndex+']" value="0" class="hidden">'+
                                            '<svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>'+
                                        '</div>'+
                                    '</label>'+
                                '</td>'+
                                '<td>'+
                                    '<label class="custom-label flex ml-3">'+
                                        '<div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">'+
                                            '<input type="radio" name="func['+row.rowIndex+']" value="1" class="hidden">'+
                                            '<svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>'+
                                        '</div>'+
                                    '</label>'+
                                '</td>'+
                                '<td>'+
                                    '<label class="custom-label flex ml-3">'+
                                        '<div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">'+
                                            '<input type="radio" name="func['+row.rowIndex+']" value="2" class="hidden">'+
                                            '<svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>'+
                                        '</div>'+
                                    '</label>'+
                                '</td>'
                break;
            case 'complete':
                table = document.getElementById('completeBody')
                row = table.insertRow()
                row.innerHTML = '<td class="w-48">'+
                                    '<span class="flex items-center col-span-2">'+
                                        '<a onclick="deleteRow(this)" class="text-red-500 hover:text-gray-500 cursor-pointer mx-1">'+
                                            '<i class="fas fa-minus-circle"></i>'+
                                        '</a>'+
                                        '<input disabled type="text" name="compParam[]" class="w-full text-sm rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto">'+ 
                                    '</span>'+
                                '</td>'+
                                '<td>'+
                                    '<label class="custom-label flex ml-3">'+
                                        '<div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">'+
                                            '<input type="radio" name="complete['+row.rowIndex+']" value="0" class="hidden">'+
                                            '<svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>'+
                                        '</div>'+
                                    '</label>'+
                                '</td>'+
                                '<td>'+
                                    '<label class="custom-label flex ml-3">'+
                                        '<div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">'+
                                            '<input type="radio" name="complete['+row.rowIndex+']" value="1" class="hidden">'+
                                            '<svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>'+
                                        '</div>'+
                                    '</label>'+
                                '</td>'+
                                '<td>'+
                                    '<label class="custom-label flex ml-3">'+
                                        '<div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">'+
                                            '<input type="radio" name="complete['+row.rowIndex+']" value="2" class="hidden">'+
                                            '<svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>'+
                                        '</div>'+
                                    '</label>'+
                                '</td>'
                break;
            case 'performance':
                table = document.getElementById('performBody')
                row = table.insertRow()
                row.innerHTML = '<td class="w-64 border-gray-300 border-r">'+
                                    '<span class="flex items-center col-span-2">'+
                                        '<input disabled type="text" name="performanceParam[]" class="w-full text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto">'+
                                    '</span>'+
                                '</td>'+
                                '<td class="border-gray-300 text-center border-r">'+
                                    '<input disabled type="text" name="setting[]" class="w-16 text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto">'+
                                '</td>'+
                                '<td class="border-gray-300 text-center border-r">'+
                                    '<input disabled type="number" name="value[0][]" class="w-16 text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto">'+
                                '</td>'+
                                '<td class="border-gray-300 text-center border-r">'+
                                    '<input disabled type="number" name="value[1][]" class="w-16 text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto">'+
                                '</td>'+
                                '<td class="border-gray-300 text-center border-r">'+
                                    '<input disabled type="text" name="reference[]" class="w-20 text-xs rounded shadow border-0 focus:ring-2 focus:ring-blue-400 mx-1 text-gray-600 ml-auto">'+
                                '</td>'+
                                '<td>'+
                                    '<label class="custom-label flex ml-3">'+
                                        '<div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">'+
                                            '<input type="radio" name="performanceCondition['+row.rowIndex+']" value="1" class="hidden">'+
                                            '<svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>'+
                                        '</div>'+
                                    '</label>'+
                                '</td>'+
                                '<td>'+
                                    '<label class="custom-label flex ml-3">'+
                                        '<div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">'+
                                            '<input type="radio" name="performanceCondition['+row.rowIndex+']" value="2" class="hidden">'+
                                            '<svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>'+
                                        '</div>'+
                                    '</label>'+
                                '</td>'+
                                '<td class="">'+
                                    '<a onclick="deleteRow(this)" class="text-red-500 hover:text-gray-500 cursor-pointer mx-1">'+
                                        '<i class="fas fa-minus-circle"></i>'+
                                    '</a>'+
                                '</td>'
                break;
            default:
                console.log("table not found");
                break;
        }
    }

    function deleteRow(r) {
        let parent = r.parentNode.parentNode.parentNode.parentNode
        parent.removeChild(r.parentNode.parentNode.parentNode)
    }
</script>
@endsection