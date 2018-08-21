<?php
namespace Gameboard\Model;

use Gameboard;

class Validate
{
    /**
     * @property QueenSort
     * 
     */
    public $QueenSort;
    
    /**
     * @property CloneProofs
     * read only reference to Solutions Proofs property
     */
    private $CloneProofs;
    
    
    /**
     * @method class constructor
     */
    public function __construct()
    {
        $this->QueenSort = [];
        $this->CloneProofs = [];
        
    }
    
    
    /**
     * @method setProofs
     * setter methods for cloning Solutions Proofs property
     * passed by reference so it is a actual clone of the 
     * current data set.
     * 
     * @param array $Proofs
     */
    public function setProofs( &$Proofs )
    {
        $this->CloneProofs = $Proofs;
        
    }
    
        
    /**
     * @method validateQueensByChunkTotals
     * @todo 20180820, needs refactored using rescursive search function.
     * 
     * @return array QueenSort, hashmap of queens(key) with captured ID's as value
     * * proto Array[ [Q101] => Q105, [Q102] => , [Q103] => Q104,... ]
     */
    public function validateQueensByChunkTotals()
    {
        $foundId = "";
        foreach( $this->CloneProofs['Q'] as $goodQueenId => $goodQueenArr ) {
            $this->QueenSort[$goodQueenId] = "";
            
            /* check if another queens occupies the same rows or column */
            foreach ( $this->CloneProofs['Q'] as $evilQueenId => $evilQueenArr ) {
                if ( substr($evilQueenId, -1) != substr($goodQueenId, -1) &&
                    $evilQueenId != $foundId )
                    if ( $evilQueenArr['Ycoord'] == $goodQueenArr['Ycoord'] ||
                        $evilQueenArr['Xcoord'] == $goodQueenArr['Xcoord'] )
                        $this->QueenSort[$goodQueenId] .= $evilQueenId .", ";
                        $foundId = $evilQueenId;
            }
            
            /* check the diagonals for another queen */
            if ( is_array( $goodQueenArr['chunked'] ) && !empty( $goodQueenArr['chunked'] ) ) {
                $foundId = "";
                
                // for each of good queens chunked values, search for an evil queen match
                foreach( $goodQueenArr['chunked'] as $gqChunkValue ) {
                    
                    // loop over each evil queen testing her chunk with the good queens search value.
                    foreach ( $this->CloneProofs['Q'] as $evilQueenId => $evilQueenArr ) {
                        
                        // only check evil queens that have NOT aleady been checked.
                        $eq = substr($evilQueenId, -1);    // ie 3 or 4
                        $gq = substr( $goodQueenId, -1 );    // is 5 or 6
                        if (  $eq != $gq && $eq > $gq && $evilQueenId != $foundId ) {
                            
                            // if evil queen has a chunk total matching good queens, compare coordinates
                            if( array_search( $gqChunkValue, $evilQueenArr['chunked'], true ) !== false ) {
                                
                                // evil queens coordinate
                                $eqCoord = $this->CloneProofs['Q'][$evilQueenId]['SqrCoord'];
                                
                                // check this good queens diagonal for a matching evil coordinate
                                foreach ($goodQueenArr as $gqId => $gqValue) {
                                    
                                    if ( is_array( $gqValue ) && !empty($gqValue) &&
                                        array_search( $eqCoord, $gqValue ) !== false ) {
                                            
                                            // evil queen can be captured!
                                            $this->QueenSort[$goodQueenId] .= $evilQueenId .", ";
                                            $foundId = $evilQueenId;
                                        }
                                        
                                }   // foreach loop check squares
                                
                            }   // if evil has a chunked array
                            
                        }   // if ckeck evil queen matching chunk total
                        
                    }   // foreach loop evilQueenId
                    
                }       // foreach loop chunked k value
                
            }    // if goodQueen chunked array
            
        }       // foreach loop goodQueenId
        
        return $this->QueenSort;
        
    }
    
    
    /**
     * @method queenTakesQueen
     * Checks if this Queen captured another queen
     *
     * @param string $goodQueenId, this Queens ID ( Q101, Q108, etc )
     *
     * @param array $queenKeys, enumerated array of queen keys
     * * proto Array[ 0 => Q101,..., 7 => Q108 ]
     *
     * @param array $qSqrId, enumerated array of occupied squares.
     * order correlates to the queen keys Q101 occupies G, Q108 occupies BH
     * * proto Array[ 0 => G,..., 7 => BH ]
     *
     * @param array $matrix, enumerated multidim array holding the
     * gameboards coordinates and square ids. See Model for prototype.
     *
     * @return boolean true when she can capture another queen
     */
    private function queenTakesQueen( string $goodQueenId, array $queenKeys, array $qSqrId, array $matrix )
    {
        $capture        = false;
        $evilQueenId    = [];
        
        /* compare queens coordinates with this Queens movable spaces */
        for ( $i = 0; $i < count($queenKeys); $i++ ) {
            if ( $goodQueenId == $queenKeys[$i] ) {
                continue;           // don't check this Queen
                
            } else {                // get evil queens coord
                $evilQueenId = $this->searchRecursively( $matrix, $qSqrId[$i], $queenKeys[$i] );
                
                error_log( __LINE__ .": the evil queen " . $queenKeys[$i] .
                    " coordinates are " . $evilQueenId[ $queenKeys[$i] ] .
                    " for the space with id " . $qSqrId[$i] );
            }
        }
        
        return $capture;
        
    }
    
    
    /**
     * @method searchRecursively
     * Recurse down through each row and column to return the evil
     * queens coordinates for the square she occupies.
     *
     * @param array $matrix, enumerated multidim array on the gameboard, see Model.
     *
     * @param string $needle, the square id to check ex. square id "U" = Y3,X5 coords
     *
     * @return array $coords hashmap of evil queens square id as key and coords as value
     * * proto Array[ Q103 => 3,5 ]
     */
    private function searchRecursively( array $matrix, string $needle, string $qKey )
    {
        $coords = [];
        foreach( $matrix as $row => $rowspaces ) {
            foreach ( $rowspaces as $k => $v ) {
                if ( $needle === $v ) return $coords = [ $qKey => $row ."," .$k ];
            }
        }
        
    }
}