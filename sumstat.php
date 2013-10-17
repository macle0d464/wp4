<form id="fsumstat">
	<p style="font-size: 11pt;">
		  Please fill in the following <u>numbers</u> (i.e. not percentages).
	</p>

<?php 
require_once('./include/php/excel_reader2.php');

error_reporting(0); 
 
$excel = new Spreadsheet_Excel_Reader("sumstat.xls"); //, 'WINDOWS-1253');
$x=1;

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
	} else if ($numbering == "3") {
?>
	<input type="text" id="<?php echo "sum_".$numbering; ?>" name="<?php echo "sum_".$numbering; ?>" value="<?php echo $data['sumstat']['sum_'.$numbering]; ?>" data-important="1" size="5" onchange="calculate_progress();" onblur="check_num(this);" onkeypress="return check_number(this, event);" /> <?php echo $question; ?>
	(define <i>pediatric</i> by entering the corresponding cutoff age, e.g. <18: &nbsp; 
	&lt;<input type="text" id="<?php echo "sum_".$numbering."_cuttoff_age"; ?>" name="<?php echo "sum_".$numbering."_cuttoff_age"; ?>" value="<?php echo $data['sumstat']['sum_'.$numbering.'_cuttoff_age']; ?>" data-important="1" size="2" onchange="calculate_progress();" onblur="check_num(this);" onkeypress="return check_number(this, event);" />)<br>
<?php		
	} else {
?>
	<input type="text" id="<?php echo "sum_".$numbering; ?>" name="<?php echo "sum_".$numbering; ?>" value="<?php echo $data['sumstat']['sum_'.$numbering]; ?>" data-important="1" size="5" onchange="calculate_progress();" onblur="check_num(this);" onkeypress="return check_number(this, event);" /> <?php echo $question; ?><br>
<?php
	}
	$x++;
}
?>

</form>

<pre>
<!-- 	<? print_r($data); ?> -->
</pre>