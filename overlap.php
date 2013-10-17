<form id="foverlap">

	<?php
	/*
	 require_once('./include/php/excel_reader2.php');

	 $excel = new Spreadsheet_Excel_Reader("overlap.xls"); //, 'WINDOWS-1253');
	 $x=1;
	 echo "<b>If your cohord includes HIV+ patients who contribute data to more than one network please fill-in the corresponding numbers of them for each network or combination of networks </b> <br> <br>"
	 ;
	 while ($x <= $excel->sheets[0]['numRows']) {
	 //	$header = trim($excel->val($x, 1));
	 $numbering = trim($excel->val($x, 1));
	 $question = trim($excel->val($x, 2));
	 //	$datatype = trim($excel->val($x, 4));
	 //	$group_with = trim($excel->val($x, 5));
	 //	$expand_with_value = trim($excel->val($x, 6));
	 //	$url = trim($excel->val($x, 7));

	 if ($question == "" || $question == "Question") {
	 $x++; continue;
	 }
	 if ($numbering == "") {
	 echo "\n <h4>$question</h4> \n";
	 } else {
	 ?>
	 <input type="text" id="<?php echo "overlap_".$numbering; ?>" name="<?php echo "overlap_".$numbering; ?>" size="5" />
	 <?php echo $question; ?>
	 <br>
	 <?php
	 }
	 $x++;
	 }

	 */
	?>

	<style>
		table.stats {
			text-align: center;
			font-family: Verdana, Geneva, Arial, Helvetica, sans-serif ;
			font-weight: normal;
			font-size: 11px;
			color: #fff;
			width: 280px;
			background-color: #666;
			border: 0px;
			border-collapse: collapse;
			border-spacing: 0px;
		}
		table.stats th {
			width: 200px;
		}
		table.stats td {
			background-color: #CCC;
			color: #000;
			padding: 4px;
			text-align: center;
			border: 1px #fff solid;
		}
		table.stats td.hed {
			background-color: #666;
			color: #fff;
			padding: 4px;
			text-align: left;
			border-bottom: 2px #fff solid;
			font-size: 12px;
			font-weight: bold;
		}
	</style>
	<p style="font-size: 11pt;">
		Indicate the network to which you contribute the majority of your data (column "main") and, 
		if applicable,
		<br/>
		other networks to which you send data too (column "other"). 
		<br />
		Optionally, give 
		approximate percentages of your total number of patients  corresponding to each network.
	</p>
	
	<br />
	
	<table class="stats" cellspacing="0">
		<thead>
			<th>Network<br>Name</th>
			<th>Main</th>
			<th>Other</th>
			<th>
			Approximate
			<br>
			Percentage
			<br>
			(optional)
			</th>
		</thead>
		<tbody>
			<tr>
				<td>
				<label>
					COHERE
				</label>
				</td>
				<td>
				<input type="radio" id="default_network" name="default_network" data-important="1" value="cohere" />
				</td>
				<td>
				<input type="checkbox" name="cohere_checkbox" id="cohere_checkbox" />
				</td>
				<td>
				<input name="cohere_percent" id="cohere_percent" size="4" onblur="check_num(this);" onkeypress="return check_number(this, event);"/>
				%
				</td>
			</tr>
			<tr>
				<td>
				<label>
					EuroSIDA
				</label>
				</td>
				<td>
				<input type="radio" id="default_network" name="default_network" data-important="1" value="eurosida" />
				</td>
				<td>
				<input type="checkbox" name="eurosida_checkbox" id="eurosida_checkbox" />
				</td>
				<td>
				<input name="eurosida_percent" id="eurosida_percent" size="4" onblur="check_num(this);" onkeypress="return check_number(this, event);" />
				%
				</td>
			</tr>
			<tr>
				<td>
				<label>
					CASCADE
				</label>
				</td>
				<td>
				<input type="radio" id="default_network" name="default_network" data-important="1" value="cascade" />
				</td>
				<td>
				<input type="checkbox" name="cascade_checkbox" id="cascade_checkbox" />
				</td>
				<td>
				<input name="cascade_percent" id="cascade_percent" size="4" onblur="check_num(this);" onkeypress="return check_number(this, event);" />
				%
				</td>
			</tr>
			<tr>
				<td>
				<label>
					PENTA
				</label>
				</td>
				<td>
				<input type="radio" id="default_network" name="default_network" data-important="1" value="penta" />
				</td>
				<td>
				<input type="checkbox" name="penta_checkbox" id="penta_checkbox" />
				</td>
				<td>
				<input name="penta_percent" id="penta_percent" size="4" onblur="check_num(this);" onkeypress="return check_number(this, event);"/>
				%
				</td>
			</tr>
			<tr>
				<td>
				<label>
					EPPICC
				</label>
				</td>
				<td>
				<input type="radio" id="default_network" name="default_network" data-important="1" value="eppicc" />
				</td>
				<td>
				<input type="checkbox" name="eppicc_checkbox" id="eppicc_checkbox" />
				</td>
				<td>
				<input name="eppicc_percent" id="eppicc_percent" size="4" onblur="check_num(this);"/>
				%
				</td>
			</tr>
			<tr>
				<td>
				<label>
					ART-CC
				</label>
				</td>
				<td>
				<input type="radio" id="default_network" name="default_network" data-important="1" value="art-cc" />
				</td>
				<td>
				<input type="checkbox" name="art-cc_checkbox" id="art-cc_checkbox" />
				</td>
				<td>
				<input name="art-cc_percent" id="art-cc_percent" size="4" onblur="check_num(this);"/>
				%
				</td>
			</tr>
		</tbody>
	</table>
	<input type="radio" id="default_network" name="default_network" data-important="1" value="" checked="checked" style="display:none" />
</form>

<script type=text/javascript>
	$("[name='default_network']").click(function() {
		value = $("[name='default_network']:checked").val(); 
		$("[name='" + value + "_checkbox']").prop("checked", true);
		calculate_progress();
	});
	$("[name$='_percent']").keypress(function() {
		id = $(this).attr("name").replace("percent", "checkbox");
		$("#" + id).prop("checked", true);
	});
	
	$("[name*='percent']").blur(function() {
		var num = parseFloat($(this).val());
		var temp = $(this).val().split(".");
		if (temp.length > 2) {
			alert("The percentage must be a number between 0 and 100!");
			$(this).focus();
		} else {
		if (num == NaN) {
		} else {
			if ((num < 0) || (num > 100)) {
				alert("The percentage must be a number between 0 and 100!");
				$(this).focus();
			}
		} 
		if ($(this).val() !== "") {
			$(this).val(num);
		}
		
		}
	});
</script>
