<?php
/**
 * Created by PhpStorm.
 * User: stephenmunabo
 * Date: 5/18/17
 * Time: 10:30 AM
 */

namespace Mustaard\Metaphor;


interface MetaphorMap
{

    public function importCsv($arr);
    public function mapCsvToCrmFields($data);

}