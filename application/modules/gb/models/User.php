<?php

/**
 * Description of User
 *
 * @author ppd
 */
class User extends Model
{
    protected $tableName = 'tUsers';
    
    public function selectCatigorizedUsers()
    {
        $sql = 'SELECT '.
                $this->tableName.'.user_id, '.
                $this->tableName.'.UserName, '.
                'tCats.cat_name AS Category, '.
                $this->tableName.'.Email, '.
                $this->tableName.'.Homepage, '.
                $this->tableName.'.MessageText, '.
                $this->tableName.'.MessageDate '.
                'FROM '.$this->tableName.' '.
                'INNER JOIN tCats '.
                'ON('.$this->tableName.'.Category='.
                'tCats.cat_id)';
        $query = $this->connection->query($sql);
        if(!$query) {
            exit('Unable to execute the query '.$sql.'<br>');
        }
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }
}
