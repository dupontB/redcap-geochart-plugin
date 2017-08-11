<?php
//database connect
require_once "../../redcap_connect.php";
require_once "functions.php";

# Display the project header
require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';

# Inject the plugin tabs (must come after including tabs.php)
include APP_PATH_DOCROOT . "ProjectSetup/tabs.php";
injectPluginTabs($pid, $_SERVER["REQUEST_URI"], 'GoogleMap');
?>
<style type='text/css'>
    td.td1 {vertical-align:text-top;}
    td.td2 {vertical-align:text-top; padding-top: 5px; padding-right:15px; width:70px;}
    td.td2 label {font-variant:small-caps; font-size: 12px;}
    td.td3 {vertical-align:middle;}
    div.desc {font-variant:normal; font-style:italic; font-size: smaller; padding-top:5px; width:70px;}
    table.tbi input {width: 500px; display: inline; height:20px}
    table.tbi input[type='radio'] {width: 14px; display: normal;}
    table.tbi textarea {width: 500px; display:inline; height:50px;}
    .modified {	font-style: italic; color: #9a9faa }
    .radio-option {margin-right: 10px; margin-bottom: 5px; position:relative; top:-5px;}
	label{display:block; width:100px;float:left;}
	select{width:150px;}
	input{ 

        border-radius:5px;
        border:solid 1px #D94E3B;
        -webkit-transition: all 0.1s;
        -moz-transition: all 0.1s;
        transition: all 0.1s;
        -webkit-box-shadow: 0px 9px 0px #84261a;
        -moz-box-shadow: 0px 9px 0px #84261a;
        box-shadow: 0px 2px 0px rgba(0, 0, 0, 0.3);
		color: #333;
border: solid 1px #aaa;
border-right: solid 1px #888;
border-bottom: solid 1px #888;
}
</style>
<?php
//****sqlite informations
 $dbname='DB';
$mytable ='gmap';

if (!file_exists($dbname)) {
    CreateTable($dbname,$mytable);
} 


$id =$_GET['pid'];
//Check if project exist in the database
$empty=ShowRegisteredInstrument($dbname,$mytable,$id) ;

//If button save pushed you create entry in database or update the row
if (isset($_POST['save'])){
	
	if(empty($empty)){
		
	InsertTable($dbname,$mytable,$id,$_POST['listofinstrument'],$_POST['listoffields']);}
	else{
UpdateTable($dbname,$mytable,$id,$_POST['listofinstrument'],$_POST['listoffields']);};

	};
	
	//If button unset pushed = entry deleted in database and post variable deleted
if (isset($_POST['unset']))
{
	DeleteEntry($dbname,$mytable,$id);
	unset($_POST['listofinstrument']);
	unset($_POST['listoffields']);
};

//recheck if the project is registered in sqlite DB
$empty2=ShowRegisteredInstrument($dbname,$mytable,$id) ;
if (!empty($empty2) and !isset($_POST['listofinstrument']) and !isset($_POST['listoffields']))
	{
	$_POST['listofinstrument']=ShowRegisteredInstrument($dbname,$mytable,$id);
	$_POST['listoffields']=ShowRegisteredField($dbname,$mytable,$id);
	}
; 



//get list of instruments for this project
$instrument_names = REDCap::getInstrumentNames();
 
print '<form action="index.php?pid='.$_GET['pid'].'" method="post">';


print "<label> Form : </label><select name='listofinstrument' onChange='this.parentNode.submit()'>";
print "<option value='0'>";
foreach ($instrument_names as $unique_name=>$label)
{
    // Print this instrument name and label
	print "<option";
	//test
	if ((isset($_POST['listofinstrument'])) and ($unique_name==$_POST['listofinstrument'])){print ' selected="selected"';}
	print" value='".$unique_name."'>".$label."</option>";

}
print "</select>";
print '</br></br>';
if (isset($_POST['listofinstrument']))
{
	
	$field_names = REDCap::getFieldNames($_POST['listofinstrument']);
	print "<label> Country Field : </label><select name='listoffields' onChange='this.parentNode.submit()'>";
		foreach ($field_names as $this_field)
		{
			
			// Print this instrument name and label
			print "<option";
			if ((isset($_POST['listoffields'])) and ($this_field==$_POST['listoffields'])){print ' selected="selected"';}
			print" value='".$this_field."'>".$this_field."</option>";
		}
print "</select></br></br>";	
}



if (isset($_POST['listofinstrument']) and isset($_POST['listoffields'])){
print '</br><input type="submit" name="save" value="Save" />';
print '<input type="submit" name="unset" value="unset" /></br>';
}


print "</form>";



//if the 2 fields exist
if (isset($_POST['listofinstrument']) and isset($_POST['listoffields'])){

	$montableau = "[['Country', 'count'],";
//select country value and count each

	$sql = "SELECT value, COUNT( * )as compteur FROM redcap_data where project_id=".$_GET['pid']." and field_name ='".$_POST['listoffields']."' GROUP BY value";
$result = $conn->query($sql);
//print_r($sql);
    // output data of each row
  while($row = $result->fetch_assoc()) {
        $montableau .="['".$row['value']."',".$row['compteur']."],";

    }
	//delete last coma at the end of the table
	$montableau = substr($montableau, 0, -1);
	//close table
	$montableau .="]";


$toprint = getElementEnum($_GET['pid'],$_POST['listoffields']);

//adjust syntax adding quote
foreach ($toprint as $key=>$value) {

$key2 ="'".$key."'";
$value2 ="'".$value."'";

$montableau = str_replace($key2,$value2,$montableau);

}

$conn->close();
}
if (isset($_POST['listofinstrument']) and isset($_POST['listoffields']) and isset($montableau) ){
// You can target a region, europe for exemple is 150, adding in url argument map=150
//find your region code here : https://developers.google.com/chart/interactive/docs/gallery/geochart#continent-hierarchy-and-codes
$myregion =$_GET['map'];
$provinces=$_GET['provinces'];

//If you don't precise map in URL, put worldmap by default
if (empty($myregion))$myregion="world";
//If you don't precise to set region in URL,no set region precision
if (!empty($provinces)){$provinces="YES";} ELSE {$provinces="NO" ;}

RegionMap($montableau,$myregion,$provinces);

}



require_once APP_PATH_DOCROOT . 'ProjectGeneral/footer.php';

	?>
	
	