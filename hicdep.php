<?php

error_reporting(0);

require_once('./include/php/excel_reader2.php');

// Read DataTypes

$excel = new Spreadsheet_Excel_Reader("datatypes.xls"); //, 'WINDOWS-1253');

$x = 2;
$datatypes = array();
while ($x <= $excel->sheets[0]['numRows']) {
	$id = trim($excel->val($x, 1));
	$datatypes[$id] = "";
	$all_values = trim($excel->val($x, 2));
	$items = array();
	$items = explode("\", ", $all_values);
	for ($i=0; $i<count($items); $i++) {
		$item = array();
		$item = explode("-", $items[$i]);
		$datatypes[$id] .= "<option value='".trim($item[0])."'> ";
		$datatypes[$id] .= str_replace("\"", "", trim($item[1]));
		$datatypes[$id] .= " </option>\n";
	}
	$x++;
}

// Read Questions

?>

<form id="fhicdep">

<div id="hicdep_tabs" class="add_shadow_tabs">
<ul>
	<li><a href="#page1">Basic info</a></li>
	<li><a href="#page2">ART and other Meds</a></li>
	<li><a href="#page3">Clinical events and procedures</a></li>
	<li><a href="#page4">CDC-C</a></li>
	<li><a href="#page5">Lab tests</a></li>
	<li><a href="#page6">CD4/HIV-RNA</a></li>
	<li><a href="#page7">Resistance</a></li>
	<li><a href="#page8">Viro/sero</a></li>
	<li><a href="#page9">Death/drop-out</a></li>
	<li><a href="#page10">Visits</a></li>
	<!-- <li><a href="#page11">Samples</a></li> -->
	<li><a href="#page12">Pregnancy/Infants</a></li>
</ul>
<div id="page1" style="padding: 10px 10px 10px 10px;">
<table>
<?php
// tblLAB	 holds type, date, value and unit of laboratory tests.

// tblLAB_BP	 holds date, diastolic and systolic values and unit of blood pressure measurements.

// tblLAB_CD4	 holds date and value of CD4 measurements.
// tblLAB_RNA	 holds date, value, detection limit and type of viral assay.
// tblLAB_RES	 holds background information on the resistance test, laboratory, library, kit, software and type of test
// tblLAB_RES_LVL_1	 holds nucleoside sequence for the PRO and RT sequences
// tblLAB_RES_LVL_2	 holds mutations and positions of these.
// tblLAB_RES_LVL_3	 holds resistance result in relation to antiretroviral drug.
// tblLAB_VIRO	 holds test results for viro-/serological tests (hepatitis etc.)
// tblLTFU	 holds data in death and drop-out
// tblMED	 holds type, start and stop dates for other HIV related medicines.
// tblOVERLAP	 holds information on the patient's participation in other cohorts
// tblVIS	 holds visit related information, weight, wasting.

$excel = new Spreadsheet_Excel_Reader("tables.xls");

$x=1;
$previous_group = "";
$previous_expand = "";
$expand_with_value = "";
$cellspacing = 5;
// $part = array("1" => "OK", "2" => "", "3" => "", "4" => "", "5" => "",  "6" => "",  "7" => "",  "8" => "",  "" => "",  "10" => "",  "11" => "",  "12" => "");
$previous_part_num = "1";

while ($x <= $excel->sheets[0]['numRows']) {
	$header = trim($excel->val($x, 1));
	$numbering = trim($excel->val($x, 2));
	$question = trim($excel->val($x, 3));
	$datatype = trim($excel->val($x, 4));
	$group_with = trim($excel->val($x, 5));
	$expand_with_value = trim($excel->val($x, 6));
	$url = trim($excel->val($x, 7));

	if ($question == "" || $question == "Question") {
		$x++; continue;
	}

	//$partnum = substr($numbering, 0, 1);
	$partnum_arr = explode(".", $numbering) ;
	$partnum = $partnum_arr[0] ;


	if ($previous_part_num != $partnum && $partnum != "") {
		echo "\n</table></div> \n<div id='page".$partnum."' style='padding: 10px 10px 10px 10px;'><table>";
	}

	if ($datatype == "") {
		$hidden_style = "";
		$span_arguments = "";
		if ($expand_with_value != "") {
			$hidden_style = "style='display: none'";
			$span_arguments = "id='q_".$group_with."_sub_$expand_with_value'";
		}
		echo "<tr $span_arguments $hidden_style> ";
		echo "<td colspan='2'><b>$question</b></td></tr>";
		$previous_group = $group_with;
		$previous_expand = $expand_with_value;
		$previous_part_num = $partnum;
		$x++; continue;
	}

	$level = substr_count($numbering, ".");
	$indentation = str_repeat("&nbsp; &nbsp; &nbsp; ", $level);

	// new lines for check box datatype	NP
	if ($datatype == "CHECKBOX") {
		$hidden_style = "";
		$span_arguments = "";
		if ($expand_with_value != "") {
			$hidden_style = "style='display: none'";
			$span_arguments = "id='q_".$group_with."_sub_$expand_with_value'";
		}
		echo "<tr $span_arguments $hidden_style><td>";
		echo "<label for=\"q_$numbering\"> $indentation <input type='checkbox' name='q_$numbering' id='q_$numbering' onclick='togglebox(this, this.checked);'> ".str_replace("<a>", "<a href='".$url."' target='_blank'>", $question)." </td><td></td>";
		echo "</tr>";
		$previous_group = $group_with;
		$previous_expand = $expand_with_value;
		$previous_part_num = $partnum;
		$x++; continue;
	}
	// end of new lines 	for check box datatype	
	
    // new lines for text box datatype NK
    if ($datatype == "TEXTBOX") {
        $hidden_style = "";
        $span_arguments = "";
        if ($expand_with_value != "") {
            $hidden_style = "style='display: none'";
            $span_arguments = "id='q_".$group_with."_sub_$expand_with_value'";
        }
        echo "<tr $span_arguments $hidden_style><td>";
        echo "<label for=\"text_$numbering\"> $indentation $question </td><td> <textarea name='q_$numbering' id='q_$numbering' cols='50' style='height: 50px'></textarea> </td>";  
        echo "</tr>";
        $previous_group = $group_with;
        $previous_expand = $expand_with_value;
        $previous_part_num = $partnum;
        $x++; continue;
    }
    // end of new lines     for text box datatype  NK

	// new lines for numeric datatype
    if ($datatype == "PERCENT") {
        $hidden_style = "";
        $span_arguments = "";
        if ($expand_with_value != "") {
            $hidden_style = "style='display: none'";
            $span_arguments = "id='q_".$group_with."_sub_$expand_with_value'";
        }
        echo "<tr $span_arguments $hidden_style><td>";
        echo "<label for=\"percent_$numbering\"> $indentation $question </td><td>&nbsp;<input name='q_$numbering' id='q_$numbering' type='text' style='width: 40px' onblur='check_num(this);' /> % </td>";  
        echo "</tr>";
        $previous_group = $group_with;
        $previous_expand = $expand_with_value;
        $previous_part_num = $partnum;
        $x++; continue;
    }	
	// end of new lines for numeric datatype    

	// new lines for date datatype
    if ($datatype == "DATE") {
        $hidden_style = "";
        $span_arguments = "";
        if ($expand_with_value != "") {
            $hidden_style = "style='display: none'";
            $span_arguments = "id='q_".$group_with."_sub_$expand_with_value'";
        }
        echo "<tr $span_arguments $hidden_style><td>";
        echo "<label for=\"percent_$numbering\"> $indentation $question </td><td>&nbsp;<input name='q_$numbering' id='q_$numbering' type='text' class='dateinput' style='width: 100px' /> </td>";  
        echo "</tr>";
        $previous_group = $group_with;
        $previous_expand = $expand_with_value;
        $previous_part_num = $partnum;
        $x++; continue;
    }	
	// end of new lines for date datatype    
	    		
	// new lines for radio box datatype
	if ($datatype == "RADIOBOX") {
		$hidden_style = "";
		$span_arguments = "";
		if ($expand_with_value != "") {
			$hidden_style = "style='display: none'";
			$span_arguments = "id='q_".$group_with."_sub_$expand_with_value'";
		}
		echo "<tr $span_arguments $hidden_style><td> ";
		echo "<label for=\"q_$numbering\"> $indentation".str_replace("<a>", "<a href='".$url."' target='_blank'>", $question)."</label> &nbsp; </td><td>";
		echo "<input type='radio' name='q_$numbering' value='1' data-important='$header' onclick='toggleradio(this, this.value)' /> YES &nbsp; ";
		echo "<input type='radio' name='q_$numbering' value='0' data-important='$header' onclick='toggleradio(this, this.value)' /> NO &nbsp; ";
		echo "<input type='radio' name='q_$numbering' value='-1' data-important='$header' onclick='toggleradio(this, this.value)' checked='checked' /> N/A ";
		echo "<td></tr>";
		$previous_group = $group_with;
		$previous_expand = $expand_with_value;
		$previous_part_num = $partnum;
		$x++; continue;
	}
	// end of new lines 	for radio box datatype	
	
	// new lines for radio box 2 datatype
	if ($datatype == "RADIOBOX2") {
		$hidden_style = "";
		$span_arguments = "";
		if ($expand_with_value != "") {
			$hidden_style = "style='display: none'";
			$span_arguments = "id='q_".$group_with."_sub_$expand_with_value'";
		}
		echo "<tr $span_arguments $hidden_style><td> ";
		echo "<label for=\"q_$numbering\"> $indentation".str_replace("<a>", "<a href='".$url."' target='_blank'>", $question)."</label> &nbsp; </td><td>";
		echo "<input type='radio' id='q_$numbering' name='q_$numbering' value='1' data-important='$header' onclick='toggleradio(this, this.value)' /> YES &nbsp; ";
		echo "<input type='radio' id='q_$numbering' name='q_$numbering' value='2' data-important='$header' onclick='toggleradio(this, this.value)' /> OCCASIONALLY &nbsp; ";
		echo "<input type='radio' id='q_$numbering' name='q_$numbering' value='0' data-important='$header' onclick='toggleradio(this, this.value)' /> NO &nbsp; ";
		echo "<input type='radio' id='q_$numbering' name='q_$numbering' value='-1' data-important='$header' onclick='toggleradio(this, this.value)' checked='checked' /> N/A ";
		echo "<td></tr>";
		$previous_group = $group_with;
		$previous_expand = $expand_with_value;
		$previous_part_num = $partnum;
		$x++; continue;
	}
	// end of new lines 	for radio box 2 datatype		


	//	if ($part[substr($numbering, 0, 1)] != "OK") {
	//		echo "\n</div> \n<div id='page".substr($numbering, 0, 1)."' style='padding: 10px 10px 10px 10px;'>";
	//		$part[substr($numbering, 0, 1)] == "OK";
	//	}
	//
	//	if ($expand_with_value == "") {
	//		$cellspacing = 5;
	//	} else if ($previous_group != $group_with) {
	//		$cellspacing += 5;
	//	}
	//
	//	if ($expand_with_value == "") {
	//		echo "</table>\n\n";
	//		echo "<table id='q_$numbering' cellspacing='$cellspacing'>\n";
	//	} else if ($previous_group != $group_with || $previous_expand != $expand_with_value) {
	//		echo "</table>\n\n";
	//		echo "<table id='q_".$group_with."_sub_$expand_with_value' cellspacing='$cellspacing' style='display: none'>\n";
	//	}
	//	echo "<tr><td>\n";

	$hidden_style = "";
	$span_arguments = "";
	if ($expand_with_value != "") {
		$hidden_style = "style='display: none'";
		$span_arguments = "id='q_".$group_with."_sub_$expand_with_value'";
	}

	echo "<tr $span_arguments $hidden_style><td> ";
	echo "<label for=\"q_$numbering\"> $indentation".str_replace("<a>", "<a href='".$url."' target='_blank'>", $question)."</label> &nbsp; </td><td>";
	if ($numbering != '11.1.5') { 
		echo "<select name='q_$numbering' id='q_$numbering' data-important='$header' onchange='toggle(this, this.value);'>\n";
		echo "<option value='-1'>- Select one -</option>\n";
		echo $datatypes[$datatype];
		echo "</select> &nbsp; ";
	}
	?><input name="text_<?php echo $numbering; ?>"
	id="text_<?php echo $numbering; ?>" type="text" size="50"
	<?php if ($numbering != '11.1.5') { ?>
	style="display: none"
	<?php }
	?>
	 /> <?php 
	echo "<td></tr>";
	$previous_group = $group_with;
	$previous_expand = $expand_with_value;
	$previous_part_num = $partnum;
	$x++;
}
?>

</table>

</div>
</div>

<input type="hidden" id='hicdep_required' value='<?php echo $hicdep_required; ?>'/>

</form>

<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$(".dateinput").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "yy-mm-dd"
		}).blur(function() {
			check_date(this); 
		});		
	});
</script>

