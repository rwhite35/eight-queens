<?php 
namespace Gameboard\Controller;

use Gameboard\Model\Board;
use Gameboard\Solutions\Solution as Solution;

class BoardController extends Board
{
    /**
     * Model object 
     * called from the view render view/board
     * @var array data set for gameboard matrix
     */
    // protected $SquareLabels;
    
    /**
     * Model properties
     * @var int game grids X and Y size (8 x 8) 
     */
    protected $Board;
    
    
    public function __construct()
    {
        $this->Board = new Board();
    }
    
    
    /**
     * @method boardAction
     * instantiated on initial load from view/board view.
     * data set for an 8 x 8 game board
     * 
     * @return array|string
     */
    public function boardAction()
    {
        $matrix = $this->Board->boardMatrix();
        return $matrix;   
    }
    
    
    /**
     * @method submitAction
     * called on submit from view. decodes json object.
     * expects an indexed array of associative arrays see prototype.
     * instantiates the Solutions class which test the submitted solution
     *  
     * @param array $get JSON object passed in from view
     * proto: Array( [0] => Array([queens] => Q106,Q107,Q108, [spaces] => AQ,AW,BH) )
     * 
     * @return string
     */
    public function submitAction( array $get )
    {
        $trialArray = json_decode( $get['Trial'], true );
        
        if( $trialArray ) {
            
            $submitSolution = new Solution();
            $submitSolution->setSolutionQueens(
                $trialArray[0]['queens'],            // string "Q101,Q102,Q103..."
                $trialArray[0]['spaces']             // string "A,B,C..."
             );
            
            $submitSolution->checkSolution();
            
            $msg = "{ That\'s all folks! }";
            return $msg;
        }
        
    }
    
}
?>