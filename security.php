<?php
 
/**
 * Protege o banco de dados contra ataques de SQL Injection
 *
 * Remove palavras que podem ser ofensivas à integridade do banco
 * Adiciona barras invertidas a uma string
 *
 * @uses $_REQUEST= _antiSqlInjection($_REQUEST);
 * @uses $_POST = _antiSqlInjection($_POST);
 * @uses $_GET = _antiSqlInjection($_GET);
 *
 * @author Igor Escobar
 * @email blog [at] igorescobar [dot] com
 *
 */
 
function _antiSqlInjection($Target){
    $sanitizeRules = array('OR','FROM','SELECT','INSERT','DELETE','WHERE','DROP TABLE','SHOW TABLES','*','--','=');
    foreach($Target as $key => $value):
        if(is_array($value)): $arraSanitized[$key] = _antiSqlInjection($value);
        else:
            $arraSanitized[$key] = (!get_magic_quotes_gpc()) ? addslashes(str_ireplace($sanitizeRules,"",$value)) : str_ireplace($sanitizeRules,"",$value);
        endif;
    endforeach;
    return $arraSanitized;
}
 
?>