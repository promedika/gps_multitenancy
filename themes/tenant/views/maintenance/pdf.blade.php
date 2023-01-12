<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ "Form Preventive Maintenance ID".$maintenance->id }}</title>
</head>

<style>
    *{
        font-family: "DejaVu Sans Mono";
    }
    body {
        background-size: contain;
        font-family: Arial, Helvetica, sans-serif, "DejaVu Sans Mono";
        padding: 60px 10px 50px 10px;
        margin: 0px;
    }
    #main-table th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
    .table-inside table, .table-inside td {
        border: 0px;
    }
    .title {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10pt;
        font-weight: 600;
        background-color: rgb(209, 213, 219);
    }
</style>
<body>
    <div class="content">
        <table id="main-table" style="width: 100%;">
            <tbody>
                <tr style="text-align: center;">
                    <td rowspan="2">
                       <h4>Pekerjaan</h4> 
                    </td>
                    <td rowspan="2">
                        <span style="font-size: 8pt;">Preventive Maintenance</span>
                    </td>
                    <td rowspan="2">
                        <h3>{{ $device->standard_name }}</h3>
                    </td>
                    <td style="text-align: center; font-size: 8pt; display: flex; height: 50px;">
                        <p>
                            Date:
                            {{ date('d-m-Y', strtotime($maintenance->created_at)) }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">
                        <div >
                            <img height="50" src="{{ public_path('gps_logo.png') }}">
                        </div>
                        @if (explode('.', $_SERVER['HTTP_HOST'])[0] == 'rsudkoja') 
                        <div> 
                            <img height="50" src="{{ public_path('logo Koja.png') }}">
                        </div>
                        @elseif ((explode('.', $_SERVER['HTTP_HOST'])[0] == 'rsudkramatjati'))
                        <div>
                            <img height="70" src="{{ public_path('logo Kramat Jati.png') }}">
                        </div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table style="width: 100%; font-size: 8pt;" class="table-inside">
                            <tbody>
                                <tr>
                                    <td style="width: 206px;">Nama Rumah Sakit / Klinik</td>
                                    <td style="width: 206px;">
                                        : {{ app('currentTenant')->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 206px;">Lokasi</td>
                                    <td style="width: 206px;">
                                        : {{ $maintenance->inventory->room->room_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 206px;">Merk</td>
                                    <td style="width: 206px;">
                                        : {{ $maintenance->inventory->identity->brand->brand }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 206px;">Model</td>
                                    <td style="width: 206px;">
                                        : {{ $maintenance->inventory->identity->model }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td colspan="2">
                        <table style="width: 100%; font-size: 8pt;" class="table-inside">
                            <tbody>
                                <tr>
                                    <td style="width: 154px;">Nomor Inventory</td>
                                    <td style="width: 206px;">
                                        : {{ $maintenance->inventory->barcode }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 154px;">S / N</td>
                                    <td style="width: 206px;">
                                        : 
                                        @if ($maintenance->inventory->identity->serial)
                                            {{ $maintenance->inventory->identity->serial }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 154px;">Nomor Kalibrasi</td>
                                    <td style="width: 206px;">
                                        : 
                                        @if ($maintenance->inventory->latest_record->label)
                                            {{ $maintenance->inventory->latest_record->label }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 154px;">Tanggal Kalibrasi</td>
                                    <td style="width: 206px;">
                                        : 
                                        @if ($maintenance->inventory->latest_record->cal_date)
                                            {{ date('d-m-Y', strtotime($maintenance->inventory->latest_record->cal_date)) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="title">
                        Kondisi Lingkungan
                    </td>
                    <td colspan="2" class="title">
                        Kondisi Kelistrikan
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 8pt;">
                        <table style="width: 100%; font-size: 8pt;" class="table-inside">
                            <tbody>
                                <tr>
                                    <td>Suhu</td>
                                    <td>: {{ isset($raw->temperature) ? $raw->temperature : '' }} °C ( 21 - 25 )</td>
                                </tr>
                                <tr>
                                    <td>Kelembaban</td>
                                    <td>: {{ isset($raw->humidity) ? $raw->humidity : '' }} % ( 50 - 60 ))</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td colspan="2" style="font-size: 8pt;">
                        <table style="width: 100%; font-size: 8pt;" class="table-inside">
                            <tbody>
                                <tr>
                                    <td>Tegangan Jala - Jala</td>
                                    <td>: {{ isset($raw->voltage) ? $raw->voltage : '' }} V</td>
                                    <td>UPS</td>
                                    <td>
                                        @isset($raw->is_ups)
                                            : {{ isset($raw->ups) ? $raw->ups : '' }} V
                                        @endisset
                                        @empty($raw->is_ups)
                                            : -
                                        @endempty
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>Stabilizer</td>
                                    <td>
                                        @isset($raw->is_stabilizer)
                                            : {{ isset($raw->stabilizer) ? $raw->stabilizer : '' }} V
                                        @endisset
                                        @empty($raw->is_stabilizer)
                                            : -
                                        @endempty
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="title">
                        Alat Kerja yang Digunakan
                    </td>
                    <td colspan="2" class="title">
                        Pemeriksaan Keamanan Lain
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" colspan="2" style="font-size: 8pt;">
                        <table style="width: 100%; font-size: 8pt;" class="table-inside">
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
                                    <td>1. </td>
                                    <td>ESA</td>
                                    <td>
                                        <input disabled type="checkbox" name="is_tools[0]" @if (isset($raw->is_tools[0]) && $raw->is_tools[0] == "on")
                                            checked
                                        @endif>
                                    </td>
                                    <td>6.<td>
                                    <td>
                                        {{ isset($raw->tools[5]) ? $raw->tools[5] : '' }}
                                    </td>
                                    <td>
                                        <input disabled type="checkbox" name="is_tools[5]" @if (isset($raw->is_tools[5]) && $raw->is_tools[5] == "on")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2. </td>
                                    <td>Thermohygrometer</td>
                                    <td>
                                        <input disabled type="checkbox" name="is_tools[1]" @if (isset($raw->is_tools[1]) && $raw->is_tools[1] == "on")
                                            checked
                                        @endif>
                                    </td>
                                    <td>7.<td>
                                    <td>
                                        {{ isset($raw->tools[6]) ? $raw->tools[6] : '' }}
                                    </td>
                                    <td>
                                        <input disabled type="checkbox" name="is_tools[6]" @if (isset($raw->is_tools[6]) && $raw->is_tools[6] == "on")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3. </td>
                                    <td>Toolset</td>
                                    <td>
                                        <input disabled type="checkbox" name="is_tools[2]" @if (isset($raw->is_tools[2]) && $raw->is_tools[2] == "on")
                                            checked
                                        @endif>
                                    </td>
                                    <td>8.<td>
                                    <td>
                                        {{ isset($raw->tools[7]) ? $raw->tools[7] : '' }}
                                    </td>
                                    <td>
                                        <input disabled type="checkbox" name="is_tools[7]" @if (isset($raw->is_tools[7]) && $raw->is_tools[7] == "on")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4. </td>
                                    <td>Cleaning Kit</td>
                                    <td>
                                        <input disabled type="checkbox" name="is_tools[3]" @if (isset($raw->is_tools[3]) && $raw->is_tools[3] == "on")
                                            checked
                                        @endif>
                                    </td>
                                    <td>9.<td>
                                    <td>
                                        {{ isset($raw->tools[8]) ? $raw->tools[8] : '' }}
                                    </td>
                                    <td>
                                        <input disabled type="checkbox" name="is_tools[8]" @if (isset($raw->is_tools[8]) && $raw->is_tools[8] == "on")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5. </td>
                                    <td>Multitester</td>
                                    <td>
                                        <input disabled type="checkbox" name="is_tools[4]" @if (isset($raw->is_tools[4]) && $raw->is_tools[4] == "on")
                                            checked
                                        @endif>
                                    </td>
                                    <td>10.<td>
                                    <td>
                                        {{ isset($raw->tools[9]) ? $raw->tools[9] : '' }}
                                    </td>
                                    <td>
                                        <input disabled type="checkbox" name="is_tools[9]" @if (isset($raw->is_tools[9]) && $raw->is_tools[9] == "on")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td colspan="2" style="font-size: 8pt;">
                        <table style="width: 100%; font-size: 8pt;" class="table-inside">
                            <tbody>
                                <tr>
                                    <td>Penempatan Alat : </td>
                                    <td>
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->placement) && $raw->placement == "good")
                                            checked
                                        @endif>
                                        Baik
                                    </td>
                                    <td>
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->placement) && $raw->placement == "bad")
                                            checked
                                        @endif>
                                        Buruk
                                    </td>
                                    <td>
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->placement) && $raw->placement == "none")
                                            checked
                                        @endif>
                                        N/A
                                    </td>
                                </tr>
                                <tr>
                                    <td>Roda / Troli / Bracket : </td>
                                    <td>
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->extra) && $raw->extra == "good")
                                            checked
                                        @endif>
                                        Baik
                                    </td>
                                    <td>
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->extra) && $raw->extra == "bad")
                                            checked
                                        @endif>
                                        Buruk
                                    </td>
                                    <td>
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->extra) && $raw->extra == "none")
                                            checked
                                        @endif>
                                        N/A
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0px;" colspan="2">
                        <table style="width: 100%; font-size: 8pt;" class="table-inside">
                            <tbody>
                                <tr style="font-size: 10pt;" class="title">
                                    <td>
                                        Pemeriksaan Fisik
                                    </td>
                                    <td style="text-align: center;">B</td>
                                    <td style="text-align: center;">C/RR</td>
                                    <td style="text-align: center;">RB</td>
                                    <td style="text-align: center;">Bersih</td>
                                    <td style="text-align: center;">Kotor</td>
                                </tr>
                                <tr>
                                    <td>
                                        Main Unit 
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->physic_main) && $raw->physic_main == "0")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->physic_main) && $raw->physic_main == "1")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->physic_main) && $raw->physic_main == "2")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->condition_main) && $raw->condition_main == "clean")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->condition_main) && $raw->condition_main == "dirty")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Roda / Troli / Bracket  
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->physic_extra) && $raw->physic_extra == "0")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->physic_extra) && $raw->physic_extra == "1")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->physic_extra) && $raw->physic_extra == "2")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->condition_extra) && $raw->condition_extra == "clean")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->condition_extra) && $raw->condition_extra == "dirty")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="title">
                        Pemeriksaan Keamanan Listrik
                    </td>
                    <td style="padding: 0px; vertical-align: text-top;" colspan="2" rowspan="3">
                        <table style="width: 100%; font-size: 8pt;" class="table-inside">
                            <tbody>
                                <?php 
                                    if (isset($raw->func)) {
                                        $raw->func = (array) $raw->func;
                                    }
                                ?>
                                <tr style="font-size: 10pt;" class="title">
                                    <td>
                                        Pemeriksaan Fungsi Alat
                                    </td>
                                    <td style="text-align: center;">N/A</td>
                                    <td style="text-align: center;">Baik</td>
                                    <td style="text-align: center;">Tidak</td>
                                </tr>
                                <tr>
                                    <td>
                                        Display / Monitor
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->func[0]) && $raw->func[0] == "0")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->func[0]) && $raw->func[0] == "1")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->func[0]) && $raw->func[0] == "2")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Switch On / Off
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->func[1]) && $raw->func[1] == "0")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->func[1]) && $raw->func[1] == "1")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->func[1]) && $raw->func[1] == "2")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Control / Setting
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->func[2]) && $raw->func[2] == "0")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->func[2]) && $raw->func[2] == "1")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->func[2]) && $raw->func[2] == "2")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Keypad
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->func[3]) && $raw->func[3] == "0")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->func[3]) && $raw->func[3] == "1")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->func[3]) && $raw->func[3] == "2")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Timer
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->func[4]) && $raw->func[4] == "0")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->func[4]) && $raw->func[4] == "1")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->func[4]) && $raw->func[4] == "2")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                                @isset($raw->funcParam)
                                    @foreach ($raw->funcParam as $i => $p)
                                        <tr>
                                            <td>
                                                {{ $p }}
                                            </td>
                                            <td style="text-align: center;">
                                                <input disabled type="checkbox" 
                                                @if ($raw->func[4 + $i] == "0")
                                                    checked
                                                @endif>
                                            </td>
                                            <td style="text-align: center;">
                                                <input disabled type="checkbox" 
                                                @if ($raw->func[4 + $i] == "1")
                                                    checked
                                                @endif>
                                            </td>
                                            <td style="text-align: center;">
                                                <input disabled type="checkbox" 
                                                @if ($raw->func[4 + $i] == "2")
                                                    checked
                                                @endif>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 8pt;">
                        <table style="width: 100%; font-size: 8pt;" class="table-inside">
                            <tbody>
                                @php $raw->is_el = isset($raw->is_el) ? $raw->is_el : []; @endphp
                                <tr>
                                    <td>
                                        <span style="text-align: left;">Tahanan hubungan pertanahan</span>
                                    </td>
                                    <td>
                                        {{ isset($raw->el[0]) && $raw->el[0] }}
                                    </td>
                                    <td>
                                        <p>
                                            &le;0,2Ω
                                        </p>
                                    </td>
                                    <td>
                                        <input disabled type="checkbox" 
                                        @if (in_array('el1', $raw->is_el))
                                            checked
                                        @endif>
                                        N/A
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="text-align: left;">Arus bocor Casis dengan Pembumian</span>
                                    </td>
                                    <td>
                                        {{ isset($raw->el[1]) && $raw->el[1] }}
                                    </td>
                                    <td>
                                        <p>
                                            &le;100µA
                                        </p>
                                    </td>
                                    <td>
                                        <input disabled type="checkbox" 
                                            @if (in_array('el2', $raw->is_el))
                                                checked
                                            @endif>
                                        N/A
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="text-align: left;">Arus bocor Casis tanpa Pembumian</span>
                                    </td>
                                    <td>
                                        {{ isset($raw->el[2]) && $raw->el[2] }}
                                    </td>
                                    <td>
                                        <p>
                                            &le;500µA
                                        </p>
                                    </td>
                                    <td>
                                        <input disabled type="checkbox" 
                                        @if (in_array('el3', $raw->is_el))
                                            checked
                                        @endif>
                                        N/A
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="text-align: left;">Arus bocor Casis Polaritas terbalik dengan Pembumian</span>
                                    </td>
                                    <td>
                                        {{ isset($raw->el[3]) && $raw->el[3] }}
                                    </td>
                                    <td>
                                        <p>
                                            &le;100µA
                                        </p>
                                    </td>
                                    <td>
                                        <input disabled type="checkbox" 
                                        @if (in_array('el4', $raw->is_el))
                                            checked
                                        @endif>
                                        N/A
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="text-align: left;">Arus bocor Casis Polaritas terbalik tanpa Pembumian</span>
                                    </td>
                                    <td>
                                        {{ isset($raw->el[4]) && $raw->el[4] }}
                                    </td>
                                    <td>
                                        <p>
                                            &le;500µA
                                        </p>
                                    </td>
                                    <td>
                                        <input disabled type="checkbox" 
                                        @if (in_array('el5', $raw->is_el))
                                            checked
                                        @endif>
                                        N/A
                                    </td>
                                </tr>
                                @isset($raw->elParam)
                                    @foreach ($raw->elParam as $i => $p)
                                        <tr>
                                            <td>
                                                <span style="text-align: left;">{{ $p }}</span>
                                            </td>
                                            <td>
                                                {{ $raw->el[count($raw->el) + $i] }}
                                            </td>
                                            <td>
                                                <p>
                                                    {{ $raw->elThreshold[$i] }}
                                                </p>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0px; vertical-align: text-top;" colspan="2">
                        <table style="width: 100%; font-size: 8pt;" class="table-inside">
                            <tbody>
                                <tr style="font-size: 10pt;" class="title">
                                    <td>
                                        Kelengkapan Alat
                                    </td>
                                    <td style="text-align: center;">N/A</td>
                                    <td style="text-align: center;">Baik</td>
                                    <td style="text-align: center;">Tidak</td>
                                </tr>
                                <tr>
                                    <td>
                                        Power cord / Adaptor
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->complete[0]) && $raw->complete[0] == "0")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->complete[0]) && $raw->complete[0] == "1")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->complete[0]) && $raw->complete[0] == "2")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                                @isset($raw->compParam)
                                    @foreach ($raw->compParam as $i => $p)
                                        <tr>
                                            <td>
                                                {{ $p }}
                                            </td>
                                            <td style="text-align: center;">
                                                <input disabled type="checkbox" 
                                                @if ($raw->complete[0 + $i] == "0")
                                                    checked
                                                @endif>
                                            </td>
                                            <td style="text-align: center;">
                                                <input disabled type="checkbox" 
                                                @if ($raw->complete[0 + $i] == "1")
                                                    checked
                                                @endif>
                                            </td>
                                            <td style="text-align: center;">
                                                <input disabled type="checkbox" 
                                                @if ($raw->complete[0 + $i] == "2")
                                                    checked
                                                @endif>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0px; vertical-align: text-top;" colspan="4">
                        <table style="width: 100%; font-size: 8pt;" class="table-inside">
                            <tbody>
                                <tr style="font-size: 10pt;" class="title">
                                    <td>
                                        Pemeriksaan Kinerja Alat
                                    </td>
                                    <td style="text-align: center;">Setting</td>
                                    <td style="text-align: center;">Terukur I</td>
                                    <td style="text-align: center;">Terukur II</td>
                                    <td style="text-align: center;">Nilai Acuan</td>
                                    <td style="text-align: center;">Baik</td>
                                    <td style="text-align: center;">Tidak</td>
                                </tr>
                                @isset($raw->performanceParam)
                                    @foreach ($raw->performanceParam as $i => $p)
                                        <tr>
                                            <td style="text-align: left;" >{{ isset($p) ? $p : '' }}</td>
                                            <td style="text-align: center;" >{{ isset($raw->setting[$i]) ? $raw->setting[$i] : '' }}</td>
                                            <td style="text-align: center;" >{{ isset($raw->value[0][$i]) ? $raw->value[0][$i] : '' }}</td>
                                            <td style="text-align: center;" >{{ isset($raw->value[1][$i]) ? $raw->value[1][$i] : '' }}</td>
                                            <td style="text-align: center;" >{{ isset($raw->reference[$i]) ? $raw->reference[$i] : '' }}</td>
                                            <td style="text-align: center;">
                                                <input disabled type="checkbox" 
                                                @if (isset($raw->performanceCondition[$i]) && $raw->performanceCondition[$i] == "1")
                                                    checked
                                                @endif>
                                            </td>
                                            <td style="text-align: center;">
                                                <input disabled type="checkbox" 
                                                @if (isset($raw->performanceCondition[$i]) && $raw->performanceCondition[$i] == "0")
                                                    checked
                                                @endif>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0px; vertical-align: text-top;" colspan="4">
                        <table style="width: 100%; font-size: 8pt;" class="table-inside">
                            <tbody>
                                <tr style="font-size: 10pt;" class="title">
                                    <td style="color: rgb(209, 213, 219);">
                                        Pemeriksaan Kinerja Alat
                                    </td>
                                    <td style="text-align: center;">Setting</td>
                                    <td style="text-align: center;">Terukur I</td>
                                    <td style="text-align: center;">Terukur II</td>
                                    <td style="text-align: center;">Nilai Acuan</td>
                                    <td style="text-align: center;">Baik</td>
                                    <td style="text-align: center;">Tidak</td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;" >Battery</td>
                                    <td style="text-align: center;" >{{ isset($raw->batterySetting[0]) ? $raw->batterySetting[0] : '' }} VDC</td>
                                    <td style="text-align: center;" >{{ isset($raw->battery[0][0]) ? $raw->battery[0][0] : '' }}</td>
                                    <td style="text-align: center;" >{{ isset($raw->battery[1][0]) ? $raw->battery[1][0] : '' }}</td>
                                    <td style="text-align: center;" >&le;10%</td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->batteryCondition[0]) && $raw->batteryCondition[0] == "0")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->batteryCondition[0]) && $raw->batteryCondition[0] == "1")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0px; vertical-align: text-top;" colspan="4">
                        <table style="width: 100%; font-size: 8pt;" class="table-inside">
                            <tbody>
                                <tr>
                                    <td style="font-size: 10pt;" class="title">
                                        Hasil Pemeriksaan
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->inspectionResult) && $raw->inspectionResult == "1")
                                            checked
                                        @endif>
                                        Alat Bekerja dengan Baik
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->inspectionResult) && $raw->inspectionResult == "0")
                                            checked
                                        @endif>
                                        Alat Tidak Bekerja dengan Baik
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0px; vertical-align: text-top;" colspan="2">
                        <table style="width: 100%; font-size: 8pt;" class="table-inside">
                            <tbody>
                                <?php 
                                    if (isset($raw->maintenance)) {
                                        $raw->maintenance = (array) $raw->maintenance;
                                    }
                                ?>
                                <tr style="font-size: 10pt;" class="title">
                                    <td>
                                        Pemeliharaan Alat
                                    </td>
                                    <td style="text-align: center;">Dilakukan</td>
                                    <td style="text-align: center;">Tidak</td>
                                </tr>
                                <tr>
                                    <td>
                                        Pembersihan Main Unit
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->maintenance[0]) && $raw->maintenance[0] == "1")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->maintenance[0]) && $raw->maintenance[0] == "0")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Pembersihan Aksesoris / Kelengkapan alat
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->maintenance[1]) && $raw->maintenance[1] == "1")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->maintenance[1]) && $raw->maintenance[1] == "0")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Pemantauan fungsi alat
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->maintenance[2]) && $raw->maintenance[2] == "1")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->maintenance[2]) && $raw->maintenance[2] == "0")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Pemantauan kinerja alat
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->maintenance[3]) && $raw->maintenance[3] == "1")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->maintenance[3]) && $raw->maintenance[3] == "0")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Penggantian consumable {{ $raw->cons }}
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->maintenance[4]) && $raw->maintenance[4] == "1")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->maintenance[4]) && $raw->maintenance[4] == "0")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Lubricating &/ Tighting
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->maintenance[5]) && $raw->maintenance[5] == "1")
                                            checked
                                        @endif>
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->maintenance[5]) && $raw->maintenance[5] == "0")
                                            checked
                                        @endif>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style="padding: 0px; vertical-align: text-top;" colspan="2">
                        <table style="width: 100%; font-size: 8pt;" class="table-inside">
                            <tbody>
                                <tr style="font-size: 10pt;" class="title">
                                    <td style="text-align: center;">Stok</td>
                                    <td>Konsumabel</td>
                                    <td style="text-align: center;">Ada</td>
                                    <td style="text-align: center;">Tidak</td>
                                    <td style="text-align: center;">Habis</td>
                                </tr>
                                @isset($raw->stock)
                                    @foreach ($raw->stock as $i => $p)
                                        <tr>
                                            <td>
                                                {{ $p }}
                                            </td>
                                            <td>
                                                {{ isset($raw->consumables[$i]) ? $raw->consumables[$i] : '' }}
                                            </td>
                                            <td style="text-align: center;">
                                                <input disabled type="checkbox" 
                                                @if (isset($raw->consCondition[$i]) && $raw->consCondition[$i] == "0")
                                                    checked
                                                @endif>
                                            </td>
                                            <td style="text-align: center;">
                                                <input disabled type="checkbox" 
                                                @if (isset($raw->consCondition[$i]) && $raw->consCondition[$i] == "1")
                                                    checked
                                                @endif>
                                            </td>
                                            <td style="text-align: center;">
                                                <input disabled type="checkbox" 
                                                @if (isset($raw->consCondition[$i]) && $raw->consCondition[$i] == "2")
                                                    checked
                                                @endif>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0px; vertical-align: text-top;" colspan="4">
                        <table style="width: 100%; font-size: 8pt;" class="table-inside">
                            <tbody>
                                <tr>
                                    <td style="font-size: 10pt;" class="title">
                                        Hasil Maintenance
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->maintenanceResult) && $raw->maintenanceResult == "1")
                                            checked
                                        @endif>
                                        Alat Berfungsi dengan Baik
                                    </td>
                                    <td style="text-align: center;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->maintenanceResult) && $raw->maintenanceResult == "0")
                                            checked
                                        @endif>
                                        Alat Tidak Dapat Berfungsi dengan Baik
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0px; vertical-align: text-top;" colspan="4">
                        <table style="width: 100%; font-size: 8pt;" class="table-inside">
                            <tbody>
                                <tr>
                                    <td style="font-size: 10pt;" class="title" rowspan="2">
                                        Rekomendasi Hasil Maintenance
                                    </td>
                                    <td style="text-align: left;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->recommendation) && $raw->recommendation == "0")
                                            checked
                                        @endif>
                                        Alat Dapat Digunakan
                                    </td>
                                    <td style="text-align: left;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->recommendation) && $raw->recommendation == "2")
                                            checked
                                        @endif>
                                        Alat Tidak Dapat Digunakan
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->recommendation) && $raw->recommendation == "1")
                                            checked
                                        @endif>
                                        Alat Perlu Dikalibrasi
                                    </td>
                                    <td style="text-align: left;">
                                        <input disabled type="checkbox" 
                                        @if (isset($raw->recommendation) && $raw->recommendation == "3")
                                            checked
                                        @endif>
                                        Alat Harus Dikalibrasi
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0px; vertical-align: text-top;" colspan="4">
                        <table style="width: 100%; font-size: 8pt;" class="table-inside">
                            <tbody>
                                <tr>
                                    <td style="font-size: 10pt;" class="title">
                                        Catatan
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        {{ isset($raw->notes) ? $raw->notes : '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0px; vertical-align: text-top;" colspan="4">
                        <table style="width: 100%; font-size: 10pt;">
                            <tbody>
                                <tr class="title table-inside">
                                    <td style="text-align: center;">
                                        Teknisi Pelaksana
                                    </td>
                                    <td style="text-align: center;">
                                        Penanggung Jawab Alat / Ruangan
                                    </td>
                                    <td style="text-align: center;">
                                        IPSRS
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; font-weight: 600;">
                                        <img style="width: 200px; height: 100px;" src="{{ public_path('stamp_submitted.png') }}" alt="">
                                        <div>
                                            {{ $maintenance->user->name }}
                                        </div>
                                    </td>
                                    <td style="text-align: center; font-weight: 600;">
                                        <img style="width: 200px; height: 100px;" src="{{ public_path('stamp_none.png') }}" alt="">
                                        <div>
                                            &nbsp;
                                        </div>
                                    </td>
                                    <td style="text-align: center; font-weight: 600;">
                                        <img style="width: 200px; height: 100px;" src="{{ public_path('stamp_none.png') }}" alt="">
                                        <div>
                                            &nbsp;
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>