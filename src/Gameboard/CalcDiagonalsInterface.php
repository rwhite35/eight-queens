<?php
namespace Gameboard\Calculate\Diagonals;

interface CalcDiagonalsInterface
{
    
    public function aboveRowLeft(int $yCoord, int $xCoord, int $rowsAbove) : array;
    
    public function aboveRowRight(int $yCoord, int $xCoord, int $rowsAbove) : array;
    
    public function belowRowLeft(int $yCoord, int $xCoord, int $rowsBelow) : array;
    
    public function belowRowRight(int $yCoord, int $xCoord, int $rowsBelow) : array;
    
    public function setCalcDiagonals(array $aboveRow, array $belowRow );
    
    public function getCalcDiagonals();
    
}