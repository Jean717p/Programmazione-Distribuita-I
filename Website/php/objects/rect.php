<?php
    class Rect
    {
        public $username;
        public $x0,$x1,$y0,$y1,$timestamp;

        function __construct($u, $x0, $x1, $y0, $y1,$timestamp) {
            $this->username = $u;
            $this->x0 = $x0;
            $this->x1 = $x1;
            $this->y0 = $y0;
            $this->y1 = $y1;
            $this->timestamp = $timestamp;
        }
    }
?>
