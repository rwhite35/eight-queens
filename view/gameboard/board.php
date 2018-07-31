<?php
/** local vars */
$imgsrc = "/workspace/EightQueens/public/img/";
?>	
<article>
	<div class="gameboard">
		<div class="board">
			<table><tbody ondrop="drop(event)" ondragover="allowDrop(event)">
			<?php
                 // create each row
			     foreach( $boardMatrix as $ri => $row ) {
			         echo '<tr class=" id="yco_' . $ri .'">';
				     
				     // create each col of each row
				     foreach( $row as $ci => $v ) {
				        echo '<td id="xco_' . $ci . '" data-title="' . $v . '"></td>';
				          if ( $ci == 8 ) echo "</tr>";
				     }
				}
		      ?>
			</tbody></table>
			</div>
			
		</div>
</article>
		
<div class="tray">
	<div class="tiles" id="Q101" style="top:0">
		<img class="queens" src="<?php echo $imgsrc . "Q101.png"?>"
			id="Q101"
			ondragstart="drag(event)" 
			draggable="true"></div>
			
	<div class="tiles" id="Q102" style="top:50px">
		<img class="queens" src="<?php echo $imgsrc . "Q102.png"?>"
			id="Q102"
			ondragstart="drag(event)"
			draggable="true"></div>
			
	<div class="tiles" id="Q103" style="top:100px">
		<img class="queens" src="<?php echo $imgsrc . "Q103.png"?>" 
			id="Q103" 
			ondragstart="drag(event)"
			draggable="true"></div>
			
	<div class="tiles" id="Q104" style="top:150px">
		<img class="queens" src="<?php echo $imgsrc . "Q104.png"?>" 
			id="Q104" 
			ondragstart="drag(event)"
			draggable="true"></div>
			
	<div class="tiles" id="Q105" style="top:200px">
		<img class="queens" src="<?php echo $imgsrc . "Q105.png"?>" 
			id="Q105"
			ondragstart="drag(event)" 
			draggable="true"></div>
			
	<div class="tiles" id="Q106" style="top:250px">
		<img class="queens" src="<?php echo $imgsrc . "Q106.png"?>" 
			id="Q106"
			ondragstart="drag(event)"
			draggable="true"></div>
			
	<div class="tiles" id="Q107" style="top:300px">
		<img class="queens" src="<?php echo $imgsrc . "Q107.png"?>" 
			id="Q107"
			ondragstart="drag(event)"
			draggable="true"></div>
			
	<div class="tiles" id="Q108" style="top:350px">
		<img class="queens" src="<?php echo $imgsrc . "Q108.png"?>" 
			id="Q108"
			ondragstart="drag(event)"
			draggable="true"></div>
	</div>