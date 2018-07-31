<?php
/** bootstap the game */
require_once '../src/Gameboard/config/local.php';
use Gameboard\Module;

$boardObj = new Module();
$configs = $boardObj->getConfig();
setAutoloading( $configs['router']['routes'] );

/**
 * A poor mans autoloader
 * load this modules Models, Views and Controllers.
 * @param array $routes
 */
function setAutoloading(array $routes)
{
    
    foreach( $routes as $key => $array ) {
        if( $routes[$key]['defaults'] ) {
            
            foreach( $array['defaults'] as $file ) {
                $path = "../src/" . $file . ".php";
                if( file_exists($path) ) include $path;
            }
            
        }
    }
}

/** get board matrix */
use Gameboard\Controller\BoardController;
use Gameboard\Model\Board;

$boardController = new BoardController();
$boardMatrix = $boardController->boardAction();
if( $_GET ) {
    $msg = $boardController->submitAction( $_GET ); 
} 
?>

<!DOCTYPE html>
<html land="en">
	<head>
		<title>Eight Queens</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<script type="text/javascript" src="js/jquery/dist/jquery.js"></script>
		
		<script>
			function allowDrop(ev) {
		    	ev.preventDefault();
			}

			function drag(ev) {
		    	ev.dataTransfer.setData("text", ev.target.id);
			}

			function drop(ev) {
		    	ev.preventDefault();
		    	var data = ev.dataTransfer.getData("text");
		    	ev.target.appendChild(document.getElementById(data));
			}
			
		</script>
		
	</head>
	<body>
	
		<header>
			<h3>Play Eight Queens</h3>
			<p>Place each Queen on gameboard so they are not captured by another Queen.</p>
		</header>
		
		<div class="container">
				<?php include '../view/gameboard/board.php'?>
		</div>
		
		<footer>
			<h4>Check solution?</h4>&nbsp;</h4>
			<button class="btn_submit" id="submit">Submit</button>
		</footer>
	</body>
</html>

<script type="text/javascript">
  $(document).ready(function () {
	
	/* ES6 Map (similar to Java) */
	const solve = new Map();
	
	// delegate click event
	$('#submit').on("click", function(event) {
		event.preventDefault;
		getTableData();
		
		var jsonStr = mapToJson(solve, solve.size);
		console.log("board solution: " + jsonStr);
		
		if( jsonStr.length > 0 ) {
			$.ajax({
				method: "get",
				data: {Trial:jsonStr},
				contentType: "application/json; charset=utf-8",
		        dataType: "json",
			})
			.done(function( data ) {
				alert( "Data Saved: " + data );
			});
		} else {
			console.log("json had no length or was null");
		}
	
	});
	
	
	/*
	 * getTableData
	 * eval each gameboard td for a queen and assign 
	 * the queens id and space id to the solve Map.
	 * @return void
	 */
	function getTableData() {
		var queens = [];
		var spaces = [];
		var td = $('tbody tr').find('td');
		
		td.each(function() {
			var hasImg = $('img',this).length > 0;
			if(hasImg) {
				queens.push( $('img',this).attr('id') );
				spaces.push( $(this).data('title') );
			}
		});
		
		// assign arrays to solve Map
		solve.set( 'queens', queens );
		solve.set( 'spaces', spaces );
	}
	
	
	/*
	* mapToJson
	* convert Map object to JSON string
	* @return string
	*/
	function mapToJson(map, count) {
		var jstring = "[{";
		// var jstring = "";
		var i=1;
		
		map.forEach( (value, key) => {
			jstring += `"${key}":"${value}"`;
			
			if(i < count) jstring += ",";
			i++;
		});
		jstring += "}]";
		return jstring;
	}
	
  });
</script>