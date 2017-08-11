<?php
//**************************[start of ShowRegisteredInstrument function]
function ShowRegisteredInstrument($DB,$mytable,$id)

{
	$base=new SQLite3($DB); 
$query3 = "SELECT instrument FROM $mytable where ID=  $id";
$results3 = $base->query($query3);


if (!$results3)
    exit ("Error : Select not do<br>");

$row = $results3->fetchArray();

if(count($row)>0)
{

   $instr = $row['instrument']; 

}   
 return $instr;

}
//**************************[end of ShowRegisteredInstrument function]



//**************************[start of ShowRegisteredInstrument function]
function ShowRegisteredField($DB,$mytable,$id)

{
	$base=new SQLite3($DB); 
$query3 = "SELECT field FROM $mytable where ID=  $id";
$results3 = $base->query($query3);

if (!$results3)
    exit ("Error :Register not do<br>");

$row = $results3->fetchArray();

if(count($row)>0)
{

   $field = $row['field'];

}   
 return $field;

}
//**************************[end of ShowRegisteredInstrument function]

//**************************[start of InsertTable function]
function InsertTable($DB,$mytable,$id,$instrument,$field)

{
	$base=new SQLite3($DB); 

    $query2 = "INSERT INTO $mytable(ID, instrument,field) 
                VALUES ('$id', '$instrument', '$field')";

$results2 = $base->exec($query2);
if (!$results2)
    exit ("Error : Insert not do<br>");
  

}
//**************************[end of InsertTable function]

//**************************[start of UpdateTable function]
function UpdateTable($DB,$mytable,$id,$instrument,$field)

{
$base=new SQLite3($DB); 


    $query2 = "UPDATE $mytable SET instrument='$instrument', field='$field'
                WHERE ID=$id ";

 $results2 = $base->exec($query2);
if (!$results2)
    exit ("Error :update not do<br>");


}
//**************************[end of UpdateTable function]
//**************************[start of DeleteEntry function]
function DeleteEntry($DB,$mytable,$id)

{
$base=new SQLite3($DB); 


    $query4 = "DELETE FROM $mytable WHERE ID=$id ";

 $results4 = $base->exec($query4);
if (!$results4)
    exit ("Error : Delete not do<br>");


}
//**************************[end of DeleteEntry function]
//**************************[start of CreateTable function]
function CreateTable($DB,$mytable)

{
 if(!class_exists('SQLite3'))
  die("SQLite 3 NOT supported.");

$base=new SQLite3($DB); 

    $query = "CREATE TABLE $mytable(
            ID bigint(20) NOT NULL PRIMARY KEY,
            instrument text,
            field text            
            )";
            
$results = $base->exec($query);
if (!$results)
    exit ("Table not created<br>");

echo "Table '$mytable' created.<br>";

}

//**************************[end of CreateTable function]


function injectPluginTabs($pid, $plugin_path, $plugin_name) {
    $msg = '<script>
		jQuery("#sub-nav ul li:last-child").before(\'<li class="active"><a style="font-size:13px;color:#393733;padding:4px 9px 7px 10px;" href="'.$plugin_path.'"><img src="' . APP_PATH_IMAGES . 'email.png" class="imgfix" style="height:16px;width:16px;"> ' . $plugin_name . '</a></li>\');
		</script>';
    echo $msg;
}


    function getElementEnum($proj, $field) {
        $enum = db_fetch_assoc(db_query("select element_enum as ee from redcap_metadata where project_id = $proj and field_name = '$field'"));
                
        $fld_enum = $enum['ee'];
        $enum_array = array();
     
     
        foreach (explode("\\n", $fld_enum)  as $key => $value) {
            $new = explode(", ", $value);
            $enum_array[trim($new['0'])] = trim($new['1']);
        }
        return $enum_array;
    }
	
	
function RegionMap($tab,$location,$provinces) {
    
		print '<!-- jQuery (necessary for Bootstraps JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<!-- tablesorter plugin -->
<script src="js/js.js"></script>




    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.charts.load("current", {"packages":["geochart"]});
      google.charts.setOnLoadCallback(drawRegionsMap);

      function drawRegionsMap() {

        var data = google.visualization.arrayToDataTable(';
		print $tab;
		print ');

        var options = {';
			if ($provinces=="YES"){print 'resolution:"provinces",';}
			
			print'region: "';
			print $location;
			print'"

		};

        var chart = new google.visualization.GeoChart(document.getElementById("regions_div"));

        chart.draw(data, options);
      }
    </script>

    <div id="regions_div" style="width: 900px; height: 500px;"></div>';
	
}
?>