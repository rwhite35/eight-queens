<?php
namespace Gameboard\Calculate\Diagonals;

use Gameboard\Model\Board;

class CalcDiagonals implements CalcDiagonalsInterface
{
    var $aboveRowCoords;
    var $belowRowCoords;
    var $diagContants;
    var $iglmt;             // ignore coords when 0 or 9
    
    
    public function __construct()
    {
        $this->aboveRowCoords = [];
        $this->aboveRowCoords = [];
        $this->diagContants = Board::diagonalConstants();
        $this->iglmt = [ 0, Board::$YMax + 1 ]; 
    }
    
    
    /**
     * Y coord decreases above the current row (-1)
     * X coord decreases above the currnet row (-1)
     * {@inheritDoc}
     * @see \Gameboard\Calculate\Diagonals\CalcDiagonalsInterface::aboveRowLeft()
     */
    public function aboveRowLeft( int $yCoord, int $xCoord, int $rowsAbove, int $colsLeft ) : array
    {
        $y = $yCoord;
        $x = $xCoord;
        $i = ( $colsLeft <= $rowsAbove ) ? $colsLeft : $rowsAbove;
        $coords = [];
        
        do { // evaluate the expression at the end of the iteration for truefullness
            
            $y = $y + $this->diagContants['Left']['Above']['Y'];
            $x = $x + $this->diagContants['Left']['Above']['X'];
            if ( $x > 0 ) $coords[] = $y .",". $x; // ignore when X is 0 on first iteration
            $i--;
            
        } while ( $i > 0 );
        
        return $coords;
        
    }
    
    
    /**
     * Y coord decreases above the current row (-1)
     * X coord increases above the current row (1)
     * {@inheritDoc}
     * @see \Gameboard\Calculate\Diagonals\CalcDiagonalsInterface::aboveRowRight()
     */
    public function aboveRowRight( int $yCoord, int $xCoord, int $rowsAbove, int $colsRight ) : array
    {
        $y = $yCoord;
        $x = $xCoord;
        $i = ( $rowsAbove + $x <= 8 ) ? $rowsAbove : $colsRight;
        $coords = [];
        
        do {
            
            $y = $y + $this->diagContants['Right']['Above']['Y'];
            $x = $x + $this->diagContants['Right']['Above']['X'];
            if ( $x < 9 ) $coords[] = $y .",". $x; // ignore 9 and above on first iteration
            $i--;
            
        } while ( $i > 0 );
        
        return $coords;
        
    }
    
    
    /**
     * Y coord increased below the current row (1)
     * X coord decreases below the current row (-1)
     * {@inheritDoc}
     * @see \Gameboard\Calculate\Diagonals\CalcDiagonalsInterface::belowRowLeft()
     */
    public function belowRowLeft( int $yCoord, int $xCoord, int $rowsBelow, int $colsLeft ) : array
    {
        $y = $yCoord;
        $x = $xCoord;
        $i = ( $colsLeft <= $rowsBelow ) ? $colsLeft : $rowsBelow;
        $coords = [];
        
        do {
            
            $y = $y + $this->diagContants['Left']['Below']['Y'];
            $x = $x + $this->diagContants['Left']['Below']['X'];
            if ( $i > 0 ) $coords[] = $y .",". $x;
            $i--;
            
         } while ( $i > 0  );
        
        return $coords;
        
    }
    
    /**
     * Y coord increases below the current row (1)
     * X coord increases below the current row (1)
     */
    public function belowRowRight( int $yCoord, int $xCoord, int $rowsBelow, int $colsRight ) : array
    {
        $y = $yCoord;
        $x = $xCoord;
        $i = ( $rowsBelow + $x <= 8 ) ? $rowsBelow : $colsRight;
        $coords = [];
        
        do { // evaluate the expression at the end of the iteration for truefullness
            
            $y = $y + $this->diagContants['Right']['Below']['Y'];
            $x = $x + $this->diagContants['Right']['Below']['X'];
            if ($x < 9 ) $coords[] = $y .",". $x; // ignore when X is 9 on first iteration
            $i--;
            
        } while ( $i > 0 );
         
         return $coords;
         
    }
    
    
    public function getCalcDiagonals()
    {}
    
    
    public function setCalcDiagonals(array $aboveRow, array $belowRow)
    {}

}