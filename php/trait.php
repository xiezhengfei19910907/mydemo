<?php
//trait A {
//    public function smallTalk() {
//        echo 'a';
//    }
//    public function bigTalk() {
//        echo 'A';
//    }
//}
//
//trait B {
//    public function smallTalk() {
//        echo 'b';
//    }
//    public function bigTalk() {
//        echo 'B';
//    }
//}
//
//class Talker {
//    use A, B {
//        B::smallTalk insteadof A;
//        A::bigTalk insteadof B;
//    }
//}
//
//class Aliased_Talker {
//    use A, B {
//        B::smallTalk insteadof A;
//        A::bigTalk insteadof B;
//        B::bigTalk as talk;
//    }
//}
//
//$talker = new Talker();
//$talker->smallTalk();
//$talker->bigTalk();
//
//
//$aliased_talker = new Aliased_Talker();
//$aliased_talker->bigTalk();
?>

<?php
//trait Hello {
//    public function sayHelloWorld() {
//        echo 'Hello'.$this->getWorld();
//    }
//    abstract public function getWorld();
//}
//
//class MyHelloWorld {
//    private $world;
//    use Hello;
//    public function getWorld() {
//        return $this->world;
//    }
//    public function setWorld($val) {
//        $this->world = $val;
//    }
//}
?>




<?php
//function foo(){
//    $bar = 1;
//    try{
//        throw new Exception('I am Wu Xiancheng.');
        return 20;
    //}catch(Exception $e){
    //
    //    exit();
    //
    //    return $bar;
    //    $bar--; // this line will be ignored
    //}finally{
    //    return 100;
    //}
//}
//echo foo(); //100
?>




<?php

class a {}

class b {}

class c extends a {}

interface e {

    public function test();

}

interface d {}

interface f extends e,d {}

?>
