<?php 
echo "rewriteOK".md5((isset($_SERVER["HTTPS"]) ? "on":"")."/".$_SERVER["HTTP_HOST"]."/".dirname(__FILE__));