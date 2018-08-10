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
			         $trow = ( $ri % 2 == 0 ) ? "even" : "odd";
			         echo '<tr class=" id="yco_' . $ri .'">';
				     
				     // create each col of each row
				     foreach( $row as $ci => $v ) {
				        $sum = $ci % 2;
				        if ( $trow == "odd" && $sum != 0 ) {
				            $tile = "class='Atile'";
				        } else if ( $trow == "even" && $sum == 0 ) {
				            $tile = "class='Atile'";
				        } else {
				            $tile = "class='Btile'";
				        }
				        
				        echo '<td id="xco_'. $ci .'" '. $tile .' data-title="' . $v . '"></td>';
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
			
	<div class="tiles" id="Q102" style="top:56px">
		<img class="queens" src="<?php echo $imgsrc . "Q102.png"?>"
			id="Q102"
			ondragstart="drag(event)"
			draggable="true"></div>
			
	<div class="tiles" id="Q103" style="top:112px">
		<img class="queens" src="<?php echo $imgsrc . "Q103.png"?>" 
			id="Q103" 
			ondragstart="drag(event)"
			draggable="true"></div>
			
	<div class="tiles" id="Q104" style="top:168px">
		<img class="queens" src="<?php echo $imgsrc . "Q104.png"?>" 
			id="Q104" 
			ondragstart="drag(event)"
			draggable="true"></div>
			
	<div class="tiles" id="Q105" style="top:224px">
		<img class="queens" src="<?php echo $imgsrc . "Q105.png"?>" 
			id="Q105"
			ondragstart="drag(event)" 
			draggable="true"></div>
			
	<div class="tiles" id="Q106" style="top:280px">
		<img class="queens" src="<?php echo $imgsrc . "Q106.png"?>" 
			id="Q106"
			ondragstart="drag(event)"
			draggable="true"></div>
			
	<div class="tiles" id="Q107" style="top:336px">
		<img class="queens" src="<?php echo $imgsrc . "Q107.png"?>" 
			id="Q107"
			ondragstart="drag(event)"
			draggable="true"></div>
			
	<div class="tiles" id="Q108" style="top:392px">
		<img class="queens" src="<?php echo $imgsrc . "Q108.png"?>" 
			id="Q108"
			ondragstart="drag(event)"
			draggable="true"></div>
	</div>