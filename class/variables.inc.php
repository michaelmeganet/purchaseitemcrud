<?php

Class SQL extends Dbh {

    protected $sql; // class common variable

    public function __construct($sql) {//from program line inject into constructor
        $this->sql = $sql; //assign the protected $sql ($this->sql to the value of
        // injected value $sql.
    }

    public function getResultRowArray() {
        $resultset = array(); //define empty array
        $sql = $this->sql; // assign private $sql by the value of protected $sql
        //                  ($this->sql)
        //echo "\$sql = $sql <br>";
        $stmt = $this->connect()->prepare($sql); // $stmt must be local variable
        $stmt->execute();
        if ($stmt->rowCount()) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $resultset = $row;
            //echo "line 122 \$resultset from Line 118 \$smt->execute() of \$sql<br>";
            //echo "the array of sql reuslt : <br>";
            //  print_r($resultset);
            //echo "=========================<br>";
        } else {
            // do nothing;
            //echo "no result on SQL <br>";
        }

        return $resultset;
    }

    public function getResultOneRowArray() {
        $row = array();
        $sql = $this->sql;
//        echo "\$sql = $sql <br>";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return $row;
    }

    public function getRowCount() {

        $sql = $this->sql;
        //echo "\$sql = $sql <br>";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $number_of_rows = $stmt->fetchColumn();
        // echo "\$number_of_rows =  $number_of_rows <br>";
        return $number_of_rows;
    }

    public function getUpdate() {

        $sql = $this->sql;
        //echo "Line 165 , in getUpdate function of Class SQL,  \$sql = $sql <br>";
        $stmt = $this->connect()->prepare($sql);

        if ($stmt->execute()) {
            $result = 'updated';
        } else {
            $result = 'update fail';
        }
        return $result;
    }

    public function InsertData() {

        $sql = $this->sql;
        //echo "\$sql = $sql <br>";
        $stmt = $this->connect()->prepare($sql);

        if ($stmt->execute()) {
            $result = 'insert ok!';
        } else {
            $result = 'insert fail';
        }
        //echo "\$result = $result <br>";
        return $result;
    }
    public function getDelete() {

        $sql = $this->sql;
//        echo "\$sql = $sql <br>";
        $stmt = $this->connect()->prepare($sql);

        if ($stmt->execute()) {
            $result = 'deleted';
        } else {
            $result = 'delete failed';
        }
        return $result;
    }
//    public function getPartialLimit(){
//
//        $sql = $this->sql;
////        echo "\$sql = $sql <br>";
//        $stmt = $this->connect()->prepare($sql);
//
//        $stmt->execute();
//        if ($stmt->rowCount() > 0) {
//            // Define how we want to fetch the results
//            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
//        }
//
//        return
//    }
}

Class SQLBINDPARAM extends SQL {

    protected $sql;
    protected $bindparamArray;

    public function __construct($sql, $bindparamArray) {

        parent::__construct($sql);
        $this->bindparamArray = $bindparamArray;
    }

    public function InsertData2() {

        $sql = $this->sql;
        #echo $sql."<bR>";
        $stmt = $this->connect()->prepare($sql);
        $bindparamArray = $this->bindparamArray;
//        unset($bindparamArray['submit']);
//        print_r($bindparamArray);
//        echo "<br>";
//        $para = "";

        foreach ($bindparamArray as $key => $value) {
            # code...
            ${$key} = $value;
            $bindValue = $key;
            $bindParamdata = "bindParam(:{$bindValue}, $$bindValue) == ".$$bindValue; //this is for debugging purposes
            #echo "\$bindParamdata = $bindParamdata <br>";
            #########################################################
            # this line not successful, how to check in the future
            //  $stmt->bindParam(":$key", $value);
            ##########################################################
//          # this line is working,                                  #
            # {$bindValue} = calls the $key value                    #
            # $$bindValue = calls the value contained by $key array  #
            ##########################################################

            $stmt->bindParam(":{$bindValue}",$$bindValue);
        }

//        echo "=====var_dump \$stmt==================<br>";
//        var_dump($stmt);
//        echo "=====end of var_dump \$stmt==================<br>";
        if ($stmt->execute()) {
            $result = 'insert ok!';
        } else {
            $result = 'insert fail';
        }
        return $result;
    }
    public function UpdateData2() {

        $sql = $this->sql;
        #echo $sql."<bR>";
        $stmt = $this->connect()->prepare($sql);
        $bindparamArray = $this->bindparamArray;
//        unset($bindparamArray['submit']);
//        print_r($bindparamArray);
//        echo "<br>";
//        $para = "";

        foreach ($bindparamArray as $key => $value) {
            # code...
            ${$key} = $value;
            $bindValue = $key;
            $bindParamdata = "bindParam(:{$bindValue}, $$bindValue) == ".$$bindValue; //this is for debugging purposes
            #echo "\$bindParamdata = $bindParamdata <br>";
            #########################################################
            # this line not successful, how to check in the future
            //  $stmt->bindParam(":$key", $value);
            ##########################################################
//          # this line is working,                                  #
            # {$bindValue} = calls the $key value                    #
            # $$bindValue = calls the value contained by $key array  #
            ##########################################################

            $stmt->bindParam(":{$bindValue}",$$bindValue);
        }

//        echo "=====var_dump \$stmt==================<br>";
//        var_dump($stmt);
//        echo "=====end of var_dump \$stmt==================<br>";


        if ($stmt->execute()) {
            $result = 'Update ok!';
        } else {
            $result = 'Update fail';
        }
        return $result;
    }
}
?>