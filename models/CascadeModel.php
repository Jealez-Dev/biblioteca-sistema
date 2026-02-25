<?php

class CascadeModel {

    /**
     * Recursive delete that follows "strong" relationships (FKs that are NOT NULL).
     * 
     * @param string $tableName Name of the table where the deletion starts.
     * @param array $whereConditions Associative array of column => values for the WHERE clause.
     * @param mysqli|null $conexion Existing connection for transaction management.
     * @return bool Success or failure.
     */
    public static function RecursiveDelete($tableName, $whereConditions, $conexion = null) {
        $shouldClose = false;
        if ($conexion === null) {
            include_once('core/Conectar.php');
            $conexion = Conectar::conexion();
            mysqli_autocommit($conexion, FALSE);
            $shouldClose = true;
        }

        try {
            // 1. Find dependent tables using INFORMATION_SCHEMA
            $dbName = require 'config/database.php';
            $dbConfig = $dbName;
            $dbName = $dbName['database'];

            // Group FKs by constraint to handle composite keys
            $sqlDeps = "
                SELECT 
                    CONSTRAINT_NAME,
                    TABLE_NAME, 
                    COLUMN_NAME, 
                    REFERENCED_COLUMN_NAME
                FROM 
                    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE 
                    REFERENCED_TABLE_NAME = '$tableName' 
                    AND TABLE_SCHEMA = '$dbName'
                    AND TABLE_NAME != '$tableName'
            ";

            $resDeps = mysqli_query($conexion, $sqlDeps);
            if (!$resDeps) throw new Exception(mysqli_error($conexion));

            $dependencies = [];
            while ($row = mysqli_fetch_assoc($resDeps)) {
                $dependencies[$row['CONSTRAINT_NAME']]['table'] = $row['TABLE_NAME'];
                $dependencies[$row['CONSTRAINT_NAME']]['mapping'][] = [
                    'col' => $row['COLUMN_NAME'],
                    'ref' => $row['REFERENCED_COLUMN_NAME']
                ];
            }

            foreach ($dependencies as $constraint => $info) {
                $depTable = $info['table'];
                
                // Check if ALL columns in this FK are NOT NULL
                $isStrong = true;
                foreach ($info['mapping'] as $map) {
                    $depColumn = $map['col'];
                    $sqlCheckNull = "
                        SELECT IS_NULLABLE 
                        FROM INFORMATION_SCHEMA.COLUMNS 
                        WHERE TABLE_SCHEMA = '$dbName' 
                        AND TABLE_NAME = '$depTable' 
                        AND COLUMN_NAME = '$depColumn'
                    ";
                    $resNull = mysqli_query($conexion, $sqlCheckNull);
                    $nullData = mysqli_fetch_assoc($resNull);
                    if (!$nullData || $nullData['IS_NULLABLE'] === 'YES') {
                        $isStrong = false;
                        break;
                    }
                }

                if ($isStrong) {
                    // Get the values required to identify children
                    $refCols = array_map(function($m) { return $m['ref']; }, $info['mapping']);
                    $whereClause = [];
                    foreach ($whereConditions as $col => $val) {
                        $whereClause[] = "$col = '$val'";
                    }
                    
                    $sqlParent = "SELECT " . implode(', ', $refCols) . " FROM $tableName WHERE " . implode(' AND ', $whereClause);
                    $resParent = mysqli_query($conexion, $sqlParent);
                    
                    while ($parentRow = mysqli_fetch_assoc($resParent)) {
                        $childConditions = [];
                        foreach ($info['mapping'] as $map) {
                            $childConditions[$map['col']] = $parentRow[$map['ref']];
                        }
                        // Recursive call
                        self::RecursiveDelete($depTable, $childConditions, $conexion);
                    }
                }
            }

            // 2. Finally, delete the record(s) from the current table
            $whereClause = [];
            foreach ($whereConditions as $col => $val) {
                $whereClause[] = "$col = '$val'";
            }
            $sqlDelete = "DELETE FROM $tableName WHERE " . implode(' AND ', $whereClause);
            
            if (!mysqli_query($conexion, $sqlDelete)) {
                throw new Exception(mysqli_error($conexion));
            }

            if ($shouldClose) {
                mysqli_commit($conexion);
                Conectar::desconexion($conexion);
            }
            return true;

        } catch (Exception $e) {
            if ($shouldClose) {
                mysqli_rollback($conexion);
                Conectar::desconexion($conexion);
            }
            return false;
        }
    }
}
