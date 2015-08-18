<?php
class page{
    var $path;
    var $page_name;

    var $html;

    var $sub_nav_data;

    //Take this from dB later...
    var $weekTimers = array(
        "Motor1" => "FunTechHouse/WeekTimer/Motor1",
        "Motor2" => "FunTechHouse/WeekTimer/Motor2",
        "Utebelysning" => "FunTechHouse/WeekTimer/Utebel",

        "Rum1"   => "FunTechHouse/WeekTimer/Rum1",
        "Rum2"   => "FunTechHouse/WeekTimer/Rum2",
        "Rum3"   => "FunTechHouse/WeekTimer/Rum3",
    );


    function __construct($path, $title, $skiphead){
        if(date_default_timezone_set('Europe/Stockholm') == 0)
        {
            print "<!-- Error uknown timezone using UTC as default -->\n";
            date_default_timezone_set('UTC');
        }

        $this->path = $path;
        require_once($path."class.html.php");
        $this->html = new html();

        $this->getDirName();
        $this->sub_nav_data = "";

        print "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n".
            "<html>\n<head>\n".
            "\t<title>FunTechHouse - $title</title>\n";

        print "\t<link rel=\"stylesheet\" title=\"std\" ".
            "media=\"screen\"   href=\"".$this->path."screen.css\"   type=\"text/css\">\n";

        print "\t<link rel=\"stylesheet\" title=\"std\" ".
            "media=\"print\"    href=\"".$this->path."print.css\"    type=\"text/css\">\n";

        //Mobile phones?
        //print "\t<link rel=\"stylesheet\" title=\"std\" ".
        //    "media=\"handheld\" href=\"".$this->path."handheld.css\" type=\"text/css\">\n";


        print "\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";

        if($skiphead == false)
            $this->printHead();
        else
            echo "\t<!-- skipped head -->\n";
    }

    function printHead(){
        print "</head>\n<body>\n".	
            "<div class=\"body_data\">\n";
    }

    function __destruct(){
        //$this_page = $this->getPageName();
        //$dirName  = $this->getDirName();
        //print "<p>This page: $dirName </p>\n";

        print "\n\n".
            "</div>\n".
            "<div class=\"nav_links\">\n".
            "<div class=\"nav_logo\"><img src=\"".$this->path."/favicon_gubbe.png\"><br><br></div>\n".
            "<div class=\"nav_colour\">\n";

        print "<br>\n".
            "<div class=\"nav_level1\">".
              "<a href=\"https://github.com/jsiei97/FunTechHouse_Web\">FunTechHouse_Web</a>".
            "</div>".
            "<br>\n";

        foreach ($this->weekTimers as $name => $topic) {
            print $this->nav_link("index.php?WEEKTIMER=".$name, $name,1);
        }
        print "<br>\n";



        print "\n<br><br><br><br><br><br>\n\n";

        print "</div>\n".
            "</div>\n".
            //"</div>\n".
            //"<div class=\"print_info\">&copy fun-tech.se - Simonsson Fun Technologies</div>\n".
            "</body>\n".
            "</html>\n";
    }


    function nav_link($target, $name, $level){
        $target = $this->path.$target;
        $str .= "<div class=\"nav_level".$level."\"><a href=\"".$target."\">".$name."</a></div>\n";


        if(strpos($target, $this->page_name) !== false)
        {
            $str .= $this->sub_nav_data;
        }

        return $str;
    }


    function getPageName(){
        //$this_page = $_SERVER['PHP_SELF'];
        //print "<!-- This page: ".$this_page." -->\n";

        $path_array = explode("/",$_SERVER['PHP_SELF']);
        $this_page = $path_array[count($path_array)-1];	

        //print "<!--\n";
        //print_r($path_array);
        //print "\n-->\n";
        //print "<!-- This page: ".$this_page." -->\n";

        $this->page_name = $this_page;
        return $this_page;
    }

    function getDirName(){
        //$this_page = $_SERVER['PHP_SELF'];
        //print "<!-- This page: ".$this_page." -->\n";

        $path_array = explode("/",$_SERVER['PHP_SELF']);
        $cnt = count($path_array);
        $this_page = $path_array[$cnt-2];	

        //print "<!--\n";
        //print_r($path_array);
        //print "\n-->\n";
        //print "<!-- This page: ".$this_page." -->\n";

        $this->page_name = $this_page;
        return $this_page;
    }

    /**
     * @return Absolute url to the current working dir
     */
    function getCwdServerUrl()
    {
        $path_array = explode("/",$_SERVER['PHP_SELF']);

        $url = "http://".$_SERVER['SERVER_NAME']."/";
        $cnt = count($path_array);
        for($i=1; $i<($cnt-1); $i++)
        {
            $url .= $path_array[$i]."/";
        }
        return $url;
    }

    //--- Build the page navigation
    function h1($text, $name)
    {
        return $this->html->h1($text, $name);
    }
    function h2($text, $name)
    {
        $this->sub_nav_data .= "<div class=\"nav_level2\"><a href=\"".$_SERVER['PHP_SELF']."#".$name."\">".$text."</a></div>";
        return $this->html->h2($text, $name);
    }
    function h3($text, $name)
    {
        $this->sub_nav_data .= "<div class=\"nav_level3\"><a href=\"".$_SERVER['PHP_SELF']."#".$name."\">".$text."</a></div>";
        return $this->html->h3($text, $name);
    }
    function h4($text, $name)
    {
        $this->sub_nav_data .= "<div class=\"nav_level4\"><a href=\"".$_SERVER['PHP_SELF']."#".$name."\">".$text."</a></div>";
        return $this->html->h4($text, $name);
    }

}
?>
