<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
{{-- <style>

*{
    box-sizing: border-box;
}

img{
    width: 250px;
    height: 200px;
}
body{
    vertical-align: middle;
}

.row {
  display: flex;
  border-bottom:1pt solid black;
  margin-bottom: 10px;
  margin-top: 10px;
}

.row:last-child{
    border:none;
    margin: none;
}

.column {
  width: 50%;
}

table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%   
}

th, td {
  font-size:13px;
  padding: 10px;
}

.center {
    margin-left: auto;
    margin-right: auto;
}

</style> --}}
<style>
    .tbl {
        margin-bottom: 100px; 
    }
    .tbl td.row {
        width: 50%;
    }
    .tbl2 td{
        vertical-align: top;
    }
    .row2{
        vertical-align: text-top;
    }

</style>
</head>
<body>
    {{-- <script type="text/php">
        if ( isset($pdf) ) {
            $font = $fontMetrics->get_font("helvetica");
            $pdf->page_text(525, 816, "Page {PAGE_NUM} * 2 of {PAGE_COUNT}", $font, 8, array(0,0,0));
        }
    </script> --}}
    @foreach ($complains as $comp)
        <table class="tbl" width='100%'>
            <tr>
                <td class="row">
                    <table class="tbl2">
                        <tr>
                            <td>Ticket ID</td>
                            <td>{{$comp->id}}</td>
                        </tr>
                        <tr>
                            <td>{{__('Created at : ')}}</td>
                            <td>{{$comp->created_at}}</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php
                                $imgCom=isset($comp->comPic) ? url($comp->comPic):public_path('/images/no_image.jpg');
                                ?>
                            <img style="width: 300px;height:200px" src="{{$imgCom}}"></td>
                        </tr>
                        <tr>
                            <td>Barcode: </td>
                            <td>{{ $comp->Barcode}}</td>
                        </tr>
                        <tr>
                            <td>Keterangan: </td>
                            <td>{{$comp->description}}</td>
                        </tr>
                        <tr>
                            <td>Unit : </td>
                            <td>{{ $comp->room->unit}}</td>
                        </tr>
                        <tr>
                            <td>Ruangan: </td>
                            <td>{{$comp->room->room_name}}</td>
                        </tr>
                        <tr>
                            <td style="width: 100px">Tanggal Tiket:</td>
                            <td>{{$comp->date_time}}</td>
                        </tr>
                    </table>
                </td>
                <td class="row2">
                    <table class="tbl2">
                        <tr>
                            <td>Response ID</td>
                            <td>{{$comp->response->id}}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Created at : ')}}</td>
                            <td>{{$comp->response->created_at}}</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php
                                        $imgRes=isset($comp->response->resPic) ? url($comp->comPic):public_path('/images/no_image.jpg');
                                    ?>
                                    <img style="width: 300px;height:200px"src="{{$imgRes}}">
                            </td>
                        </tr>
                        <tr>
                            <td>Barcode: </td>
                            <td>{{$comp->response->barcode}}</td>
                        </tr>
                        <tr>
                            <td>Keterangan :</td>
                            <td>{{ $comp->response->description}}</td>
                        </tr>
                        <tr>
                            <td>Status : </td>
                            <td>{{ $comp->response->status}}</td>
                        </tr>
                        <tr>
                            <td>Status Respon :</td>
                            <td>{{ $comp->response->progress_status}}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Respon : </td>
                            <td>{{ $comp->response->created_at}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    @endforeach
</body>
</html>