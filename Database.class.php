<?php
class Database
{
    function selectAllCarRows()
    {
        $link = mssql_connect("") or die("Не соединилось!!!");
        mssql_select_db('[selection]') or die("Не найдена БД");

        $query = "SELECT * FROM dbo.abw_selection";

        $result = mssql_query($query) or die("Error :" . mssql_get_last_message() );

        if (!mssql_num_rows($result)) {
            mssql_free_result($result);
            return array();
        }
        $cars = array();

        while ($line = mssql_fetch_assoc($result)) {
            $cars[] = $line;
        }
        mssql_free_result($result);

        return $this->encode_items($cars);
    }

    function encode_items($array)
    {
        foreach($array as $key => $value)
        {
            if(is_array($value))
            {
                $array[$key] = $this->encode_items($value);
            }
            else
            {
                $array[$key] = iconv('cp1251','utf-8',  $value);
            }
        }

        return $array;
    }
}