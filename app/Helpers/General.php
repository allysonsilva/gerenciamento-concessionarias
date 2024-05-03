<?php

if (! function_exists('sanitize_cnpj')) { // @codeCoverageIgnore
    /**
     * Removes all characters from a string returning only the numbers of a CNPJ.
     *
     * @param string $cnpj
     *
     * @return string
     */
    function sanitize_cnpj(string $cnpj): string
    {
        return filter_var(str_replace(array('.','-','/'), '', trim($cnpj)), FILTER_SANITIZE_NUMBER_INT);
    }
};
