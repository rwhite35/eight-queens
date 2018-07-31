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
    
    
    public function __construct()
    {
        $this->Proofs = [];
        $this->Board = new Board();
        $this->Diagonal = new CalcDiagonals();
    }
    
    
    /**
     * Takes game board input and calculates the solution
     * based on each Queens position in relationship 
     * to each other queen. JSON input looks like: 
     * * [{"queens":"Q101,Q102,Q103,Q104,Q105,Q106,Q107,Q108","spaces":"G,M,X,Y,AI,AT,AX,BH"}]
     */
    public function checkSolution() 
    {
        
        $queenKeys = explode( ",", $this->Proofs['queens'] );
        $qSqrId = explode( ",", $this->Proofs['spaces'] );
        $matrix = $this->Board->boardMatrix();
        
        /* Outer loop over each Queen. Only loops once for each 
         * and has a time complexity of 1 or continous time.
         */
        $row = 1;
        foreach( $queenKeys as $k => $qId ) {
            
            $this->Proofs['Q'][$qId]['SqrId'] = $qSqrId[$k];
            
            /* Inner loop over each row on the gameboard and 
             * assign Queens coordinates and square ID. loops once
             * for each row and has a time complexity of 1 or continous time
             */
            foreach( $matrix[$row] as $col => $mSqrId ) {
                
                if( $this->Proofs['Q'][$qId]['SqrId'] == $mSqrId ) {
                    $this->Proofs['Q'][$qId]['Ycoord']  = $row;
                    $this->Proofs['Q'][$qId]['Xcoord']  = $col;
                }
                
                if( $col == Board::$XMax ) $row++;  // move internal pointer to next row
            }
            
            // calculates Queens relative position to the game board
            $this->calcRelativePosition($qId);
            
            // calculate Queens diagonal above 
            $this->calcDiagonals($qId);
         
        } // close outer foreach queen
        
        ob_start();
        echo "Queens relative position to gameboard: ";
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
    private function calcRelativePosition( string $qId )
    {
           $yLessOne    = Board::$YMax - 1;
           $xLessOne    = Board::$XMax - 1;
           
           $this->Proofs['Q'][$qId]['rowsAbove']    = '';
           $this->Proofs['Q'][$qId]['rowsBelow']    = '';
           $this->Proofs['Q'][$qId]['colsLeft']     = '';
           $this->Proofs['Q'][$qId]['colsRight']    = '';
           $this->Proofs['Q'][$qId]['ydLeftAbv']    = [];
           $this->Proofs['Q'][$qId]['ydRightAbv']   = [];
           $this->Proofs['Q'][$qId]['ydLeftBlw']    = [];
           $this->Proofs['Q'][$qId]['ydRightBlw']   = [];
          
           /* calculate the rows above this square */
           $rowsAbove   = ( $this->Proofs['Q'][$qId]['Ycoord'] == 1 ) 
            ? 0 : $yLessOne - ( Board::$YMax - $this->Proofs['Q'][$qId]['Ycoord'] );
           
            $this->Proofs['Q'][$qId]['rowsAbove']   = $rowsAbove;
           
           /* calculate the rows below this square */
           $rowsBelow   = Board::$YMax - $this->Proofs['Q'][$qId]['Ycoord'];
           
           $this->Proofs['Q'][$qId]['rowsBelow']    = $rowsBelow;
           
           /* calculate the columns to the left of this square */
           $colsLeft    = ( $this->Proofs['Q'][$qId]['Xcoord'] == 1 ) 
            ? 0 : $xLessOne - ( Board::$XMax - $this->Proofs['Q'][$qId]['Xcoord'] );
           
            $this->Proofs['Q'][$qId]['colsLeft']     = $colsLeft;
           
           /* calculate the columns to the right of the square */
           $colsRight   = Board::$XMax - $this->Proofs['Q'][$qId]['Xcoord'];
           
           $this->Proofs['Q'][$qId]['colsRight']    = $colsRight;
           
       
    }
    
    
    
    private function calcDiagonals( string $qId )
    {
        
        $calculated = [ 'rowsAbove' => false, 'rowsBelow' => false ];
        $rowsAbove  = $this->Proofs['Q'][$qId]['rowsAbove'];
        $rowsBelow  = $this->Proofs['Q'][$qId]['rowsBelow'];
        $qYcoord    = $this->Proofs['Q'][$qId]['Ycoord'];
        $qXcoord    = $this->Proofs['Q'][$qId]['Xcoord'];
        
        // calcuate the left and right diagonals above this row
        if ( $rowsAbove > 0 ) {
            $this->Proofs['Q'][$qId]['ydLeftAbv'] = 
                $this->Diagonal->aboveRowLeft( $qYcoord, $qXcoord, $rowsAbove );
            
            $this->Proofs['Q'][$qId]['ydRightAbv'] = 
                $this->Diagonal->aboveRowRight( $qYcoord, $qXcoord, $rowsAbove );
                    
            $calculated['rowsAbove'] = true;
            
        }
        
        // calculate the left and right diagonals below this row
        if ( $rowsBelow > 0 ) {
            $this->Proofs['Q'][$qId]['ydLeftBlw'] =
                $this->Diagonal->belowRowLeft( $qYcoord, $qXcoord, $rowsBelow );

            $this->Proofs['Q'][$qId]['ydRightBlw'] =
                $this->Diagonal->belowRowRight( $qYcoord, $qXcoord, $rowsBelow );
            
            $calculated['rowsBelow'] = true;
        }
        
    }
    
    
    public function setSolutionQueens( string $queens, string $spaces ) 
    {
        $this->Proofs = [
            'queens' => $queens,
            'spaces' => $spaces
        ];
    }
    
    
    public function getProofProp() 
    {
        return $this->Poofs;
    }
}