<?php
date_default_timezone_set('America/Sao_Paulo');
ini_set('max_execution_time','300');

/**
 * Formata número inteiro para decimal com duas casas e com separador de milhar
 * @param integer $numero inteiro a ser formatado
 * @return string
 */
 
function inteiro_decimal_br($numero)
{
    $numero = number_format($numero, 2, ',', '.');
    return $numero;
}


function formatarCnpj($cnpj_cpf){
  if(strlen(preg_replace("/\D/", '', $cnpj_cpf)) === 11){
    $response = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
  }else{
    $response = preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
  }
  return $response;
}


?>