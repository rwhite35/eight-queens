<?php
namespace Gameboard\Solutions;
/**
 * Eight Queens Solution Class
 * Eight Queens is an example of a Contraint Satisfaction Problem (CSP).
 * The placement of the eight Queens must be in such a way that one Queen 
 * wouldnt be able to capture another Queen, given standard rules of Chess.
 * 
 * This class checks that a submitted proof
 * 1. Contains eight Queens and that
 * 2. Only one Queen occupies a given Row(Y coord) and Column(X coord)
 * 3. Only one Queen occupies a given Diagonal
 */

use Gameboard\Model\Board;
use Gameboard\Model\Validate;
use Gameboard\Calculate\Diagonals\CalcDiagonals;

class Solution extends Board
{
    /**
     * @property Proofs
     * player submitted solution
     */
    protected $Proofs;
    
    /**
     * @property Board
     * Ycoord(row), Xcoord(col), SqruareId
     */
    protected $Board;
    
    /**
     * @property Diagonals
     * calculates one direction for each 
     * diagonal a queen occupies 
     */
    protected $Diagonal;
    
    /**
     * @property ValidateSolution
     * the content of this property are passed back
     * to the view as a JSON string.
     */
    public $ValidateSolution;
    
    
    
    public function __construct()
    {
        $this->Proofs   =  [];
        $this->Board    =  new Board();
        $this->Diagonal =  new CalcDiagonals();
        $this->ValidateSolution = new Validate();
        
    }
    
    
    /**
     * Takes game board input and checks the players solution
     * based on each Queens (good queen) position in relationship to 
     * each other queen (evil queens). 
     * If an evil queen occupies a space that can be captured by the
     * good queen, the condition is not satisfied and the solution fails.
     * 
     * On submit(JSON): 
     * * [{"queens":"Q101,Q102,Q103,Q104,Q105,Q106,Q107,Q108","spaces":"G,M,X,Y,AI,AT,AX,BH"}]
     */
    public function checkSolution() 
    {
        
        $queenKeys  =  explode( ",", $this->Proofs['queens'] );
        $qSqrId     =  explode( ",", $this->Proofs['spaces'] );
        $matrix     =  $this->Board->boardMatrix();
        $row        =  1;   // always start with the first row
        
        /* Outer loop over each Queen. Only loops over each Queen once
         * and has a time complexity of O^1 (continous time).
         */
        foreach( $queenKeys as $k => $goodQueenId ) {
            
            $this->Proofs['Q'][$goodQueenId]['SqrId'] = $qSqrId[$k];
            
            /* Inner loop assign Queens coordinates and square ID. */
            foreach( $matrix[$row] as $col => $mSqrId ) {
                
                if( $this->Proofs['Q'][$goodQueenId]['SqrId'] == $mSqrId ) {
                    $this->Proofs['Q'][$goodQueenId]['SqrCoord']    = $row .",". $col;
                    $this->Proofs['Q'][$goodQueenId]['Ycoord']      = $row;
                    $this->Proofs['Q'][$goodQueenId]['Xcoord']      = $col;
                }
                
                if( $col == Board::$XMax ) $row++;  // move internal pointer to next row
            }
            
            /* calculate relative position to the game board */
            $this->calcRelativePosition( $goodQueenId );
            
            /* calculate diagonal spaces Queen can move */ 
            $this->calcDiagonals( $goodQueenId );
            
            /* reduce coords into chunks for quick search validation */
            $this->chunkCoords( $goodQueenId );
         
        } // close outer foreach queen
        
        /* now that all queens have a complete data sets, 
         * validate the submitted solution */
        $this->ValidateSolution->setProofs( $this->Proofs );
        
        $validated = $this->ValidateSolution->validateRowsColumns();
        
        if($validated) $resultArr = $this->ValidateSolution
            ->validateDiagonals( $goodQueenId );
        
            
        ob_start();
        
        if ( isset($resultArr) ){  
            echo "Solutions Validated Proofs: ";
            print_r($resultArr);
        }
        
        echo "Queens Proofs Data Set: ";
        print_r( $this->Proofs['Q'] );
        $str = ob_get_clean();
        error_log($str);
  
    }
    
    
    /**
     * calculates Queens relative position to the game board
     * assumes cardinal numbers starting with 1 and increments
     * to the game boards max size which is likely 8.
     * Therefore a queen on row 1 would have zero rows above her
     * and 7 rows below her position. 
     *  
     * @return void
     */
    private function calcRelativePosition( string $goodQueenId )
    {
           $yLessOne    = Board::$YMax - 1;
           $xLessOne    = Board::$XMax - 1;
           
           $this->Proofs['Q'][$goodQueenId]['rowsAbove']    = '';
           $this->Proofs['Q'][$goodQueenId]['rowsBelow']    = '';
           $this->Proofs['Q'][$goodQueenId]['colsLeft']     = '';
           $this->Proofs['Q'][$goodQueenId]['colsRight']    = '';
           $this->Proofs['Q'][$goodQueenId]['ydLeftAbv']    = [];
           $this->Proofs['Q'][$goodQueenId]['ydRightAbv']   = [];
           $this->Proofs['Q'][$goodQueenId]['ydLeftBlw']    = [];
           $this->Proofs['Q'][$goodQueenId]['ydRightBlw']   = [];
          
           
           /* calculate the rows above this square */
           $rowsAbove  =  ( $this->Proofs['Q'][$goodQueenId]['Ycoord'] == 1 ) 
            ? 0 : $yLessOne - ( Board::$YMax - $this->Proofs['Q'][$goodQueenId]['Ycoord'] );
           
            $this->Proofs['Q'][$goodQueenId]['rowsAbove']  = $rowsAbove;
           
            
           /* calculate the rows below this square */
           $rowsBelow  =  Board::$YMax - $this->Proofs['Q'][$goodQueenId]['Ycoord'];
           
           $this->Proofs['Q'][$goodQueenId]['rowsBelow']   = $rowsBelow;
           
           
           /* calculate the columns to the left of this square */
           $colsLeft    =  ( $this->Proofs['Q'][$goodQueenId]['Xcoord'] == 1 ) 
            ? 0 : $xLessOne - ( Board::$XMax - $this->Proofs['Q'][$goodQueenId]['Xcoord'] );
           
            $this->Proofs['Q'][$goodQueenId]['colsLeft']   = $colsLeft;
            
           /* calculate the columns to the right of the square */
           $colsRight   =  Board::$XMax - $this->Proofs['Q'][$goodQueenId]['Xcoord'];
           
           $this->Proofs['Q'][$goodQueenId]['colsRight']   = $colsRight;

    }
    
    
    /**
     * @method private CalcDiagonals
     * Calculate diagonal spaces this queen occupies
     * If another queen occupies a space on any diagonal
     * that can be captured, the solution fails.
     * 
     * @param string $goodQueenId, this queens id (Q101, Q108 etc)
     * 
     * @return void, updates class property Proofs
     */
    private function calcDiagonals( string $goodQueenId )
    {
        $calculated =   [ 'rowsAbove' => false, 'rowsBelow' => false ];
        $rowsAbove  =   $this->Proofs['Q'][$goodQueenId]['rowsAbove'];
        $rowsBelow  =   $this->Proofs['Q'][$goodQueenId]['rowsBelow'];
        $colsLeft   =   $this->Proofs['Q'][$goodQueenId]['colsLeft'];
        $colsRight  =   $this->Proofs['Q'][$goodQueenId]['colsRight'];
        $qYcoord    =   $this->Proofs['Q'][$goodQueenId]['Ycoord'];
        $qXcoord    =   $this->Proofs['Q'][$goodQueenId]['Xcoord'];
        
        /* calcuate the left and right diagonals above this row */
        if ( $rowsAbove > 0 ) {
            $this->Proofs['Q'][$goodQueenId]['ydLeftAbv'] = 
                $this->Diagonal->aboveRowLeft( $qYcoord, $qXcoord, $rowsAbove, $colsLeft );
            
            $this->Proofs['Q'][$goodQueenId]['ydRightAbv'] = 
                $this->Diagonal->aboveRowRight( $qYcoord, $qXcoord, $rowsAbove, $colsRight );
                    
            $calculated['rowsAbove'] = true;
            
        }
        
        /* calculate the left and right diagonals below this row */
        if ( $rowsBelow > 0 ) {
            $this->Proofs['Q'][$goodQueenId]['ydLeftBlw'] =
                $this->Diagonal->belowRowLeft( $qYcoord, $qXcoord, $rowsBelow, $colsLeft );

            $this->Proofs['Q'][$goodQueenId]['ydRightBlw'] =
                $this->Diagonal->belowRowRight( $qYcoord, $qXcoord, $rowsBelow, $colsRight );
            
            $calculated['rowsBelow'] = true;
        }
        
    }
    
    
    /**
     * @method chunkCoords
     * cooridinates are totaled and sorted in to chunks. Queens with
     * matching chunked totals are searched for matching coordinates.
     * when found, the queens violate the cronstaint and the solution fails.
     * 
     * @param string $goodQueenId, this Queens id (Q101, Q104 etc)
     * 
     * @var array $allCoords, enumerated array of string coordinates
     * * proto Array[ [0]=>"1,4", [1]=>"2,3",...,[9]=>"6,8" ]
     * 
     * @return void, updates class property Proofs
     * 
     */
    private function chunkCoords( string $goodQueenId )
    {
        $chunked    = [];
        $allCoords  = [ $this->Proofs['Q'][$goodQueenId]['SqrCoord'] ];
        $keys       = array_keys( $this->Proofs['Q'][$goodQueenId] );
            
        /* loop through each elements, if array, get the coords */
        foreach( $keys as $k ) {
            if (  is_array( $this->Proofs['Q'][$goodQueenId][$k]) ) {
                $c = count( $this->Proofs['Q'][$goodQueenId][$k] );
                if( $c > 0 ) {
                    for ( $i=0; $i < $c; $i++ ) {
                        $allCoords[] = $this->Proofs['Q'][$goodQueenId][$k][$i];
                    }
                }
            }
        }
        
        /* loop through all coords and chunk into sets of three */
        $e = 1;
        $t = 0;
        for ( $j=0; $j<count($allCoords); $j++ ) {
            $yx = explode( ",", $allCoords[$j] );
            $t = $t +  ( $yx[0] + $yx[1] );
            $chunked[$e] = $t;
            
           if( ($j + 1) % 3 == 0 ) {
                $t = 0;     // reset tally
                $e++;       // increment chunk
           }
           
        }
        
        /* append the chunked totals to this queens data set */
        $this->Proofs['Q'][$goodQueenId]['chunked'] = $chunked;
    }
    
    
    /**
     * @method setSolutionsQueens
     * setter method for passing the JSON input
     * 
     * @param string $queens, comma delimited list of Queen ids
     * @param string $spaces, comma delimited list of Queens square id
     */
    public function setSolutionQueens( string $queens, string $spaces )
    {
        $this->Proofs = [
            'queens'  => $queens,
            'spaces'  => $spaces
        ];
        
    }
    
    
    /**
     * @method getProofProp
     * getter method for return the gameboard dataset
     * see prototype in comments above
     * 
     * @return array object, multidimensional array of each queens data set.
     */
    public function getProofProp()
    {
        return $this->Poofs;
        
    }
      
}