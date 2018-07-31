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
    public function aboveRowLeft( int $yCoord, int $xCoord, int $rowsAbove ) : array
    {
        $y = $yCoord;
        $x = $xCoord;
        $coords = [];
        $ignore = [ 0, Board::$YMax + 1 ];
        
        $r = $rowsAbove;
        while ( $r > 0 && $x > 0 ) {
            $y = $y + $this->diagContants['Left']['Above']['Y'];
            $x = $x + $this->diagContants['Left']['Above']['X'];
            if ( $r > 0 || $x > 0 ) $coords[] = $y .",". $x;
            $r--;
            
        }
        
        return $coords;
        
    }
    
    
    /**
     * Y coord decreases above the current row (-1)
     * X coord increases above the current row (1)
     * {@inheritDoc}
     * @see \Gameboard\Calculate\Diagonals\CalcDiagonalsInterface::aboveRowRight()
     */
    public function aboveRowRight( int $yCoord, int $xCoord, int $rowsAbove ) : array
    {
        $y = $yCoord;
        $x = $xCoord;
        $coords = [];
        $ignore = [ 0, Board::$YMax + 1 ];
        
        $r = $rowsAbove;
        while ( $r > 0 && $x < 9 ) {
            $y = $y + $this->diagContants['Right']['Above']['Y'];
            $x = $x + $this->diagContants['Right']['Above']['X'];
            if ( $r > 0 || $x < 9 ) $coords[] = $y .",". $x;
            $r--;
        }
        
        return $coords;
        
    }
    
    
    /**
     * Y coord increased below the current row (1)
     * X coord decreases below the current row (-1)
     * {@inheritDoc}
     * @see \Gameboard\Calculate\Diagonals\CalcDiagonalsInterface::belowRowLeft()
     */
    public function belowRowLeft( int $yCoord, int $xCoord, int $rowsBelow ) : array
    {
        $y = $yCoord;
        $x = $xCoord;
        $coords = [];
        
        $r = $yCoord;
        while ( $r < 9 && $x > 0 ) {
            $y = $y + $this->diagContants['Left']['Below']['Y'];
            $x = $x + $this->diagContants['Left']['Below']['X'];
            if ( $r < 9 || $x > 0 ) $coords[] = $y .",". $x;
            $r++;
        }
        
        return $coords;
        
    }
    
    /**
     * Y coord increases below the current row (1)
     * X coord increases below the current row (1)
     */
    public function belowRowRight( int $yCoord, int $xCoord, int $rowsBelow ) : array
    {
        $y = $yCoord;
        $x = $xCoord;
        $coords = [];
        
        $r = $yCoord;
        while ( $r < 9 && $x < 9 ) {
             $y = $y + $this->diagContants['Right']['Below']['Y'];
             $x = $x + $this->diagContants['Right']['Below']['X'];
             if ( $r < 9 || $x < 9 ) $coords[] = $y .",". $x;
             $r++;
         }
         
         return $coords;
        
    }
    
    
    public function getCalcDiagonals()
    {}
    
    
    public function setCalcDiagonals(array $aboveRow, array $belowRow)
    {}

}