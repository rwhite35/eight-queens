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
        foreach( $this->CloneProofs['Q'] as $qId => $qArray ) {
            $this->QueenSort[$qId] = "";
            
            /* check if another queens occupies the same rows or column */
            foreach ( $this->CloneProofs['Q'] as $eqKey => $eqArray ) {
                if ( substr($eqKey, -1) != substr($qId, -1) &&
                    $eqKey != $foundId )
                    if ( $eqArray['Ycoord'] == $qArray['Ycoord'] ||
                        $eqArray['Xcoord'] == $qArray['Xcoord'] )
                        $this->QueenSort[$qId] .= $eqKey .", ";
                        $foundId = $eqKey;
            }
            
            /* check the diagonals for another queen */
            if ( is_array( $qArray['chunked'] ) && !empty( $qArray['chunked'] ) ) {
                $foundId = "";
                
                // for each chunk value, search for a match
                foreach( $qArray['chunked'] as $ckneedle ) {
                    
                    // loop over each enemy queen testing their chunk with this needle.
                    foreach ( $this->CloneProofs['Q'] as $eqKey => $eqArray ) {
                        
                        // only check queens that have not aleady been checked.
                        $e = substr($eqKey, -1);    // ie 3 or 4
                        $q = substr( $qId, -1 );    // is 5 or 6
                        if (  $e != $q && $e > $q && $eqKey != $foundId ) {
                            
                            // if enemy queen has a chunk total matching ours, check her coordinates
                            if( array_search( $ckneedle, $eqArray['chunked'], true ) !== false ) {
                                
                                // get enemy queens coordinate
                                $eqCoordNeedle = $this->CloneProofs['Q'][$eqKey]['SqrCoord'];
                                
                                // check this queens diagonals for a matching enemy coordinate
                                foreach ($qArray as $qqId => $qqValue) {
                                    
                                    if ( is_array( $qqValue ) && !empty($qqValue) &&
                                        array_search( $eqCoordNeedle, $qqValue ) !== false ) {
                                            
                                            // the enemy queens id, she can be captured!
                                            $this->QueenSort[$qId] .= $eqKey .", ";
                                            $foundId = $eqKey;
                                        }
                                        
                                }   // foreach loop check squares
                                
                            }   // if enemy has a chunked array that matches our chunk number,
                            // check their coordinates for possible capture.
                            
                        }   // if not ourself, ckeck for enemy queen matching chunk total
                        
                    }   // foreach loop eqKey
                    
                }       // foreach loop chunked k value
                
            }    // if qArray chunked
            
        }       // foreach loop qId
        
        return $this->QueenSort;
        
    }
    
    
    /**
     * @method queenTakesQueen
     * Checks if this Queen captured another queen
     *
     * @param string $qId, this Queens ID ( Q101, Q108, etc )
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
    private function queenTakesQueen( string $qId, array $queenKeys, array $qSqrId, array $matrix )
    {
        $capture        = false;
        $enemyCoords    = [];
        
        /* compare queens coordinates with this Queens movable spaces */
        for ( $i = 0; $i < count($queenKeys); $i++ ) {
            if ( $qId == $queenKeys[$i] ) {
                continue;           // don't check this Queen
                
            } else {                // get enemy queens coord
                $enemyCoords = $this->searchRecursively( $matrix, $qSqrId[$i], $queenKeys[$i] );
                
                error_log( __LINE__ .": the enemy queen " . $queenKeys[$i] .
                    " coordinates are " . $enemyCoords[ $queenKeys[$i] ] .
                    " for the space with id " . $qSqrId[$i] );
            }
        }
        
        return $capture;
        
    }
    
    
    /**
     * @method searchRecursively
     * Recurse down through each row and column to return the enemy
     * queens coordinates for the square she occupies.
     *
     * @param array $matrix, enumerated multidim array on the gameboard, see Model.
     *
     * @param string $needle, the square id to check ex. square id "U" = Y3,X5 coords
     *
     * @return array $coords hashmap of enemy queens square id as key and coords as value
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