<?php
class html{

    function html(){
    }

    //--- Special funktioner som visar något -----------------
    function showCode($file, $short_name)
    {
        if($short_name == "")
        {
            $short_name = $file;
        }

        $str = "<div id=\"codebox\">\n".
            "<p>Filename: <a href=\"".$file."\" target=\"_blank\">".$short_name."</a></p>\n". 
            "<pre>\n";
        //include($file); 
        $lines = file($file); foreach ($lines as $line_num => $line)
        { 
            $str .= htmlspecialchars($line) . ""; 
        }
        $str .= "</pre></div>\n";

        return $str;
    }




    //--- TABELL taggar ---------------------------------------
    function table($text){
        return "<table>\n".$text."</table>\n";
    }

    function td($text){
        if($text == ""){
            $text = "&nbsp";
        }
        $text = "<td>".$text."</td>\n";
        return $text;
    }

    function tdCompose($text, $width, $col, $row, $color){
        $str = "<td";
        if($width <> 0 or $width <> ""){
            $str .= " WIDTH=\"".$width."\"";
        }
        if($color <> ""){
            $str .= " BGCOLOR=\"".$color."\"";
        }
        if($col <> ""){
            $str .= " colspan=\"".$col."\"";
        }
        if($row <> ""){
            $str .= " rowspan=\"".$row."\"";
        }

        $str .= " >".$text."</td>\n";
        return $str;
    }

    function tdEmpty(){
        return "<td><p>&nbsp;</p></td>\n";
    }

    function th($text){
        return "<th align=\"left\"><p>".$text."</p></th>\n";
    }

    function thCompose($text, $width, $col, $row, $color){
        $str = "<th";
        if($width <> 0 or $width <> ""){
            $str .= " WIDTH=\"".$width."\"";
        }
        if($color <> ""){
            $str .= " BGCOLOR=\"".$color."\"";
        }
        if($col <> ""){
            $str .= " colspan=\"".$col."\"";
        }
        if($row <> ""){
            $str .= " rowspan=\"".$row."\"";
        }

        $str .= " >".$text."</th>\n";
        return $str;
    }

    function thEmpty(){
        return "<th><p>&nbsp;</p></th>\n";
    }

    function tr($text){
        return "<tr valign=\"bottom\">".$text."</tr>\n";
    }

    function trCompose($text, $align, $valign, $color){
        $string = "<tr";
        if($align <> "")
            $string .= " align=\"".$align."\"";

        if($valign == "")
            $string .= " valign=\"bottom\"";
        else 
            $string .= " valign=\"".$valign."\"";

        if($color <> "")
            $string .= " bgcolor=\"".$color."\"";

        $string .= ">".$text."</tr>\n";
        return $string;
    }

    //--- Form:s taggar----------------------------------------

    function form($target, $text){
        return "<form action=\"".$target."\" method=\"GET\">\n".
            $text.
            "</form>\n";
    }

    function formGet($target, $text){
        return "<form action=\"".$target."\" method=\"GET\">\n".
            $text.
            "</form>\n";
    }

    function formPost($target, $text){
        return "<form action=\"".$target."\" method=\"POST\">\n".
            $text.
            "</form>\n";
    }

    function inputText($name, $value, $size, $max){
        return "<INPUT Name=\"".$name."\" ".
            "VALUE=\"".$value."\" ".
            "TYPE=\"TEXT\" ".
            "SIZE=\"".$size."\" ".
            "MAXLENGTH=\"".$max."\">\n";
    }

    function inputTextArea($name, $value, $rows, $cols){
        $str = "<textarea name=\"".$name."\" rows=\"".$rows."\" cols=\"".$cols."\">";
        if($value != ""){
            $str .= $value;
        } 
        $str .= "</textarea>";
        return $str;
    }

    function inputPassword($name, $value, $size, $max){
        return "<INPUT Name=\"".$name."\" ".
            "VALUE=\"".$value."\" ".
            "TYPE=\"password\" ".
            "SIZE=\"".$size."\" ".
            "MAXLENGTH=\"".$max."\">\n";
    }

    function inputSend($text){
        return "<input value=\"".$text."\" type=\"submit\">\n";
    }

    function inputHidden($namn, $value){
        return "<input type=\"hidden\" ".
            "name=\"".$namn."\" value=\"".$value."\">\n";
    }

    function select($name, $size, $text){
        return "<select name=\"".$name."\" size=\"".$size."\">\n".$text."</select>\n";
    }

    function option($value, $default_value, $text){
        if($value == $default_value){
            return "<option value=\"".$value."\" selected>".$text."</option>\n";
        } 
        return "<option value=\"".$value."\">".$text."</option>\n";
    }


    //--- Vanliga taggar...------------------------------------

    function h1($text, $name){
        if($name != ""){
            return "<h1><A NAME=\"".$name."\">".$text."</h1>\n";
        } else {
            return "<h1>".$text."</h1>\n";
        }
    }
    function h2($text, $name){
        if($name != ""){
            return "<h2><A NAME=\"".$name."\">".$text."</h2>\n";
        } else {
            return "<h2>".$text."</h2>\n";
        }
    }
    function h3($text, $name){
        if($name != ""){
            return "<h3><A NAME=\"".$name."\">".$text."</h3>\n";
        } else {
            return "<h3>".$text."</h3>\n";
        }
    }
    function h4($text, $name){
        if($name != ""){
            return "<h4><A NAME=\"".$name."\">".$text."</h4>\n";
        } else {
            return "<h4>".$text."</h4>\n";
        }
    }

    /**
     * Ordered List, put List Items (li) inside.
     * More or less 1,2,3.
     */
    function ol($text){
        return "<ol>".$text."</ol>\n";
    }
    /**
     * Unordered List, put List Items (li) inside.
     * More or less bullets, * * *.
     */
    function ul($text){
        return "<ul>".$text."</ul>\n";
    }
    /**
     * List Item to use inside ol or ul.
     */
    function li($text){
        return "<li>".$text."</li>\n";
    }

    function p($text){
        if($text == ""){
            return "<p>&nbsp;</p>\n";
        }
        return "<p>".$text."</p>\n";
    }

    function a($target, $text){
        return "<a href=\"".$target."\">".$text."</a>";
        //return "<a href=\"".$target."\" target=\"_blank\">".$text."</a>";
    }

    function strong($text){
        return "<strong>".$text."</strong>\n";
    }


    // --- Vanliga taggar fast med lite extra runt så de passar in.... -----------------

    function img($path){
        return "<br><img src=\"".$path."\"><br clear=\"all\">\n";
    }

    function img_thumb($path, $width){
        return "<br>".$this->link_ext($path,"<img src=\"".$path."\" width=\"".$width."\" >")."<br clear=\"all\">\n";
    }

    function link($target, $text){
        if($text == "")
            $text = $target;
        return "<a href=\"".$target."\">".$text."</a>\n";
    }
    function link_ext($target, $text){
        if($text == "")
            $text = $target;
        return "<a href=\"".$target."\" target=\"_blank\">".$text."</a>";
    }

}
?>
