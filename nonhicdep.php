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

<form id="fnonhicdep">

<!--<div id="nonhicdep_tabs" class="add_shadow_tabs">-->
<!--<ul>-->
<!--	<li><a href="#nh_page1"> </a></li>-->
<!--</ul>-->
<!--<div id="nh_page1" style="padding: 10px 10px 10px 10px;">-->
<table>
<?php


$excel = new Spreadsheet_Excel_Reader("tables_nonhicdep.xls");

$x=1;
$previous_group = "";
$previous_expand = "";
$expand_with_value = "";
$cellspacing = 5;
$part = array("1" => "OK", "2" => "", "3" => "", "4" => "", "5" => "");
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
		echo "\n</table><table>";
//		echo "\n</table></div> \n<div id='page".$partnum."' style='padding: 10px 10px 10px 10px;'><table>";
	}

	if ($datatype == "") {
		$hidden_style = "";
		$span_arguments = "";
		if ($expand_with_value != "") {
			$hidden_style = "style='display: none'";
			$span_arguments = "id='nq_".$group_with."_sub_$expand_with_value'";
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
			$span_arguments = "id='nq_".$group_with."_sub_$expand_with_value'";
		}
		echo "<tr $span_arguments $hidden_style><td>";
		echo "<label for=\"nq_$numbering\"> $indentation <input type='checkbox' name='nq_$numbering' id='nq_$numbering' onclick='togglebox(this, this.checked);'> ".str_replace("<a>", "<a href='".$url."' target='_blank'>", $question)." </td><td></td>";
		echo "</tr>";
		$previous_group = $group_with;
		$previous_expand = $expand_with_value;
		$previous_part_num = $partnum;
		$x++; continue;
	}
	// end of new lines 	for check box datatype	NP

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
	
	// new lines for radio box datatype
	if ($datatype == "RADIOBOX") {
		$hidden_style = "";
		$span_arguments = "";
		if ($expand_with_value != "") {
			$hidden_style = "style='display: none'";
			$span_arguments = "id='nq_".$group_with."_sub_$expand_with_value'";
		}
		echo "<tr $span_arguments $hidden_style><td> ";
		echo "<label for=\"nq_$numbering\"> $indentation".str_replace("<a>", "<a href='".$url."' target='_blank'>", $question)."</label> &nbsp; </td><td>";
		echo "<input type='radio' id='nq_$numbering' name='nq_$numbering' data-important='$header' value='1' onclick='toggleradio(this, this.value)' /> YES &nbsp; ";
		echo "<input type='radio' id='nq_$numbering' name='nq_$numbering' data-important='$header' value='0' onclick='toggleradio(this, this.value)' /> NO &nbsp; ";
		echo "<input type='radio' id='nq_$numbering' name='nq_$numbering' data-important='$header' value='-1' onclick='toggleradio(this, this.value)' checked='checked' /> N/A ";
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
			$span_arguments = "id='nq_".$group_with."_sub_$expand_with_value'";
		}
		echo "<tr $span_arguments $hidden_style><td> ";
		echo "<label for=\"nq_$numbering\"> $indentation".str_replace("<a>", "<a href='".$url."' target='_blank'>", $question)."</label> &nbsp; </td><td>";
		echo "<input type='radio' id='nq_$numbering' name='nq_$numbering' data-important='$header' value='1' onclick='toggleradio(this, this.value)' /> YES &nbsp; ";
		echo "<input type='radio' id='nq_$numbering' name='nq_$numbering' data-important='$header' value='2' onclick='toggleradio(this, this.value)' /> OCCASIONALLY &nbsp; ";
		echo "<input type='radio' id='nq_$numbering' name='nq_$numbering' data-important='$header' value='0' onclick='toggleradio(this, this.value)' /> NO &nbsp; ";
		echo "<input type='radio' id='nq_$numbering' name='nq_$numbering' data-important='$header' value='-1' onclick='toggleradio(this, this.value)' checked='checked' /> N/A ";
		echo "<td></tr>";
		$previous_group = $group_with;
		$previous_expand = $expand_with_value;
		$previous_part_num = $partnum;
		$x++; continue;
	}
	// end of new lines 	for radio box datatype		

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
		$span_arguments = "id='nq_".$group_with."_sub_$expand_with_value'";
	}

	echo "<tr $span_arguments $hidden_style><td> ";
	echo "<label for=\"nq_$numbering\"> $indentation".str_replace("<a>", "<a href='".$url."' target='_blank'>", $question)."</label> &nbsp; </td><td>";
	echo "<select name='nq_$numbering' id='nq_$numbering' data-important='$header' onchange='toggle(this, this.value);'>\n";
	echo "<option value='-1'>- Select one -</option>\n";
	echo $datatypes[$datatype];
	?> </select> &nbsp; <input name="ntext_<?php echo $numbering;?>"
	id="ntext_<?php echo $numbering;?>" type="text" size="50"
	style="display: none" /> <?php 
	echo "<td></tr>";
	$previous_group = $group_with;
	$previous_expand = $expand_with_value;
	$previous_part_num = $partnum;
	$x++;
}
?>
</table>
<!--</div>-->
<!--</div>-->

</form>