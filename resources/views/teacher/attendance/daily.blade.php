@extends('layouts.dashboard')
@include('teacher.functions.attendance.index')

@section('mycss')

@endsection



@section('principal')

							<div class="form-group row">
                                <label class="col-md-4 col-sm-3 control-label">Current Search Date</label>
                                    <div class="col-md-4 col-sm-6">
                                        <input type="date" class="form-control" value="<?php echo $date; ?>" onchange="getDateOnChange()" id="datebox"/>
                                    </div>
                            </div>

<table class="table" border="1" cellpadding="10" cellspacing="5">
	<tr>
		<td>...</td>
		<td colspan="2"> 8.00 - 8-40 am </td>
		<td colspan="2"> 8.40 - 9.20 am</td>
		<td colspan="2"> 9.20 - 10.00 am</td>
        <td colspan="2"> 10.00 - 10.40 am</td>
        <td colspan="2"> 10.40 - 11.20 am</td>
        <td colspan="2"> 12.00 - 12.40 pm</td>
        <td colspan="2"> 12.40 - 13.20 pm</td>
        <td colspan="2"> 13.20 - 14.00 pm</td>
        <td colspan="2"> 14.00 - 14.40 pm</td>
	</tr>

	<tr>
		<td><b style="font-size: 18px;"> JS1A </b></td>
		<td colspan="2"><?php echo getSubTime($date, '8:05:00', 1, 1); ?></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:45:00', 1, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '9:25:00',  1, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:05:00',  1, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:45:00',  1, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:00:00',  1, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:40:00',  1, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '13:20:00',  1, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '14:00:00',  1, 1); ?></td>
	</tr>

	<tr>
		<td><b style="font-size: 18px;"> JS1B </b></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:05:00', 2, 1); ?></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:45:00', 2, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '9:25:00',  2, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:05:00',  2, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:45:00',  2, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:00:00',  2, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:40:00',  2, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '13:20:00',  2, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '14:00:00',  2, 1); ?></td>
	</tr>

	<tr>
		<td><b style="font-size: 18px;"> JS2A </b></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:05:00', 3, 1); ?></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:45:00', 3, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '9:25:00',  3, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:05:00',  3, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:45:00',  3, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:00:00',  3, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:40:00',  3, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '13:20:00',  3, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '14:00:00',  3, 1); ?></td>
	</tr>

	<tr>
		<td><b style="font-size: 18px;"> JS2B </b></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:05:00', 4, 1); ?></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:45:00', 4, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '9:25:00',  4, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:05:00',  4, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:45:00',  4, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:00:00',  4, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:40:00',  4, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '13:20:00',  4, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '14:00:00',  4, 1); ?></td>
	</tr>

	<tr>
		<td><b style="font-size: 18px;"> JS3A </b></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:05:00', 5, 1); ?></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:45:00', 5, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '9:25:00',  5, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:05:00',  5, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:45:00',  5, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:00:00',  5, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:40:00',  5, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '13:20:00',  5, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '14:00:00',  5, 1); ?></td>
	</tr>

	<tr>
		<td><b style="font-size: 18px;"> JS3B </b></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:05:00', 6, 1); ?></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:45:00', 6, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '9:25:00',  6, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:05:00', 6, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:45:00',  6, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:00:00',  6, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:40:00',  6, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '13:20:00',  6, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '14:00:00',  6, 1); ?></td>
	</tr>

	<tr>
		<td><b style="font-size: 18px;"> SS1A </b></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:05:00', 7, 1); ?></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:45:00', 7, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '9:25:00',  7, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:05:00', 7, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:45:00',  7, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:00:00',  7, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:40:00',  7, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '13:20:00',  7, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '14:00:00',  7, 1); ?></td>
	</tr>

	<tr>
		<td><b style="font-size: 18px;"> SS1B </b></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:05:00', 8, 1); ?></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:45:00', 8, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '9:25:00',  8, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:05:00', 8, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:45:00',  8, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:00:00',  8, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:40:00',  8, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '13:20:00',  8, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '14:00:00',  8, 1); ?></td>
	</tr>

	<tr>
		<td><b style="font-size: 18px;"> SS1C </b></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:05:00', 13, 1); ?></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:45:00', 13, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '9:25:00',  13, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:05:00', 13, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:45:00',  13, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:00:00',  13, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:40:00',  13, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '13:20:00',  13, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '14:00:00',  13, 1); ?></td>
	</tr>

	<tr>
		<td><b style="font-size: 18px;"> SS2A </b></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:05:00', 9, 1); ?></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:45:00', 9, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '9:25:00',  9, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:05:00', 9, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:45:00',  9, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:00:00',  9, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:40:00',  9, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '13:20:00',  9, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '14:00:00',  9, 1); ?></td>
	</tr>

	<tr>
		<td><b style="font-size: 18px;"> SS2B </b></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:05:00', 10, 1); ?></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:45:00', 10, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '9:25:00',  10, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:05:00', 10, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:45:00',  10, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:00:00',  10, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:40:00',  10, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '13:20:00',  10, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '14:00:00',  10, 1); ?></td>
	</tr>

	<tr>
		<td><b style="font-size: 18px;"> SS3A </b></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:05:00', 11, 1); ?></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:45:00', 11, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '9:25:00',  11, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:05:00', 11, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:45:00',  11, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:00:00',  11, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:40:00',  11, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '13:20:00',  11, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '14:00:00',  11, 1); ?></td>
	</tr>

	<tr>
		<td><b style="font-size: 18px;"> SS3B </b></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:05:00', 12, 1); ?></td>
		<td colspan="2"> <?php echo getSubTime($date, '8:45:00', 12, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '9:25:00',  12, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:05:00', 12, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '10:45:00',  12, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:00:00',  12, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '12:40:00',  12, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '13:20:00',  12, 1); ?></td>
		<td colspan="2"><?php echo getSubTime($date, '14:00:00',  12, 1); ?></td>
	</tr>

	
</table>

@endsection

@section('myscript')
<script>
function getDateOnChange(){
        let datebox = $('#datebox').val();
		window.location.replace("/pdaily/"+datebox);
}
</script>
@endsection

