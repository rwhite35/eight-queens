<?php
namespace Gameboard\Calculate\Diagonals;

interface CalcDiagonalsInterface
{
    
    public function aboveRowLeft(
        int $yCoord, 
        int $xCoord, 
        int $rowsAbove, 
        int $colsLeft
    ) : array;
    
    public function aboveRowRight(
        int $yCoord, 
        int $xCoord, 
        int $rowsAbove, 
        int $colsRight
    ) : array;
    
    public function belowRowLeft(
        int $yCoord, 
        int $xCoord, 
        int $rowsBelow, 
        int $colsLeft
    ) : array;
    
    public function belowRowRight(
        int $yCoord, 
        int $xCoord, 
        int $rowsBelow, 
        int $colsRight
     ) : array;
    
    public function setCalcDiagonals(array $aboveRow, array $belowRow );
    
    public function getCalcDiagonals();
    
}