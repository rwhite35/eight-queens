<?php 
namespace Gameboard\Model;

class Board
{
    /**
     * XMax
     * X coordinate
     */
    public static $XMax = 8;
    
    /**
     * YMax
     * Y coordinate
     */
    public static $YMax = 8;
    
    /**
     * Square
     * Board squares
     */
    public $Squares;
    
    /**
     * ConstaintParams
     * Conditions to satisfy 
     */
    public $ConstaintParams;
    
    /**
     * Square Ids
     * called from the view render view/board
     * @var array data set for gameboard matrix
     */
    protected $SquareIds;
    
    
    public function __construct() 
    {
        $this->Squares = [
            "startLabel" => "A",
            "total" => 64
        ];
        
        $this->SquareIds = [];
        $this->ConstaintParams = [];
        
    }
    
    
    /**
     * @method generateSquareLabels
     * dynamically generates each game board square id.
     * the square id is used to test the solution
     * has continuous time signature O(1).
     * 
     * @return array sqrLabel with size of the game board (8 x 8 = 64)
     */
    public function generateSquareLabels()
    {
        
        $c = 0;
        $j = 0;
        $prefix = null;
        $sqrLabels = [];
        $alphaChars = [ "A","B","C","D","E","F","G","H","I","J","K","L",
            "M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z" ];
        
        for( $i = 1; $i <= $this->Squares['total']; $i++ ) {
            
            // assign unique label to each square
            $sqrLabels[$i] = $prefix . $alphaChars[$c];
            $c++;
            
            // reset values after 26 and 52
            if ( $i % 26 == 0 ) {
                $c = 0;
                $prefix = $alphaChars[$j];
                $j++; // increment after every 26 loops.
            } 
        }
        
        return $sqrLabels;
        
    }
    
    
    /**
     * @method boardMatrix
     * geneates the gameboard using the data set defined in Model.
     *
     * @return array|mixed
     */
    public function boardMatrix()
    {
        $this->SquareIds = $this->generateSquareLabels();
        $matrix = [];
        $l = 1;
        
        foreach ( range( 1, self::$XMax ) as $row ) {
            
            foreach ( range(1, self::$XMax ) as $col ) {
                $matrix[$row][$col] = $this->SquareIds[$l];
                $l++;
            }
            
        }
        
        return $matrix;
        
    }
    
    
    /**
     * @method diagonalConstants
     * defines constants used to calculate the diagonal squares 
     * that a queen "occupies" from her current position.
     * 
     * @return array ConstraintParams
     */
    public static function diagonalConstants()
    {
        // add these values to calculate the diagonal squares
        return [
            "Left" => [
                'Above' => [
                    'Y' => -1,
                    'X' => -1
                ],
                'Below' => [
                    'Y' => 1,
                    'X' => -1
                ]
            ],
            "Right" => [
                'Above' => [
                    'Y' => -1,
                    'X' => 1
                ],
                'Below' => [
                    'Y' => 1,
                    'X' => 1
                ]
            ]
        ];
        
    }
    
    
}


/*
 * Test Modle Objects
 */
/*
class TestBoard extends Board
{
    public function TestGenerateSquareLabels() {
        return $this->generateSquareLabels();
    }
    
}

$gameboardObj = new TestBoard();
echo $gameboardObj->TestGenerateSquareLabels();
*/

?>